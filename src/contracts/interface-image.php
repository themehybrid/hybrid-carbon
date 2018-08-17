<?php
/**
 * Image interface.
 *
 * Defines the interface that image classes must use.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Contracts;

/**
 * Image interface.
 *
 * @since  1.0.0
 * @access public
 */
interface Image {

	/**
	 * Renders the image HTML output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function display();

	/**
	 * Returns the image HTML output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function render();

	/**
	 * Returns the image source value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function src();

	/**
	 * Returns the image width value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return int
	 */
	public function width();

	/**
	 * Returns the image height value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return int
	 */
	public function height();

	/**
	 * Returns the image alt value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function alt();

	/**
	 * Returns the image caption.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function caption();
}
