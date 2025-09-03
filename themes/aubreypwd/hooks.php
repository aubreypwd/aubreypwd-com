<?php

namespace aubreypwd\theme;

// Use /page|post-<id>.html permalinks instead of ?p=<id> and ?page_id=<id>.
add_action( 'template_redirect', function() {

	if ( ! isset( $_SERVER['HTTP_HOST'] ) || 'aubreypwd.com' !== $_SERVER['HTTP_HOST'] ) {
		return; // Locally don't do this because it requires Apache rewrites.
	}

	// Symlink .htaccess to WP install root.
	ob_start( function( $html ) {

		return preg_replace_callback(
			sprintf(
				'#://%s/\?(p|page_id)=([0-9]+)#',
				preg_quote( $_SERVER['HTTP_HOST'], '#' )
			),
			function( $matches ) {
				return sprintf( '://%s/%s/%d.html', $_SERVER['HTTP_HOST'], $matches[1] === 'p' ? 'posts' : 'pages', $matches[2] );
			},
			$html
		);
	} );
} );

// Just disable cononical redirects all together, they aren't worth it: my server will figure it out.
remove_filter( 'template_redirect', 'redirect_canonical' );

// Clean up my admin menu.
add_action( 'admin_menu', function() {

	// This shit is useless.
	remove_submenu_page( 'options-general.php', 'options-privacy.php' );
	remove_submenu_page( 'options-general.php', 'sqlite-integration' );
	remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
	remove_submenu_page( 'themes.php', 'theme-editor.php' );
	remove_submenu_page( 'users.php', 'wp-persistent-login-pricing' );

}, PHP_INT_MAX );

// Permalinks will always be plain.
add_action( 'admin_menu', function () {

	remove_submenu_page( 'options-general.php', 'options-permalink.php' );

	add_action( 'admin_init', function() {

		if ( true !== update_option( 'permalink_structure', '' ) ) {
			return; // It wasn't fucked up, don't flush rules.
		}

		flush_rewrite_rules();
	} );
} );

// Menus.
add_action( 'init', function() {

	register_nav_menu(
		'aubreypwd/header',
		__( 'Header', 'aubreypwd' )
	);

	register_nav_menu(
		'aubreypwd/footer',
		__( 'Footer', 'aubreypwd' )
	);
} );

// Init.
add_action( 'init', function() {

	// Disable emoji stuff in the <head>
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );

	// Disble post page thumbnails.
	remove_post_type_support( 'post', 'thumbnail' );
	remove_post_type_support( 'page', 'thumbnail' );

	// Remove site icon bullshit.
	remove_action( 'wp_head', 'wp_site_icon', 99 );

	// Remove these taxonomies.
	unregister_taxonomy_for_object_type( 'category', 'post' );
	unregister_taxonomy_for_object_type( 'post_tag', 'post' );
	unregister_taxonomy_for_object_type( 'media_category', 'attachment' );
	unregister_taxonomy_for_object_type( 'media_post_tag', 'attachment' );

} );

// Remove category and tag meta boxes.
add_action( 'admin_menu', function() {
	remove_meta_box( 'categorydiv', 'post', 'side' );
	remove_meta_box( 'tagsdiv-post_tag', 'post', 'side' );
} );

// Remove the main Dashboard page from the menu.
add_action( 'admin_menu', function() {
	remove_menu_page( 'index.php' );
}, 20 );

// Redirect dashboard to posts list.
add_action( 'admin_init', function() {

	global $pagenow;

	if ( 'index.php' !== $pagenow ) {
		return;
	}

	wp_redirect( admin_url( 'edit.php' ) );

	exit;
} );

// Reposition Extended Search under Settings.
add_action( 'admin_menu', function() {

	remove_menu_page( 'wp-es' );

	add_options_page(
		'Extended Search',
		'Extended Search',
		'manage_options',
		'wp-es'
	);
}, 20 );

// Hide the logo in the admin.
add_action( 'admin_head', function() {
	?>

	<style>

		#adminmenu {
			margin-top: -11px;
		}

		#wp-admin-bar-wp-logo {
			display: none;
		}

	</style>

	<?php
} );

// Follow.it verification.
add_action( 'wp_head', function() {
	?>

	<meta name="follow.it-verification-code" content="maWa3ZU9dT2pHz0GgG0Z"/>

	<?php
} );

// Enqueuing scripts.
add_action( 'wp_enqueue_scripts', function() {

	// I don't need fucking jQuery!
	wp_dequeue_script( 'jquery' );
} );

// Disable the customizer.
add_action( 'admin_init', function() {

	// Block access to the Customizer UI
	add_action( 'load-customize.php', function () {
		wp_die( __( 'The Customizer is currently disabled.', 'aubreypwd' ) );
	} );

	// Remove the Customizer script and related actions.
	add_action( 'admin_init', function () {
		remove_action( 'plugins_loaded', '_wp_customize_include', 10 );
		remove_action( 'admin_enqueue_scripts', '_wp_customize_loader_settings', 11 );
	} );

	// Remove the "Customize" capability from all roles.
	add_filter( 'map_meta_cap', function ( $caps, $cap ) {
		return ( $cap === 'customize' ) ? [ 'do_not_allow' ] : $caps;
	}, 10, 2 );

} );

// Disable some robot meta shit I don't need.
add_filter( 'wp_robots', function( $robots ) {

	unset( $robots['max-image-preview'] );

	return $robots;
} );

// async and defer scripts.
add_action( 'script_loader_tag', function( $tag, $handle ) {

	$defer = [
		// None right now.
	];

	$async = [
		// None right now.
	];

	// Add defer to scripts.
	if ( in_array( $handle, $defer, true ) ) {
		$tag = str_replace( '<script', '<script defer', $tag );
	}

	// Add async to scripts.
	if ( in_array( $handle, $async, true ) ) {
		$tag = str_replace( '<script', '<script async', $tag );
	}

	return $tag;
}, 10, 2 );

// Remove emoji bullshit.
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Convert images on the fly to avif and reduce image size with imagekit.io.
add_filter( 'the_content', function( $content ) {

	$converted_content = $content;

	// Whatever host this site is running on.
	$host = preg_quote( $_SERVER['HTTP_HOST'], '#' );

	// Image replacement: add transformations: avif, 70 quality, and max width 1024 (my theme will never be wider than that).
	$converted_content = preg_replace(
		sprintf( '#https?://(?:aubreypwd\.com|%s)/wp-content/uploads/([^\s"\']+?\.(jpe?g|png|bmp|webp))#i', $host ),
		'https://ik.imagekit.io/aubreypwd/tr:f-web,q-71,w-1024/wp-content/uploads/$1',
		$converted_content
	);

	// Video replacement, no transformations.
	$converted_content = preg_replace(
		sprintf( '#https?://(?:aubreypwd\.com|%s)/wp-content/uploads/([^\s"\']+?\.(mp4|webm|mov))#i', $host ),
		'https://ik.imagekit.io/aubreypwd/wp-content/uploads/$1',
		$converted_content
	);

	$transient = 'aubreypwd/theme/imagekit/network_check';

	if ( isset( $_GET['reset_imagekit_check'] ) ) {
		delete_transient( $transient ); // Reset the transient to re-test.
	}

	$check = get_transient( $transient );

	if ( 'failed' === $check ) {

		// It recently failed, use our normal content until transient expires.
		return $content;

	} elseif ( 'succeeded' === $check ) {

		// Trust the converted content until the transient expires.
		return $converted_content;

	// We don't know if it failed or not, let's test.
	} else {

		// I have this on my server, it should proxy it and 200 OK.
		$headers = @get_headers( 'https://ik.imagekit.io/aubreypwd/pixel.png' );

		// We got a 200 OK.
		if ( strpos( ( $headers[0] ?? '' ), '200' ) ) {

			// Remember this success for an hour.
			set_transient( $transient, 'succeeded', HOUR_IN_SECONDS );

			// Use converted content since we can trust the source.
			return $converted_content;

		} else {

			// We can't trust the source, so don't trust it for 10 minutes.
			set_transient( $transient, 'failed', MINUTE_IN_SECONDS * 10 );
		}
	}

	return $content; // Default to our content.
} );

// Allow WYSIWYG to insert video using normal <video> so I don't have to use a bloated plugin.
add_filter( 'media_send_to_editor', function( $html, $id, $attachment ) {

	$mime = get_post_mime_type( $id );

	// Check if it's a video
	if ( strpos( $mime, 'video/' ) === 0 ) {

		$src = wp_get_attachment_url( $id );

		// Output <video> tag
		$html = sprintf(
			'<video controls preload="metadata" src="%s" decoding="async" playsinline muted>',
			esc_url( $src )
		);
	}

	return $html;

}, 10, 3 );

// Add RSS to the theme.
add_theme_support( 'automatic-feed-links' );

// Replace youtube embeds with a thumbnail that links to the video instead.
add_filter( 'the_content', function( $content ) {

	return preg_replace_callback(
		'#(?<!["\'=])\bhttps?://(?:www\.)?(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]{11})\b#i',

		function ( $matches ) {
			return sprintf(
				'<figure><a href="%s" target="_blank" rel="noopener"><img src="%s" alt="%s" fetchpriority="high" /></a><figcaption>%s</figcaption></figure>',
				esc_url( "https://www.youtube.com/watch?v={$matches[1]}" ),
				esc_url( "https://img.youtube.com/vi/{$matches[1]}/maxresdefault.jpg" ),
				__( 'Watch on YouTube', 'aubreypwd' ),
				sprintf(
					'<a href="%s">%s</a>',
					esc_url( "https://www.youtube.com/watch?v={$matches[1]}" ),
					__( 'Watch on Youtube...', 'aubreypwd' )
				)
			);
		},
		$content
	);
}, PHP_INT_MIN );

// Disable the embed in the admin, just show the URL.
add_filter( 'embed_oembed_html', function( $html, $url, $attr, $post_id ) {

	if ( is_admin() && ( strpos( $url, 'youtube.com' ) !== false || strpos( $url, 'youtu.be' ) !== false ) ) {
		return esc_url( $url ); // just show the raw URL
	}

	return $html;
}, 10, 4 );
