<?php

# Autoloads our custom functions files that are not loaded via the class loader.
array_map(
	function( $file ) {
		require_once( trailingslashit( __DIR__ ) . "{$file}.php" );
	},
	[
		'functions-helpers'
	]
);
