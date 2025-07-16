<?php

namespace aubreypwd\theme;

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

// Disable some robot meta.
add_filter( 'wp_robots', function( $robots ) {

	unset( $robots['max-image-preview'] );

	return $robots;
} );

// async and defer scripts.
add_action( 'script_loader_tag', function( $tag, $handle ) {

	$defer = [
	];

	$async = [
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

remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Convert images on the fly to webp and reduce image size automatically.
add_filter( 'the_content', function( $content ) {

	$transient_key = 'aubreypwd_imagekit_200';

	if ( 'failed' === get_transient( $transient_key ) ) {
		return $content; // A previous attempt failed, assume it's still down until the transient expires.
	}

	// I have this on my server, it should proxy it and 200 OK.
	$headers = @get_headers( 'https://ik.imagekit.io/aubreypwd/pixel.png' );

	if ( false === $headers || ! strstr( ( $headers[0] ?? '' ), '200' ) ) {

		// Don't trust imagekit for 5 minutes and try again.
		set_transient( $transient_key, 'failed', 60 * 5 );

		return $content; // Imagekit isn't responding, use my own images on my server.
	}

	delete_transient( $transient_key ); // Make sure previous failures are removed, we got a 200.

	// Whatever host this site is running on.
	$host = preg_quote( $_SERVER['HTTP_HOST'], '#' );

	// Image replacement: add transformations: webp, 70 quality, and max width 1024 (my theme will never be wider than that).
	$content = preg_replace(
		sprintf( '#https?://(?:aubreypwd\.com|%s)/wp-content/uploads/([^\s"\']+?\.(jpe?g|png|gif|svg|bmp|ico|tiff|avif|webp))#i', $host ),
		"https://ik.imagekit.io/aubreypwd/tr:f-web,q-70,w-1024/$1",
		$content
	);

	// Video replacement, no transformations.
	$content = preg_replace(
		sprintf( '#https?://(?:aubreypwd\.com|%s)/wp-content/uploads/([^\s"\']+?\.(mp4|webm|mov))#i', $host ),
		"https://ik.imagekit.io/aubreypwd/$1",
		$content
	);

	return $content;
} );

// Allow WYSIWYG to insert video using <video>.
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
