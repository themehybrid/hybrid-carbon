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

use Hybrid\Carbon\Contracts\Image;
use Hybrid\Carbon\Core\Carbon;

/**
 * Creates and returns a new `Carbon` object and runs its `make()` method.
 *
 * @since  1.0.0
 * @access public
 * @param  array|string  $type
 * @param  array         $args
 * @return Carbon
 */
function carbon( $type, array $args = [] ) {

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
function image( $type, array $args = [] ) {

	$image = carbon( $type, $args )->image();

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
function render( $type, array $args = [] ) {

	$image = image( $type, $args );

	if ( $image ) {
		$image->render();
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
function fetch( $type, array $args = [] ) {

	$image = image( $type, $args );

	return $image ? $image->fetch() : '';
}
