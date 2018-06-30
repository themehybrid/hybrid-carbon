<?php

namespace Hybrid\Carbon\Locate\Types;

use Hybrid\Carbon\Image\Attachment;
use function Hybrid\Carbon\is_image_attachment;

class Featured extends Base {

	public function make() {

		$image = '';

		// Check for a post image ID (set by WP as a custom field).
		$attachment_id = get_post_thumbnail_id( $this->args['post_id'] );

		if ( 0 < $attachment_id && is_image_attachment( $attachment_id ) ) {

			$image = new Attachment( $attachment_id, $this->args );
		}

		return $this->validate( $image ) ? $image : parent::make();
	}
}
