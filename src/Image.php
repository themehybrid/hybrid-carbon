<?php
/**
 * Image grabber utility class.
 *
 * This is a static utility class with helper methods for grabbing an image.
 *
 * @package   HybridCarbon
 * @link      https://github.com/themehybrid/hybrid-carbon
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2023, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon;

use Hybrid\Carbon\Contracts\Image as ImageContract;

/**
 * Image grabber class.
 *
 * @since  1.0.0
 *
 * @access public
 */
class Image {

    /**
     * Creates and returns a new `Carbon` object.
     *
     * @since  1.0.0
     * @param  array|string $type
     * @param  array        $args
     * @return \Hybrid\Carbon\Carbon
     *
     * @access public
     */
    public static function carbon( $type, array $args = [] ) {

        return new Carbon( $type, $args );
    }

    /**
     * Creates and returns a new `Carbon` object and runs its `make()` method.
     *
     * @since  1.0.0
     * @param  array|string $type
     * @param  array        $args
     * @return \Hybrid\Carbon\Carbon
     *
     * @access public
     */
    public static function make( $type, array $args = [] ) {

        return static::carbon( $type, $args )->make();
    }

    /**
     * Creates and returns a new `ImageContract` object.
     *
     * @since  1.0.0
     * @param  array|string $type
     * @param  array        $args
     * @return \Hybrid\Carbon\Contracts\Image|bool
     *
     * @access public
     */
    public static function image( $type, array $args = [] ) {

        $image = static::make( $type, $args )->image();

        return $image instanceof ImageContract ? $image : false;
    }

    /**
     * Outputs the image HTML.
     *
     * @since  1.0.0
     * @param  array|string $type
     * @param  array        $args
     * @return void
     *
     * @access public
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
     * @param  array|string $type
     * @param  array        $args
     * @return string
     *
     * @access public
     */
    public static function render( $type, array $args = [] ) {

        $image = static::image( $type, $args );

        return $image ? $image->render() : '';
    }

}
