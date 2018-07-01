<?php
/**
 * Locate factory class.
 *
 * This is a simple static factory class for quickly create new `Locate\*`
 * objects.  It returns the result of the object.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Locate;

/**
 * Locate factory class.
 *
 * @since  1.0.0
 * @access public
 */
class Factory {

	/**
	 * Creates a new `Hybrid\Carbon\Locate\Types\*` object and returns the
	 * result of its `make()` method.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @param  array   $args
	 * @return object|bool
	 */
	public static function make( $type = 'featured', $args = [] ) {

		// Gets the meta subnamespace with the trailing backslash.
		$namespace = __NAMESPACE__ . '\\Types\\';

		$class = $namespace . str_replace( '_', '', ucwords( $type, '_' ) );

		if ( ! class_exists( $class ) ) {
			$class = $namespace . 'Base';
		}

		$image = new $class( $args );

		return $image->make();
	}
}
