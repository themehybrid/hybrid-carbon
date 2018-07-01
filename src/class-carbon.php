<?php
/**
 * The core project class.
 *
 * Carbon is a highly-intuitive image script that displays post-specific images
 * (an image-based representation of a post). This class pulls everything
 * together and loops through the various methods for finding an image.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon;

use Hybrid\Carbon\Contracts\Image as ImageContract;
use Hybrid\Carbon\Image\Image;
use Hybrid\Carbon\Locate\Factory;

/**
 * Carbon core class.
 *
 * @since  1.0.0
 * @access public
 */
class Carbon {

	/**
	 * The method(s) used to locate an image.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array|string
	 */
	protected $type = [];

	/**
	 * Array of arguments passed in.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $args = [];

	/**
	 * Image object.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Image
	 */
	protected $image = null;

	/**
	 * Creates a new Carbon object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array|string  $type
	 * @param  array         $args
	 * @return void
	 */
	public function __construct( $type = [], $args = [] ) {

		$this->type = $type ? (array) $type : [ 'featured' ];

		$defaults = [
			'post_id'           => get_the_ID(),
			'meta_key'          => [ 'thumbnail', 'Thumbnail' ],
			'size'              => has_image_size( 'post-thumbnail' ) ? 'post-thumbnail' : 'thumbnail',
			'link'              => false,
			'link_class'        => '',
			'attr'              => [],
			'bem_block'         => 'entry',
			'bem_element'       => 'image',
		//	'before'            => '',
		//	'after'             => '',
			'min_width'         => 0,
			'min_height'        => 0,
		//	'caption'           => false,
		//	'meta_key_save'     => false,
		//	'thumbnail_id_save' => false,
		//	'cache'             => true
		];

		$this->args = wp_parse_args( $args, $defaults );

		// Compatibility with the core WP `post_thumbnail_size` hook.
		$this->args['size'] = apply_filters( 'post_thumbnail_size', $this->args['size'] );

		$this->build();
	}

	/**
	 * Returns the image object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return Image
	 */
	public function image() {

		return $this->image;
	}

	/**
	 * Builds the image object.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function build() {

		foreach ( $this->type as $type ) {

			$image = Factory::make(
				$type,
				apply_filters( "hybrid/carbon/{$type}/args", $this->args )
			);

			if ( $image instanceof ImageContract ) {

				$this->image = $image;
				return;
			}
		}

		$this->image = new Image();
	}
}
