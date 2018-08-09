<?php
/**
 * Helper functions.
 *
 * Quick helper functions that make using the plugin easy.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon;

use Hybrid\Carbon\Util\Grabber;

/**
 * Creates and returns a new `Carbon` object and runs its `make()` method.
 *
 * @since  1.0.0
 * @access public
 * @param  array|string  $type
 * @param  array         $args
 * @return \Hybrid\Carbon\Contracts\ImageGrabber
 */
function make( $type, array $args = [] ) {

	return Grabber::make( $type, $args );
}

/**
 * Creates and returns a new `Image` object.
 *
 * @since  1.0.0
 * @access public
 * @param  array|string  $type
 * @param  array         $args
 * @return \Hybrid\Carbon\Contracts\Image|bool
 */
function image( $type, array $args = [] ) {

	return Grabber::image( $type, $args );
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
function display( $type, array $args = [] ) {

	Grabber::display( $type, $args );
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
function fetch( $type, array $args = [] ) {

	return Grabber::fetch( $type, $args );
}
