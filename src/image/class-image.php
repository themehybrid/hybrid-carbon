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

	protected $link = 'post';
	protected $link_class = '';

	public function __construct( $args = [] ) {

		foreach ( array_keys( get_object_vars( $this ) ) as $key ) {

			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}
	}

	public function render() {

		echo $this->fetch();
	}

	public function fetch() {

		if ( ! $this->html && $this->src() ) {

			$hwstring = trim( image_hwstring( $this->width(), $this->height() ) );

			$attr = [
				'src'   => $this->src(),
				'class' => "entry__image",
				'alt'   => $this->alt()
			];

			$esc_attr = '';

			foreach ( $attr as $name => $value ) {

				$esc_attr .= false !== $value
				         ? sprintf( ' %s="%s"', esc_html( $name ), esc_attr( $value ) )
					 : esc_html( " {$name}" );
			}

			$this->html = sprintf(
				'<img %s %s />',
				$hwstring,
				$esc_attr
			);
		}

		return $this->html;
	}

	public function src() {

		return $this->src;
	}

	public function width() {

		return $this->width;
	}

	public function height() {

		return $this->height;
	}

	public function alt() {

		return '';
	}

	public function caption() {

		return '';
	}

	protected function addLink( $html ) {

		if ( false !== $this->link ) {

			$url = '';

			if ( 'post' === $this->link || true === $this->link ) {

				$url = get_permalink( $this->post_id );

			} elseif ( 'file' === $this->link ) {

				$url = $this->src();

			} elseif ( 'attachment' === $this->link && isset( $this->attachment_id ) ) {

				$url = get_permalink( $this->attachment_id );
			}

			if ( $url ) {
				$class = $this->link_class ?: 'entry__image-link';

				$html = sprintf(
					'<a href="%s" class="%s">%s</a>',
					esc_url( $url ),
					esc_attr( $class ),
					$html
				);
			}
		}

		return $html;
	}
}
