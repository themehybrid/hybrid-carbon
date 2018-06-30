<?php

namespace Hybrid\Carbon\Locate;

class Factory {

	public static function make( $method = 'featured', $args = [] ) {

		// Gets the meta subnamespace with the trailing backslash.
		$namespace = __NAMESPACE__ . '\\Types\\';

		$class = $namespace . str_replace( '_', '', ucwords( $method, '_' ) );

		if ( ! class_exists( $class ) ) {
			$class = $namespace . 'Base';
		}

		$image = new $class( $args );

		return $image->make();
	}
}
