<?php

namespace aubreypwd\theme;

// Menus.
add_action( 'after_theme_setup', function() {

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

// Async and defer scripts.
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
