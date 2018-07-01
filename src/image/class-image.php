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

/**
 * Image class.
 *
 * @since  1.0.0
 * @access public
 */
class Image implements ImageContract {

	/**
	 * Image HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $html = '';

	/**
	 * Post ID.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    int
	 */
	protected $post_id = 0;

	/**
	 * Image source URL.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $src = '';

	/**
	 * Image width.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    int
	 */
	protected $width = '';

	/**
	 * Image height.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    int
	 */
	protected $height = '';

	/**
	 * Image alt text.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $alt = '';

	/**
	 * Image caption text.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $caption = '';

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
	 * Whether and what to link the image to. The image can link to `post`,
	 * `file`, or `attachment`. Set to `false` for no link.
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
	 * Creates a new Image object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $args
	 * @return void
	 */
	public function __construct( $args = [] ) {

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
	public function render() {

		echo $this->fetch();
	}

	/**
	 * Returns the image HTML output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function fetch() {

		if ( ! $this->html && $this->src() ) {

			$hwstring = trim( image_hwstring( $this->width(), $this->height() ) );

			$esc_attr = '';

			foreach ( $this->attr() as $name => $value ) {

				$esc_attr .= false !== $value
				         ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) )
					 : esc_html( " {$name}" );
			}

			$this->html = sprintf(
				'<img %s %s />',
				$hwstring,
				$esc_attr
			);

			$this->html = $this->addLink( $this->html );
		}

		return $this->html;
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

		return $this->alt;
	}

	/**
	 * Returns the image caption.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function caption() {

		return $this->caption;
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
	 * Returns the image source value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function attr() {

		return array_merge( [
			'src'   => $this->src(),
			'class' => join( ' ', $this->class() ),
			'alt'   => $this->alt()
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

		$class = [ $this->bem() ];

		if ( $o = $this->orientation() ) {
			$class[] = $this->bem( "orientation-{$o}" );
		}

		return $class;
	}

	/**
	 * Creates a BEM-style HTML class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  string    $mod
	 * @return string
	 */
	protected function bem( $mod = '' ) {

		$bem = $this->bem_block;

		if ( $this->bem_element ) {
			$bem = "{$bem}__{$this->bem_element}";
		}

		if ( $mod ) {
			$bem = "{$bem}--{$mod}";
		}

		return $bem;
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

			$class = $this->link_class ?: sprintf( '%s-link', $this->bem() );

			$html = sprintf(
				'<a href="%s" class="%s">%s</a>',
				esc_url( $url ),
				esc_attr( $class ),
				$html
			);
		}

		return $html;
	}
}
