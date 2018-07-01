<?php
/**
 * Attachment image class.
 *
 * This is the image object (when loaded as an attachment) that's returned to
 * the main plugin class and back to any functions used.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Image;

/**
 * Attachment image class.
 *
 * @since  1.0.0
 * @access public
 */
class Attachment extends Image {

	/**
	 * ID of the attachment.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    int
	 */
	protected $attachment_id = 0;

	/**
	 * Size of the image to get.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $size = 'thumbnail';

	/**
	 * Creates a new Image Attachment object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int    $attachment_id
	 * @param  array  $args
	 * @return void
	 */
	public function __construct( $attachment_id, $args = [] ) {

		parent::__construct( $args );

		$this->attachment_id = $attachment_id;

		$image = wp_get_attachment_image_src( $this->attachment_id, $this->size );

		if ( $image ) {
			$this->src     = $image[0];
			$this->width   = $image[1];
			$this->height  = $image[2];
		}
	}

	/**
	 * Returns the image HTML output. We're using `wp_get_attachment_image()`
	 * here, which will handle all the hard work and handle the srcset and
	 * sizes attributes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function fetch() {

		if ( ! $this->html ) {

			$attr = $this->attr();

			// Core WP will use `image_hwstring()` to handle this.
			unset( $attr['width'], $attr['height'] );

			$this->html = wp_get_attachment_image(
				$this->attachment_id,
				$this->size,
				false,
				$attr
			);

			$this->html = $this->addLink( $this->html );
		}

		return $this->html;
	}

	/**
	 * Returns the image element class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array
	 */
	protected function class() {

		$class = parent::class();

		$class[] = $this->bem( "size-{$this->size}" );

		return $class;
	}

	/**
	 * Returns the image alt value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function alt() {

		$alt = get_post_meta( $this->attachment_id, '_wp_attachment_image_alt', true );

		return trim( strip_tags( $alt ) );
	}

	/**
	 * Returns the image caption.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function caption() {

		return wp_get_attachment_caption( $this->attachment_id );
	}
}
