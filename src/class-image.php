<?php

namespace Hybrid\Carbon;

class Image {

	protected $image;

	protected $methods = [];

	public function __construct( $args = [] ) {

		$defaults = [
			'post_id'           => get_the_ID(),
			'meta_key'          => false,
			'featured'          => true,
			'attachment'        => false,
			'scan'              => false,
			'fallback'          => false,
			'size'              => has_image_size( 'post-thumbnail' ) ? 'post-thumbnail' : 'thumbnail',
			'srcset_sizes'      => [],
			'link'              => 'post',
			'link_class'        => '',
			'image_class'       => false,
			'image_attr'        => [],
			'before'            => '',
			'after'             => '',
			'min_width'         => 0,
			'min_height'        => 0,
			'caption'           => false,
			'meta_key_save'     => false,
			'thumbnail_id_save' => false,
			'cache'             => true
		];

		$this->args = wp_parse_args( $args, $defaults );

		// Compatibility with the core WP `post_thumbnail_size` hook.
		$this->args['size'] = apply_filters( 'post_thumbnail_size', $this->args['size'] );

		// Create an array of methods to check based on what arguments
		// were input.
		array_map( function( $method ) {

			if ( $this->args[ $method ] ) {

				$this->methods[] = $method;
			}
		}, [
			'meta_key',
			'featured',
			'attachment',
			'scan',
			'fallback'
		] );

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

		foreach ( $this->methods as $method ) {

			$image = Factory::make( $method, $this->args );

			if ( $image && $image->hasImage() ) {
				$this->image = $image;
				return;
			}
		}

		$this->image = null;
	}
}
