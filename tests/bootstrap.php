<?php
/**
 * PHPUnit/Pest bootstrap.
 *
 * MslsMenu.php guards against direct access via ABSPATH, so the constant has to
 * exist before the plugin file is loaded. Loading it here instead of through
 * autoload-dev keeps it out of the phpcs and phpstan processes, which analyse
 * the file rather than execute it.
 *
 * @package mslsmenu
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../MslsMenu.php';
