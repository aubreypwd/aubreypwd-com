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
function my_gravatar( int $size = 150 ) {

	// Yes, serve it up via imagekit so we get super small version.
	return str_replace(
		'://aubreypwd.com/wp-content/uploads/',
		"://ik.imagekit.io/aubreypwd/tr:f-webp,q-99,w-{$size},h-{$size}/",
		'https://aubreypwd.com/wp-content/uploads/avatar.jpg'
	);
}

require_once 'hooks.php';
