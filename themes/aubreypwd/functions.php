<?php  // phpcs:disable

namespace aubreypwd\theme;

require_once 'hooks.php';

// Is the current post the latest post?
function is_latest_post() {

	if ( is_archive() ) {
		return false;
	}

	if ( is_page() ) {
		return false;
	}

	global $post; // Current post in the loop.

	$sticky_posts = get_option( 'sticky_posts', [] );

	if ( ! empty( $sticky_posts ) ) {

		// We have sticky post, show that instead.
		return in_array( $post->ID ?? 0, $sticky_posts, true );
	}

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
		"://ik.imagekit.io/aubreypwd/tr:f-avif,w-{$size},h-{$size}/",
		get_site_icon_url()
	);
}
