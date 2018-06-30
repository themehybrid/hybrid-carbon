<?php

namespace Hybrid\Carbon;

use Hybrid\Carbon\Contracts\Image as ImageContract;
use Hybrid\Carbon\Image\Image;
use Hybrid\Carbon\Locate\Factory;

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
	 */
	public function __construct( $type = [], $args = [] ) {

		$this->type = $type ? (array) $type : [ 'featured' ];

		$defaults = [
			'post_id'           => get_the_ID(),
			'meta_key'          => [ 'thumbnail', 'Thumbnail' ],
			'size'              => has_image_size( 'post-thumbnail' ) ? 'post-thumbnail' : 'thumbnail',
			'link'              => 'post',
			'link_class'        => '',
			'image_attr'        => [],
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

		foreach ( $this->type as $method ) {

			$image = Factory::make( $method, $this->args );

			if ( $image instanceof ImageContract ) {

				$this->image = $image;
				return;
			}
		}

		$this->image = new Image();
	}
}
