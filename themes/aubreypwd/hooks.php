<?php // phpcs:disable

namespace aubreypwd\theme;

require_once 'functions.php';

// Make sure we can tell what posts have tags, and what they are tagged with.
add_action( 'body_class', function( $classes ) {

	$tags = array_map(
		function( $tag_id ) {
			return "tagged-{$tag_id}";
		},
		wp_get_post_tags( get_the_ID(), [ 'fields' => 'ids' ] )
	);

	if ( empty( $tags ) ) {
		return $classes;
	}

	return array_merge(
		$classes,
		$tags,
		[ 'has-tags' ]
	);
} );

// Make sure API changes to sticky posts result in one sticky post.
add_action(
	'post_stuck',
	function( $post_id ) {
		update_option( 'sticky_posts', [ (int) $post_id ] );
	},
	999
);

// Make sure that sticky posts I set are sticky.
add_action(
	'save_post',
	function( $post_id ) {

		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Make sure sticky posts are just the one we just saved.
		if ( isset( $_POST['sticky'] ) && 'sticky' === $_POST['sticky'] ) {
			update_option( 'sticky_posts', [ (int) $post_id ] );
		}
	},
	999
);

// Make sure tags show like categories.
add_action( 'init', function() {

	// Get the existing post_tag registration.
	$tag_tax = get_taxonomy( 'post_tag' );

	// Modify its arguments.
	$tag_tax->hierarchical = true; // Makes the UI use checkboxes like categories.
	$tag_tax->meta_box_cb  = 'post_categories_meta_box'; // Forces category-style metabox.
	$tag_tax->rewrite['hierarchical'] = false; // Keep URL structure flat.

	// Re-register the taxonomy with new behavior.
	register_taxonomy(
		'post_tag',
		'post',
		(array) $tag_tax
	);
}, 11 );

// Make sure we know if the latest post is being displayed.
add_action( 'body_class', function( $classes ) {

	if ( ! is_latest_post() ) {
		return $classes;
	}

	return array_merge(
		$classes,
		[
			'latest-post',
		]
	);
} );

// Disable (404) all archive pages except tags.
add_action( 'template_redirect', function() {

	// Allow tag archives
	if ( is_tag() ) {
		return;
	}

	// Block all other archives: category, date, author, post type, etc.
	if ( is_archive() ) {

		global $wp_query;

		$wp_query->set_404();

		status_header( 404 );
		nocache_headers();

		exit;
	}
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
	unregister_taxonomy_for_object_type( 'media_category', 'attachment' );
	unregister_taxonomy_for_object_type( 'media_post_tag', 'attachment' );

} );

// Remove category and tag meta boxes.
add_action( 'admin_menu', function() {
	remove_meta_box( 'categorydiv', 'post', 'side' );
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

// Set permalink structure to /posts|pages/slug.html...
add_action( 'init', function() {

	// Posts...
	add_rewrite_rule(
		'^posts/([^/]+)\.html$',
		'index.php?name=$matches[1]',
		'top'
	);

	global $wp_rewrite;

	$wp_rewrite->set_permalink_structure( '/posts/%postname%.html' );

	add_filter( 'post_type_link', function( $url, $post ) {

		if ( $post->post_type === 'post' ) {
			return home_url( "/posts/{$post->post_name}.html" );
		}

		return $url;
	}, 10, 2 );

	// Pages...
	add_rewrite_rule(
		'^pages/([^/]+)\.html$',
		'index.php?pagename=$matches[1]',
		'top'
	);

	add_filter( 'page_link', function( $url, $post_id ) {

		$post = get_post( $post_id );

		if ( $post && $post->post_type === 'page' ) {
			return home_url( "/pages/{$post->post_name}.html" );
		}

		return $url;
	}, 10, 2 );

	// Tags...
	add_rewrite_rule(
		'^tags/([^/]+)\.html$',
		'index.php?tag=$matches[1]',
		'top'
	);

	add_filter( 'term_link', function( $url, $term, $taxonomy ) {

		if ( $taxonomy === 'post_tag' ) {
			return home_url( "/tags/{$term->slug}.html" );
		}

		return $url;
	}, 10, 3 );

	// /index.html
	add_rewrite_rule(
		'^index\.html$',
		'index.php',
		'top'
	);

	// Flush.
	if ( 'flushed' === get_option( sprintf( 'aubreypwd/theme/permalinks_flushed/%s', filemtime( __FILE__ ) ) ) ) {
		return; // Don't flush.
	}

	// Flush once.
	flush_rewrite_rules();

	update_option( sprintf( 'aubreypwd/theme/permalinks_flushed/%s', filemtime( __FILE__ ) ), 'flushed' );
} );

// Redirect ?p= and ?page_id= to their normal .html URLs.
add_action( 'template_redirect', function() {

	// Detect ?p= or ?page_id=.
	$id = isset( $_GET['p'] )

		// ?p=X...
		? absint( $_GET['p'] )

		// Test for ?page_id=...
		: (
			isset( $_GET['page_id'] )
				? absint( $_GET['page_id'] )
				: false
		);

	if ( ! $id ) {
		return;
	}

	// Redirect to the proper URL...
	wp_redirect( get_permalink( $id ), 301 );
		exit;
} );

// Store what URL's go to what Post ID's.
add_action( 'template_redirect', function() {

	if ( is_home() ) {
		return;
	}

	global $post;

	if ( ! is_a( $post, '\WP_Post' ) ) {
		return;
	}

	@file_put_contents(
		sprintf( '%s/requests.urls', untrailingslashit( ABSPATH ) ),
		sprintf( "%s,%s\n", $_SERVER['REQUEST_URI'], $post->ID ?? -1 ),
		FILE_APPEND | LOCK_EX
	);
} );