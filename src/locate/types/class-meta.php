<?php

namespace Hybrid\Carbon\Locate\Types;

use Hybrid\Carbon\Image\Attachment;
use Hybrid\Carbon\Image\Image;
use function Hybrid\Carbon\is_image_attachment;

class Meta extends Base {

	public function make() {

		// Loop through each of the given meta keys and attempt to find
		// an image stored as a meta value.
		foreach ( (array) $this->args['meta_key'] as $meta_key ) {

			$image = '';

			$meta_value = get_post_meta( $this->args['post_id'], $meta_key, true );

			if ( $meta_value && is_numeric( $meta_value ) && is_image_attachment( $meta_value ) ) {

				$image = new Attachment( $meta_value, $this->args );

			} elseif ( $meta_value ) {

				$image = new Image( [
					'src' => $meta_value
				] + $this->args );
			}

			if ( $image && $this->validate( $image ) ) {
				return $image;
			}
		}

		return parent::make();
	}
}
