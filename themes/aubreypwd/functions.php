<?php

namespace aubreypwd\theme;

// Is the current post the latest post?
function is_latest_post() {

	if ( ! in_the_loop() || ! is_main_query() ) {
		return false;
	}

	global $post; // Current post in the loop.

	// Get the latest post (ID) in the DB.
	$latest_post = get_posts(
		[
			'posts_per_page' => 1,
			'number'         => 1,
			'fields'         => 'ids',
			'post_status'    => 'publish',
		]
	);

	return ( $latest_post[0] ?? 0 ) === $post->ID;
}

// My gravatar.
function my_avatar( int $size = 150 ) {

	// Yes, serve it up via imagekit so we get super small version.
	return str_replace(
		[
			'://aubreypwd.com/wp-content/uploads/',
			sprintf( '://%s/wp-content/uploads/', $_SERVER['HTTP_HOST'] ?? 'aubreypwd.com' ),
		],
		"://ik.imagekit.io/aubreypwd/tr:f-webp,w-{$size},h-{$size}/",
		get_site_icon_url()
	);
}

// Store the post in uploads/html/ for faster rendering later.
function htmlify( $post_id ) {

	if ( isset( $_GET['_as_html'] ) ) {
		return; // Don't create the HTML when the HTML is being created.
	}

	if ( ! isset( $_SERVER['REQUEST_URI'], $_SERVER['HTTP_HOST'] ) ) {
		return; // We need these to form a valid URL.
	}

	$html = file_get_contents( add_query_arg( '_as_html', '1', sprintf( 'https://%s/%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'] ) ) );

	if ( empty( $html ) ) {
		return;
	}

	@mkdir( sprintf( '%s/html', wp_get_upload_dir()['basedir'] ) );

	@file_put_contents( sprintf( '%s/html/%s.html', wp_get_upload_dir()['basedir'], $post_id ), $html );
}

require_once 'hooks.php';
