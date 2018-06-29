<?php

namespace Hybrid\Carbon\Image;

class Attachment {

	protected $attachment_id = 0;
	protected $args = [];
	protected $html = '';
	protected $data = [];

	public function __construct( $attachment_id, $args = [] ) {

		$this->attachment_id = $attachment_id;
		$this->args = $args;

		$this->data();
	}

	public function render() {

		echo $this->fetch();
	}

	public function fetch() {

		if ( ! $this->html ) {

			$attr = [
				'class' => "entry__image entry__image--{$this->args['size']}"
			];

			$this->html = wp_get_attachment_image(
				$this->attachment_id,
				$this->args['size'],
				false,
				$attr
			);
		}

		return $this->html;
	}

	public function caption() {

		return get_post_field( 'post_excerpt', $this->attachment_id );
	}

	public function alt() {

		return trim( strip_tags( get_post_meta( $this->attachment_id, '_wp_attachment_image_alt', true ) ) );
	}

	public function width() {

		return $this->data['width'];
	}

	public function height() {

		return $this->data['height'];
	}

	public function src() {

		return $this->data['src'];
	}

	private function data() {

		if ( ! $this->data ) {
			$image = wp_get_attachment_image_src( $this->attachment_id, $this->args['size'] );

			if ( $image ) {
				$this->data['src']     = $image[0];
				$this->data['width']   = $image[1];
				$this->data['height']  = $image[2];
			}
		}

		return $this->data;
	}
}
