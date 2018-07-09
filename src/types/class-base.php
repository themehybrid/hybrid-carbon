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
use Hybrid\Carbon\Contracts\Type;
use function Hybrid\Carbon\has_min_dimension;

/**
 * Base location class.
 *
 * @since  1.0.0
 * @access public
 */
abstract class Base implements Type {

	/**
	 * Array of arguments passed in.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $args = [];

	/**
	 * Creates a new location type object.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  array      $args
	 * @return void
	 */
	public function __construct( $args = [] ) {

		$this->args = $args;
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

		if ( ! has_min_dimension( $image->width(), $this->args['min_width'] ) ) {
			return false;
		}

		if ( ! has_min_dimension( $image->height(), $this->args['min_height'] ) ) {
			return false;
		}

		return true;
	}
}
