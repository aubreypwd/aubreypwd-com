<?php

namespace aubreypwd\theme;

require_once 'hooks.php';

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

function my_gravatar( int $size = 150 ) {

	return str_replace(
		'://aubreypwd.com/wp-content/uploads/',
		"://ik.imagekit.io/aubreypwd/tr:f-webp,q-100/,w-{$size},h-{$size}",
		'https://aubreypwd.com/wp-content/uploads/2025/04/Computer-Lab-Cropped.jpg'
	);
}
