<?php
/**
 * Plugin Name: Markdown Support
 */

namespace aubreypwd\markdown;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// This allows us to make MD into HTML.
require_once sprintf( '%s/inc/Parsedown.php', untrailingslashit( __DIR__ ) );

// Hook into the admin...
add_action( 'current_screen', function( $screen ) {
	if ( ! is_admin() ) {
		return;
	}

	if ( 'post' !== $screen->base ) {
		return;
	}

	// Add Markdown checkbox.
	add_action( 'add_meta_boxes', function() {

		global $post;

		if ( ! in_array( get_post_type( $post ), [ 'post', 'page' ], true ) ) {
			return;
		}

		add_meta_box(
			'post_markdown',
			__( 'Markdown', 'aubreypwd' ),

			// Render.
			function( $post ) {

				$markdown = (string) get_post_meta( $post->ID ?? 0, 'post_markdown_html', true );

				?>

				<label for="post_markdown" title="<?php echo ( empty( $markdown ) ) ? '' : esc_html__( 'To disable markdown, you must clear the post content first, and update.' ); ?>">

					<input
						type="checkbox"
						name="post_markdown"
						<?php checked( ! empty( $markdown ) ); ?>
						<?php disabled( ! empty( $markdown ) ); ?>
					>
					<?php esc_html_e( 'Use Markdown', 'aubreypwd' ); ?>
				</label>

				<?php wp_nonce_field( 'post_markdown_enable', 'post_markdown_nonce' ); ?>

				<?php
			},
			[
				'post',
				'page',
			],
			'side',
			'high'
		);
	} );

	add_action( 'admin_head', function() {

		global $post;

		if ( ! in_array( get_post_type( $post ), [ 'post', 'page' ], true ) ) {
			return;
		}

		?>

		<script>

			/**
			 * Toggle the TinyMCE WYSIWYG editor on the fly when a checkbox changes.
			 */
			( function( $ ) {
				// Replace '#sms_markdown' with your checkbox selector.
				$( document ).on( 'change', 'input[name="post_markdown"]', function() {

					// Hide the TinyMCE and Text switcher.
					$( 'button#content-tmce' ).css( 'display', this.checked ? 'none' : 'block' );
					$( 'button#content-html' ).css( 'display', this.checked ? 'none' : 'block' );

					// Switch to Text mode.
					$( 'button#content-html' ).click();

					// $( 'input[name="post_markdown"]' ).prop( 'disabled', true );
				} );
			} )( jQuery );
		</script>

		<?php
	} );
} );

// Save mardown to post_markdown_html (meta).
add_action( 'save_post', function( $post_id, $post ) {

	if (
		wp_is_post_autosave( $post )
		 || wp_is_post_revision( $post )
		 || 1 !== wp_verify_nonce( filter_input( INPUT_POST, 'post_markdown_nonce' ), 'post_markdown_enable' )
		 || true !== current_user_can( 'edit_posts' )
		 || true !== is_admin()
		 || ! in_array( get_post_type( $post ), [ 'post', 'page' ], true )
	) {
		return;
	}

	$option = (

		// The option was turned on for the first time.
		'on' === filter_input( INPUT_POST, 'post_markdown' )

			// This post is already markdown, so it should remain enabled.
			|| ! empty( get_post_meta( $post_id, 'post_markdown_html', true ) )
	)
		? 'enabled' // === 'on'
		: 'disabled'; // not 'on'

	if ( 'disabled' === $option ) {
		return; // Markdown not chosen.
	}

	$parsedown = new \ParseDown();

	update_post_meta( $post->ID ?? 0, 'post_markdown_html', $parsedown->text( $post->post_content ?? '' ) );

}, 10, 2 );

// Disable WYSIWYG on markdown posts.
add_filter( 'user_can_richedit', function( $default ) {

	global $post;

	if ( ! empty( get_post_meta( $post->ID ?? 0, 'post_markdown_html', true ) ) ) {
		return false; // Markdown posts shouldn't be editable via WYSIWYG going forward.
	}

	return $default;
} );

// Show the markdown HTML (frontend) instead of the normal the_content.
add_filter( 'the_content', function( $content ) {

	global $post;

	$html = (string) get_post_meta( $post->ID ?? 0, 'post_markdown_html', true );

	if ( empty( $html ) ) {
		return $content;
	}

	return $html;
} );
