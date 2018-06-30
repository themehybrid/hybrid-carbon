<?php

namespace Hybrid\Carbon\Locate\Types;

use Hybrid\Carbon\Image\Attachment;
use function Hybrid\Carbon\is_image_attachment;

class Attached extends Base {

	/**
	 * Gets the first image attached to the post.  If the post itself is an attachment image, that will
	 * be the image used.  This method also works with sub-attachments (images for audio/video attachments
	 * are a good example).
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function make() {

		$image         = '';
		$attachment_id = 0;

		if ( is_image_attachment( $this->args['post_id'] ) ) {

			$attachment_id = $this->args['post_id'];
		} else {

			$attachments = get_children( [
				'numberposts'    => 1,
				'post_parent'    => $this->args['post_id'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => 'ASC',
				'orderby'        => 'menu_order ID',
				'fields'         => 'ids'
			] );

			// Check if any attachments were found.
			if ( $attachments ) {
				$attachment_id = array_shift( $attachments );
			}
		}

		if ( 0 < $attachment_id && is_image_attachment( $attachment_id ) ) {

			$image = new Attachment( $attachment_id, $this->args );
		}

		return $this->validate( $image ) ? $image : parent::make();
	}
}
