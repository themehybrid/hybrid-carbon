<?php

namespace Hybrid\Carbon\Methods;

class Scan extends Base {

	/**
	 * Scans the post content for an image.  It first scans and checks for an image with the
	 * "wp-image-xxx" ID.  If that exists, it'll grab the actual image attachment.  If not, it looks
	 * for the image source.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function make() {

		// Get the post content.
		$post_content = get_post_field( 'post_content', $this->args['post_id'] );

		// Apply filters to content.
		$post_content = apply_filters( 'get_the_image_post_content', $post_content );

		// Check the content for `id="wp-image-%d"`.
		preg_match( '/id=[\'"]wp-image-([\d]*)[\'"]/i', $post_content, $image_ids );

		// Loop through any found image IDs.
		if ( is_array( $image_ids ) ) {

			foreach ( $image_ids as $image_id ) {

				$this->attachment( $image_id );

				if ( $this->attr ) {
					return;
				}
			}
		}

		// Search the post's content for the <img /> tag and get its URL.
		preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post_content, $matches );

		// If there is a match for the image, set the image args.
		if ( isset( $matches ) && ! empty( $matches[1][0] ) ) {

			$this->attr['src'] = $matches[1][0];
		}
	}
}
