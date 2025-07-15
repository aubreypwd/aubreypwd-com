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
	return "https://0.gravatar.com/avatar/09601923fd59a7433892711376c37e41/?s={$size}&d=identicon&r=g&f=webp";
}
