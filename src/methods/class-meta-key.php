<?php

namespace Hybrid\Carbon\Methods;

class MetaKey extends Base {

	public function make() {

		// Loop through each of the given meta keys and attempt to find
		// an image stored as a meta value.
		foreach ( (array) $this->args['meta_key'] as $meta_key ) {

			$image = get_post_meta( $this->args['post_id'], $meta_key, true );

			if ( $image && is_numeric( $image ) ) {

				$this->attachment( $image );

				if ( $this->attr ) {
					break;
				}
			} elseif ( $image ) {

				$this->attr['src'] = $image;
			}
		}
	}
}
