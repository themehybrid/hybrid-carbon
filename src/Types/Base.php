<?php
/**
 * Base location type class.
 *
 * This class should be sub-classed. Sub-classes should use the available
 * arguments passed in to attempt to locate an image.
 *
 * @package   HybridCarbon
 * @link      https://github.com/themehybrid/hybrid-carbon
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2023, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Types;

use Hybrid\Carbon\Contracts\Image;
use Hybrid\Carbon\Contracts\ImageGrabber;
use Hybrid\Carbon\Contracts\Type;
use Hybrid\Carbon\Util\Helpers;

/**
 * Base location class.
 *
 * @since  1.0.0
 *
 * @access public
 */
abstract class Base implements Type {

    /**
     * ImageGrabber instance.
     *
     * @since  1.0.0
     * @var \Hybrid\Carbon\Contracts\ImageGrabber
     *
     * @access protected
     */
    protected $manager;

    /**
     * Creates a new location type object.
     *
     * @since  1.0.0
     * @param \Hybrid\Carbon\Contracts\ImageGrabber $manager
     * @return void
     *
     * @access protected
     */
    public function __construct( ImageGrabber $manager ) {

        $this->manager = $manager;
    }

    /**
     * Returns an `Image` object or `false` if no image is found.
     *
     * @since  1.0.0
     * @param  array $args
     * @return \Hybrid\Carbon\Contracts\Image|bool
     *
     * @access protected
     */
    abstract public function make();

    /**
     * Creates a new location type object.
     *
     * @since  1.0.0
     * @param  mixed $image
     * @return bool
     *
     * @access protected
     */
    protected function validate( $image ) {

        if ( ! $image || ! $image instanceof Image ) {
            return false;
        }

        return $this->checkRequirements( $image );
    }

    /**
     * Checks if an image meets any requirements.
     *
     * @since  1.0.0
     * @param \Hybrid\Carbon\Contracts\Image $image
     * @return bool
     *
     * @access protected
     */
    protected function checkRequirements( Image $image ) {

        if ( ! Helpers::hasMinDimension( $image->width(), $this->manager->option( 'min_width' ) ) ) {
            return false;
        }

        if ( ! Helpers::hasMinDimension( $image->height(), $this->manager->option( 'min_height' ) ) ) {
            return false;
        }

        return true;
    }

}
