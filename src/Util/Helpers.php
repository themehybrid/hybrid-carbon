<?php
/**
 * Helpers class.
 *
 * A static class with helper functions for performing some actions needed in
 * the library.
 *
 * @package   HybridCarbon
 * @link      https://github.com/themehybrid/hybrid-carbon
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2023, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Util;

/**
 * Helpers class.
 *
 * @since  1.0.0
 *
 * @access public
 */
class Helpers {

    /**
     * Checks if a post is an image attachment.
     *
     * @since  1.0.0
     * @param  int $post_id
     * @return bool
     *
     * @access public
     */
    public static function isImageAttachment( $post_id ) {

        return 'attachment' === get_post_type( $post_id ) && wp_attachment_is_image( $post_id );
    }

    /**
     * Checks if a dimension (e.g., width, height) meets the minimum requirement.
     *
     * @since  1.0.0
     * @param  int $dimension
     * @param  int $required
     * @return bool
     *
     * @access public
     */
    public static function hasMinDimension( $dimension, $required ) {

        return 0 >= $required || ! $dimension || $dimension >= $required;
    }

    /**
     * Returns the first HTML class name if the class is separated by spaces.
     * Otherwise, the original string will be returned.
     *
     * @since  1.0.0
     * @param  string $class
     * @return string
     *
     * @access public
     */
    public static function classBase( $class ) {

        $base_class = explode( ' ', $class );

        return array_shift( $base_class );
    }

}
