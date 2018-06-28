<?php

namespace Hybrid\Carbon;

function image( $args = [] ) {

	return new Image( $args );
}

function render_image( $args = [] ) {

	image( $args )->render();
}

function fetch_image( $args = [] ) {

	return image( $args )->fetch();
}

/**
 * Sanitizes the image class.
 *
 * @since  1.0.0
 * @access public
 * @param  array   $classes
 * @return array
 */
function sanitize_class( $classes ) {

	$classes = array_map( 'strtolower',          $classes );
	$classes = array_map( 'sanitize_html_class', $classes );

	return array_unique( $classes );
}

/**
 * Checks if the image meets the minimum size requirements.
 *
 * @since  1.1.0
 * @access public
 * @param  int|bool  $width
 * @param  int|bool  $height
 * @return bool
 */
function has_required_dimensions( $dimensions = [], $required = [] ) {

	if ( ! has_min_dimension( $dimensions['width'], $required['min_width'] ) ) {
		return false;
	}

	if ( ! has_min_dimension( $dimensions['height'], $required['min_height'] ) ) {
		return false;
	}

	return true;
}

function has_min_dimension( $dimension, $required ) {

	if ( 0 < $required && $dimension && $dimension < $required ) {
		return false;
	}

	return true;
}
