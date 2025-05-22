<?php // phpcs:disable

// Remove featured image support.
add_action( 'init', function() {

	remove_post_type_support( 'post', 'thumbnail' );
	remove_post_type_support( 'page', 'thumbnail' );
} );

// Unregister default taxonomies.
add_action( 'init', function() {

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

// Add PWA manifest and meta tags.
add_action( 'wp_head', function() {
	?>

	<link rel="manifest" href="<?php echo esc_url( get_site_url() . '/manifest.json' ); ?>">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="apple-mobile-web-app-title" content="@aubreypwd">
	<link rel="apple-touch-icon" href="<?php echo esc_url( get_site_url() . '/wp-content/uploads/2025/04/cropped-MG_4726-Cropped.jpg' ); ?>">

	<?php
} );

// Redirect dashboard to posts list.
add_action( 'admin_menu', function() {
	remove_menu_page( 'index.php' );
}, 20 );

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