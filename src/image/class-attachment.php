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

	protected $post_id = 0;
	protected $size = 'thumbnail';

	public function __construct( $attachment_id, $args = [] ) {

		parent::__construct( $args );

		$this->attachment_id = $attachment_id;

		$this->data();
	}

	public function render() {

		echo $this->fetch();
	}

	public function fetch() {

		if ( ! $this->html ) {

			$attr = [
				'class' => "entry__image entry__image--{$this->size}"
			];

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

	public function caption() {

		return wp_get_attachment_caption( $this->attachment_id );
	}

	public function alt() {

		return trim( strip_tags( get_post_meta( $this->attachment_id, '_wp_attachment_image_alt', true ) ) );
	}

	private function data() {

		$image = wp_get_attachment_image_src( $this->attachment_id, $this->size );

		if ( $image ) {
			$this->src     = $image[0];
			$this->width   = $image[1];
			$this->height  = $image[2];
		}
	}
}
