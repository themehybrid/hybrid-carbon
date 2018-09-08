<?php
/**
 * Base location type class.
 *
 * This class should be sub-classed. Sub-classes should use the available
 * arguments passed in to attempt to locate an image.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
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
 * @access public
 */
abstract class Base implements Type {

	/**
	 * ImageGrabber instance.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    ImageGrabber
	 */
	protected $manager;

	/**
	 * Creates a new location type object.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  ImageGrabber  $manager
	 * @return void
	 */
	public function __construct( ImageGrabber $manager ) {

		$this->manager = $manager;
	}

	/**
	 * Returns an `Image` object or `false` if no image is found.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  array      $args
	 * @return Image|bool
	 */
	abstract public function make();

	/**
	 * Creates a new location type object.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  mixed      $image
	 * @return bool
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
	 * @access protected
	 * @param  Image     $image
	 * @return bool
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
