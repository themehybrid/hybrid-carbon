<?php

namespace Hybrid\Carbon\Locate\Types;

use Hybrid\Carbon\Contracts\Image;
use function Hybrid\Carbon\has_min_dimension;

class Base {

	protected $args = [];

	public function __construct( $args ) {

		$this->args = $args;
	}

	public function make() {
		return null;
	}

	protected function validate( $image ) {

		if ( ! $image || ! $image instanceof Image ) {
			return false;
		}

		return $this->checkRequirements( $image );
	}

	protected function checkRequirements( Image $image ) {

		if ( ! has_min_dimension( $image->width(), $this->args['min_width'] ) ) {
			return false;
		}

		if ( ! has_min_dimension( $image->height(), $this->args['min_height'] ) ) {
			return false;
		}

		return true;
	}
}
