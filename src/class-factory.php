<?php

namespace Hybrid\Carbon;

class Factory {

	public static function make( $method = 'featured', $args = [] ) {

		// Gets the meta subnamespace with the trailing backslash.
		$namespace = __NAMESPACE__ . '\\Methods\\';

		$class = $namespace . str_replace( '_', '', ucwords( $method ) );

		return class_exists( $class ) ? new $class( $args ) : null;
	}
}
