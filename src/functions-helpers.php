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
 * Creates and returns a new `Carbon` object.
 *
 * @since  1.0.0
 * @access public
 * @param  array|string  $type
 * @param  array         $args
 * @return Carbon
 */
function carbon( $type, $args = [] ) {

	return new Carbon( $type, $args );
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
function image( $type, $args = [] ) {

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
function render( $type, $args = [] ) {

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
function fetch( $type, $args = [] ) {

	$image = image( $type, $args );

	return $image ? $image->fetch() : '';
}

/**
 * Checks if a post is an image attachment.
 *
 * @since  1.0.0
 * @access public
 * @param  int    $post_id
 * @return bool
 */
function is_image_attachment( $post_id ) {

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
function has_min_dimension( $dimension, $required ) {

	if ( 0 < $required && $dimension && $dimension < $required ) {
		return false;
	}

	return true;
}

/**
 * Creates a BEM-style HTML class.
 *
 * @since  1.0.0
 * @access public
 * @param  string       $block
 * @param  string       $element
 * @param  string|array $mod
 * @return string
 */
function bem( $block, $element = '', $mod = '' ) {

	$bem = $block;

	if ( $element ) {
		$bem .= "__{$element}";
	}

	if ( $mod ) {
		$bem .= "--{$mod}";
	}

	return $bem;
}
