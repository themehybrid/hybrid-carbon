<?php

namespace Hybrid\Carbon\Locate\Types;

class Fallback extends Base {


	/**
	 * Sets the default image.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function make() {

		if ( $this->args['fallback'] ) {

			$this->attr['src'] = $this->args['fallback'];
		}
	}
}
