<?php
/**
 * Featured location type class.
 *
 * Searches for and returns a featured image if the post has one.
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
 * Featured location class.
 *
 * @since  1.0.0
 * @access public
 */
class Featured extends Base {

	/**
	 * Returns an `Image` object or `false` if no image is found.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  array      $args
	 * @return Image|bool
	 */
	public function make() {

		$image = '';

		// Check for a post image ID (set by WP as a custom field).
		$attachment_id = get_post_thumbnail_id( $this->manager->option( 'post_id' ) );

		if ( 0 < $attachment_id && Helpers::isImageAttachment( $attachment_id ) ) {

			$image = new Attachment( $this->manager, [
				'attachment_id' => $attachment_id
			] );
		}

		return $this->validate( $image ) ? $image : false;
	}
}
