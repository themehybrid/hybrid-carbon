<?php
/**
 * Breadcrumbs interface.
 *
 * Defines the interface that breadcrumbs classes must use.
 *
 * @package   HybridBreadcrumbs
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-breadcrumbs
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Contracts;

/**
 * Carbon interface.
 *
 * @since  1.0.0
 * @access public
 */
interface Carbon {

	/**
	 * Returns an object implementing the `Image` contract.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return Image
	 */
	public function image();
}
