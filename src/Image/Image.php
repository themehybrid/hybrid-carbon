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
use Hybrid\Carbon\Contracts\ImageGrabber;
use Hybrid\Carbon\Util\Helpers;

/**
 * Image class.
 *
 * @since  1.0.0
 * @access public
 */
class Image implements ImageContract {

	/**
	 * ImageGrabber instance.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    ImageGrabber
	 */
	protected $manager;

	/**
	 * Image src.
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
	protected $width = 0;

	/**
	 * Image height.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    int
	 */
	protected $height = 0;

	/**
	 * Image alternative text.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $alt = '';

	/**
	 * Image caption.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $caption = '';

	/**
	 * Creates a new Image object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  ImageGrabber  $manager
	 * @param  array         $args
	 * @return void
	 */
	public function __construct( ImageGrabber $manager, array $args = [] ) {

		foreach ( array_keys( get_object_vars( $this ) ) as $key ) {

			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		$this->manager = $manager;
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

		$html = '';

		if ( $attr = $this->attr() ) {

			foreach ( $attr as $name => $value ) {

				if ( 'src' === $name ) {

					$html .= sprintf( ' %s="%s"', esc_html( $name ), esc_url( $value ) );

				} elseif ( false !== $value ) {

					$html .= sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) );
				}
			}

			$html = sprintf( '<img%s />', $html );
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
	 * Returns the image attributes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	protected function attr() {

		$attr = [];

		if ( ! $this->src() ) {
			return $attr;
		}

		$attr['src']   = $this->src();
		$attr['class'] = join( ' ', $this->class() );
		$attr['alt']   = $this->alt();

		if ( $this->width() ) {
			$attr['width'] = $this->width();
		}

		if ( $this->height() ) {
			$attr['height'] = $this->height();
		}

		return $this->manager->option( 'attr' ) + $attr;
	}

	/**
	 * Returns the image element class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array
	 */
	protected function class() {

		$class = [
			$this->manager->option( 'class' )
		];

		if ( $o = $this->orientation() ) {

			$class[] = sprintf(
				'%s--orientation-%s',
				Helpers::classBase( $this->manager->option( 'class' ) ),
				$o
			);
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

			if ( $w === $h ) {
				return 'square';
			}

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

		return $this->manager->option( 'before' ) . $html . $this->manager->option( 'after' );
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

		if ( 'post' === $this->manager->option( 'link' ) || true === $this->manager->option( 'link' ) ) {

			$html = sprintf(
				'<a href="%s" class="%s">%s</a>',
				esc_url( get_permalink( $this->manager->option( 'post_id' ) ) ),
				esc_attr( $this->manager->option( 'link_class' ) ),
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

		if ( $this->manager->option( 'caption' ) && $caption = $this->caption() ) {

			$html = img_caption_shortcode( [
				'caption' => $caption,
				'width'   => $this->width()
			], $html );
		}

		return $html;
	}
}
