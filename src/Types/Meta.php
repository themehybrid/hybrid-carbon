<?php
/**
 * Meta location type class.
 *
 * Checks if meta values of the given meta keys are image attachment IDs.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Types;

use Hybrid\Carbon\Image\Attachment;
use Hybrid\Carbon\Util\Helpers;

/**
 * Meta location class.
 *
 * @since  1.0.0
 * @access public
 */
class Meta extends Base {

	/**
	 * Returns an `Image` object or `false` if no image is found.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  array      $args
	 * @return Image|bool
	 */
	public function make() {

		// Loop through each of the given meta keys and attempt to find
		// an image stored as a meta value.
		foreach ( (array) $this->manager->option( 'meta_key' ) as $meta_key ) {

			$meta_value = get_post_meta( $this->manager->option( 'post_id' ), $meta_key, true );

			if ( $meta_value && is_numeric( $meta_value ) && Helpers::isImageAttachment( $meta_value ) ) {

				$image = new Attachment( $this->manager, [
					'attachment_id' => $meta_value
				] );

				if ( $image && $this->validate( $image ) ) {
					return $image;
				}
			}
		}

		return false;
	}
}
