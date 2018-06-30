<?php

# Bail if we're not in the WP environment.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

# Check if the framework has been bootstrapped. If not, load the bootstrap files
# and get the framework set up.
if ( ! defined( 'HYBRID_CARBON_BOOTSTRAPPED' ) ) {

	require_once( __DIR__ . '/bootstrap-autoload.php'  );
	require_once( __DIR__ . '/bootstrap-functions.php' );

	define( 'HYBRID_CARBON_BOOTSTRAPPED', true );
}
