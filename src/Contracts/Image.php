<?php
/**
 * Image interface.
 *
 * Defines the interface that image classes must use.
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
 * Image interface.
 *
 * @since  1.0.0
 *
 * @access public
 */
interface Image {

    /**
     * Renders the image HTML output.
     *
     * @since  1.0.0
     * @return void
     *
     * @access public
     */
    public function display();

    /**
     * Returns the image HTML output.
     *
     * @since  1.0.0
     * @return string
     *
     * @access public
     */
    public function render();

    /**
     * Returns the image source value.
     *
     * @since  1.0.0
     * @return string
     *
     * @access public
     */
    public function src();

    /**
     * Returns the image width value.
     *
     * @since  1.0.0
     * @return int
     *
     * @access public
     */
    public function width();

    /**
     * Returns the image height value.
     *
     * @since  1.0.0
     * @return int
     *
     * @access public
     */
    public function height();

    /**
     * Returns the image alt value.
     *
     * @since  1.0.0
     * @return string
     *
     * @access public
     */
    public function alt();

    /**
     * Returns the image caption.
     *
     * @since  1.0.0
     * @return string
     *
     * @access public
     */
    public function caption();

}
