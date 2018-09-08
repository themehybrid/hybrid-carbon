<?php
/**
 * Helpers class.
 *
 * A static class with helper functions for performing some actions needed in
 * the library.
 *
 * @package   HybridBreadcrumbs
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Util;

/**
 * Helpers class.
 *
 * @since  1.0.0
 * @access public
 */
class Helpers {

	/**
	 * Checks if a post is an image attachment.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int    $post_id
	 * @return bool
	 */
	public static function isImageAttachment( $post_id ) {

		return 'attachment' === get_post_type( $post_id ) && wp_attachment_is_image( $post_id );
	}

	/**
	 * Checks if a dimension (e.g., width, height) meets the minimum requirement.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int    $dimension
	 * @param  int    $required
	 * @return bool
	 */
	public static function hasMinDimension( $dimension, $required ) {

		if ( 0 < $required && $dimension && $dimension < $required ) {
			return false;
		}

		return true;
	}

	/**
	 * Returns the first HTML class name if the class is separated by spaces.
	 * Otherwise, the original string will be returned.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $class
	 * @return string
	 */
	public static function classBase( $class ) {

		$base_class = explode( ' ', $class );

		return array_shift( $base_class );
	}
}
