<?php

namespace Hybrid\Carbon\Locate\Types;

class Featured extends Base {

	public function make() {

		// Check for a post image ID (set by WP as a custom field).
		$thumb_id = get_post_thumbnail_id( $this->args['post_id'] );

		// If no post image ID is found, return.
		if ( ! $thumb_id ) {
			return;
		}

		$attachment = $this->validateAttachment( $thumb_id );

		return $attachment ?: parent::make();
	}
}
