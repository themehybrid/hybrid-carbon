<?php
/**
 * Type interface.
 *
 * Defines the interface for types (methods to search for images).
 *
 * @package   HybridCarbon
 * @link      https://github.com/themehybrid/hybrid-carbon
 *
 * @author    Theme Hybrid
 * @copyright Copyright (c) 2008 - 2023, Theme Hybrid
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Contracts;

/**
 * Type interface.
 *
 * @since  1.0.0
 * @access public
 */
interface Type {

	/**
	 * Must return an `Image` object or `false` if no image was found.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return Image|bool
	 */
	public function make();
}
