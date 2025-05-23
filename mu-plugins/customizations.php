<?php // phpcs:disable

/** Site Customizations */

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

// Custom styles.
add_action( 'wp_head', function() {
	?>

	<style>

		#back-to-top,
		#header > div.group > div:nth-child(1) {
			display: none !important;
		}

		.container-inner {
			max-width: 880px;
		}

		.toggle-search {
			right: 13px;
		}

		.entry a:visited {
			color: rgb(237, 82, 237) !important;
		}


		.post-title {
			font-size: 150% !important;
		}

		.entry h1, h2 {
			font-size: 110% !important;
			font-weight: bold !important;
		}

		.entry h3 {
			font-size: 90% !important;
			font-weight: bold;
		}

		.entry h4, h5, h6 {
			font-size: 90% !important;
		}

		article > .pad {
			padding-top: 25px;
			padding-bottom: 25px;
		}

		.toggle-search {
			padding: 9px 0 3px 0;
		}

		#copyright {
			margin-top: 6px;
		}

		@media only screen and (max-width: 479px) {
			.sidebar.s2,
			#profile-description br {
				display: none;
			}

			#profile {
				border-bottom: 1px solid #393939;
			}

			.nav-menu.mobile > div > ul > li:last-child {
			margin-bottom: 0;
			}
		}

		.toggle-search {
			background: #1E1E1E;
		}

		.s2 {
			background: #363636;
			border-right: 1px solid #1E1E1E;
		}

		#profile-image img {
			height: 93px;
			width: auto;
		}

		.cscfForm .form-control {
			border-radius: 8px !important;
			padding: 20px !important;

		}

		.cscfForm input[type="submit"] {
			margin-top: 10px;
		}

		.cscfForm label {
			display: none;
		}

		.post img {
			border-radius: 10px;
			border: 1px solid #000;
		}

		/* All Posts Page */
		.display-posts-listing {
			margin: 0 0 15px 20px !important;
		}

	</style>

	<?php
} );