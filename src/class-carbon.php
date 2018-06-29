<?php

namespace Hybrid\Carbon;

use Hybrid\Carbon\Image\Attachment;
use Hybrid\Carbon\Locate\Factory;

class Carbon {

	/**
	 * @var   $type   string|array
	 */
	protected $type = [];

	protected $allowed_types = [
		'featured',
		'meta',
		'scan',
		'attachment'
	];

	protected $image;

	protected $methods = [];

	public function __construct( $type = [], $args = [] ) {

		foreach ( (array) $type as $index => $t ) {

			if ( ! in_array( $t, $this->allowed_types ) ) {
				unset( $type[ $index ] );
			}
		}

		$this->type = $type ?: 'featured';

		$defaults = [
			'post_id'           => get_the_ID(),
			'meta_key'          => false,
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

	public function render() {

		echo $this->fetch();
	}

	public function fetch() {

		return is_object( $this->image ) ? $this->image->fetch() : '';
	}

	public function attr() {

		return is_object( $this->image ) ? $this->image->attr() : [];
	}

	public function build() {

		foreach ( $this->type as $method ) {

			$image = Factory::make( $method, $this->args );

			if ( is_object( $image ) && $image instanceof Attachment ) {

				$this->image = $image;
				return;
			}

			/*
			if ( $image && $image->hasImage() ) {
				$this->image = $image;
				return;
			}
			*/
		}

		$this->image = null;
	}
}
