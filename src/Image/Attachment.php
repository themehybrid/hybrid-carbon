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

use Hybrid\Carbon\Contracts\ImageGrabber;
use Hybrid\Carbon\Util\Helpers;

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
	 * Creates a new Image Attachment object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  ImageGrabber  $manager
	 * @param  array         $args
	 * @return void
	 */
	public function __construct( ImageGrabber $manager, array $args = [] ) {

		parent::__construct( $manager, $args );

		$data = wp_get_attachment_image_src(
			$this->attachment_id,
			$this->manager->option( 'size' )
		);

		if ( $data ) {
			$this->src    = $data[0];
			$this->width  = $data[1];
			$this->height = $data[2];
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
	public function render() {

		$html = wp_get_attachment_image(
			$this->attachment_id,
			$this->manager->option( 'size' ),
			false,
			$this->attr()
		);

		return $this->wrap( $html );
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
	 * Returns the image attributes. Most of these are going to be handled
	 * automatically via `wp_get_attachment_image()`, so we're not worried
	 * about getting the necessary attributes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	protected function attr() {

		$attr = [
			'class' => join( ' ', $this->class() )
		];

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

		$class = parent::class();

		$class[] = sprintf(
			'%s--size-%s',
			Helpers::classBase( $this->manager->option( 'class' ) ),
			$this->manager->option( 'size' )
		);

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

		return wp_get_attachment_caption( $this->attachment_id );
	}
}
