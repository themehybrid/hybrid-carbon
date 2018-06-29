<?php

namespace Hybrid\Carbon\Locate\Types;

use Hybrid\Carbon\Image\Attachment;
use function Hybrid\Carbon\has_min_dimension;
use function Hybrid\Carbon\is_image_attachment;

class Base {

	protected $args = [];

	public function __construct( $args ) {

		$this->args = $args;
	}

	public function make() {
		return null;
	}

	protected function validateAttachment( $attachment_id ) {

		$attachment_id = absint( $attachment_id );

		if ( 0 >= $attachment_id || ! is_image_attachment( $attachment_id ) ) {
			return false;
		}

		$attachment = new Attachment( $attachment_id, $this->args );

		return $this->checkRequirements( $attachment ) ? $attachment : false;
	}

	public function checkRequirements( Attachment $image ) {

		if ( ! has_min_dimension( $image->width(), $this->args['min_width'] ) ) {
			return false;
		}

		if ( ! has_min_dimension( $image->height(), $this->args['min_height'] ) ) {
			return false;
		}

		return true;
	}
}
