<?php
/**
 * Base image class.
 *
 * This is the image object that's returned to the main plugin class and back to
 * any functions used.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Image;

use Hybrid\Carbon\Contracts\Image as ImageContract;
use Hybrid\Carbon\Util\Helpers;

/**
 * Image class.
 *
 * @since  1.0.0
 * @access public
 */
class Image implements ImageContract {

	/**
	 * Post ID.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    int
	 */
	protected $post_id = 0;

	/**
	 * Image HTML attributes.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $attr = [];

	/**
	 * BEM-style HTML class "block" name.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $bem_block = 'entry';

	/**
	 * BEM-style HTML class "element" name.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $bem_element = 'image';

	/**
	 * Whether and what to link the image to.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string|bool
	 */
	protected $link = 'post';

	/**
	 * Link class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $link_class = '';

	/**
	 * Whether to add a caption (if one exists) to the image.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    bool
	 */
	protected $caption = false;

	/**
	 * HTML to add before image.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $before = '';

	/**
	 * HTML to add after image.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $after = '';

	/**
	 * Creates a new Image object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $args
	 * @return void
	 */
	public function __construct( array $args = [] ) {

		foreach ( array_keys( get_object_vars( $this ) ) as $key ) {

			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}
	}

	/**
	 * Renders the image HTML output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function display() {

		echo $this->render();
	}

	/**
	 * Returns the image HTML output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function render() {

		return '';
	}

	/**
	 * Returns the image source value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function src() {

		return $this->src;
	}

	/**
	 * Returns the image width value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return int
	 */
	public function width() {

		return $this->width;
	}

	/**
	 * Returns the image height value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return int
	 */
	public function height() {

		return $this->height;
	}

	/**
	 * Returns the image alt value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function alt() {

		return '';
	}

	/**
	 * Returns the image attributes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	protected function attr() {

		return array_merge( [
			'src'   => $this->src(),
			'alt'   => $this->alt(),
			'class' => join( ' ', $this->class() )
		] + $this->attr );
	}

	/**
	 * Returns the image element class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array
	 */
	protected function class() {

		$class = [];

		$class[] = Helpers::bem( $this->bem_block, $this->bem_element );

		if ( $o = $this->orientation() ) {
			$class[] = Helpers::bem( $this->bem_block, $this->bem_element, "orientation-{$o}" );
		}

		return $class;
	}

	/**
	 * Returns the image caption.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function caption() {

		return '';
	}

	/**
	 * Returns the image orientation (`landscape` or `portrait`) if one can
	 * be determined. Else, returns an empty string.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function orientation() {

		$w = $this->width();
		$h = $this->height();

		if ( $w && $h ) {
			return $w > $h ? 'landscape' : 'portrait';
		}

		return '';
	}

	/**
	 * Wraps the image with HTML wrappers.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $html
	 * @return string
	 */
	protected function wrap( $html ) {

		$html = $this->addLink( $html );
		$html = $this->addCaption( $html );

		return $this->before . $html . $this->after;
	}

	/**
	 * Wraps the image HTML with a link.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $html
	 * @return string
	 */
	protected function addLink( $html ) {

		if ( 'post' === $this->link || true === $this->link ) {

			$url = get_permalink( $this->post_id );

			$class = $this->link_class ?: sprintf( '%s-link', Helpers::bem( $this->bem_block, $this->bem_element ) );

			$html = sprintf(
				'<a href="%s" class="%s">%s</a>',
				esc_url( $url ),
				esc_attr( $class ),
				$html
			);
		}

		return $html;
	}

	/**
	 * Wraps the image HTML with a caption.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $html
	 * @return string
	 */
	protected function addCaption( $html ) {

		if ( $this->caption && $caption = $this->caption() ) {

			$html = img_caption_shortcode( [
				'caption' => $caption,
				'width'   => $this->width()
			], $html );
		}

		return $html;
	}
}
