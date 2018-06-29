<?php

namespace Hybrid\Carbon;

function carbon( $type, $args = [] ) {

	return new Carbon( $type, $args );
}

function render_image( $type, $args = [] ) {

	carbon( $type, $args )->render();
}

function fetch_image( $type, $args = [] ) {

	return carbon( $type, $args )->fetch();
}

function is_image_attachment( $post_id ) {

	return 'attachment' === get_post_type( $post_id ) && wp_attachment_is_image( $post_id );
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
