<?php
/**
 * PHPStan bootstrap.
 *
 * Loads the real Multisite Language Switcher so that the `lloc\Msls\*` symbols
 * MslsMenu calls are analysed against the actual API. The stubs in tests/Pest.php
 * exist to keep the unit tests free of WordPress, but they also hide changes in
 * MSLS - `MslsAdmin::init()` losing its return value in MSLS 3.0 went unnoticed
 * because the stub still returned an object.
 *
 * @package mslsmenu
 */

declare( strict_types=1 );

// phpstan-wordpress may have defined it already; aliases.php bails out without it.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

$msls = dirname( __DIR__ ) . '/vendor/lloc/multisite-language-switcher/includes/aliases.php';

// "composer build" runs "composer update --no-dev", which removes MSLS from vendor/.
// Fail with an instruction instead of a "Failed opening required" fatal.
if ( ! file_exists( $msls ) ) {
	throw new RuntimeException(
		"Multisite Language Switcher is missing from vendor/. Run 'composer install' (a previous 'composer build' stripped the dev dependencies)."
	);
}

require_once $msls;
