<?php
/**
 * Image grabber utility class.
 *
 * This is a static utility class with helper methods for grabbing an image.
 *
 * @package   HybridBreadcrumbs
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Util;

use Hybrid\Carbon\Contracts\Image;
use Hybrid\Carbon\Core\Carbon;

/**
 * Image grabber class.
 *
 * @since  1.0.0
 * @access public
 */
class Grabber {

	/**
	 * Creates and returns a new `Carbon` object and runs its `make()` method.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array|string  $type
	 * @param  array         $args
	 * @return Carbon
	 */
	public static function make( $type, array $args = [] ) {

		return ( new Carbon( $type, $args ) )->make();
	}

	/**
	 * Creates and returns a new `Image` object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array|string  $type
	 * @param  array         $args
	 * @return Image|bool
	 */
	public static function image( $type, array $args = [] ) {

		$image = static::make( $type, $args )->image();

		return $image instanceof Image ? $image : false;
	}

	/**
	 * Outputs the image HTML.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array|string  $type
	 * @param  array         $args
	 * @return void
	 */
	public static function display( $type, array $args = [] ) {

		$image = static::image( $type, $args );

		if ( $image ) {
			$image->display();
		}
	}

	/**
	 * Returns the image HTML.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array|string  $type
	 * @param  array         $args
	 * @return string
	 */
	public static function fetch( $type, array $args = [] ) {

		$image = static::image( $type, $args );

		return $image ? $image->fetch() : '';
	}
}
