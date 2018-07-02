<?php
/**
 * Bootstrap the project.
 *
 * Kick-starts everything.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Check if the framework has been bootstrapped. If not, load the bootstrap files
# and get the framework set up.
if ( ! defined( 'HYBRID_CARBON_BOOTSTRAPPED' ) ) {

	# Autoload classes.
	require_once( __DIR__ . '/bootstrap-autoload.php'  );

	# Autoload functions.
	require_once( __DIR__ . '/functions-helpers.php' );

	define( 'HYBRID_CARBON_BOOTSTRAPPED', true );
}
