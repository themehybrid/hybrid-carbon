<?php

namespace Hybrid\Carbon\Methods;

class Featured extends Base {

	public function make() {

		// Check for a post image ID (set by WP as a custom field).
		$post_thumbnail_id = get_post_thumbnail_id( $this->args['post_id'] );

		// If no post image ID is found, return.
		if ( ! $post_thumbnail_id ) {
			return;
		}

		// Set the image args.
		$this->attachment( $post_thumbnail_id );

		// Add the post thumbnail ID.
		if ( $this->hasImage() ) {
			$this->post_thumbnail_id = $post_thumbnail_id;
		}
	}
}
