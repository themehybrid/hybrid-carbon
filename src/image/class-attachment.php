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

	protected function addLink( $html ) {

		if ( false !== $this->args['link'] ) {

			$url = '';

			if ( 'post' === $this->args['link'] || true === $this->args['link'] ) {

				$url = get_permalink( $this->args['post_id'] );

			} elseif ( 'file' === $this->args['link'] ) {

				$url = $this->src();

			} elseif ( 'attachment' === $this->args['link'] && isset( $this->attachment_id ) ) {

				$url = get_permalink( $this->attachment_id );
			}

			if ( $url ) {
				$class = $this->args['link_class'] ?: 'entry__image-link';

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
