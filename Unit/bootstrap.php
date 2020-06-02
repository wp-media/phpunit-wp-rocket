<?php

namespace WP_Rocket\Tests\Unit;

/**
 * These constants should be defined in the WP Rocket's bootstrap file:
 *
 * - WP_ROCKET_PLUGIN_ROOT
 * - WP_ROCKET_TESTS_FIXTURES_DIR
 */

define( 'PHPUNIT_WP_ROCKET_FIXTURES_DIR', dirname( __DIR__ ) . '/Fixtures' );

if ( ! defined( 'WP_ROCKET_IS_TESTING' ) ) {
	define( 'WP_ROCKET_IS_TESTING', true );
}

define( 'PHPUNIT_WP_ROCKET_ROOT_DIR', dirname( dirname( dirname( __DIR__ ) ) ) );
define( 'PHPUNIT_WP_ROCKET_TESTS_DIR', __DIR__ );

// Set the path and URL to our virtual filesystem.
define( 'WP_ROCKET_CACHE_ROOT_PATH', 'vfs://public/wp-content/cache/' );
define( 'WP_ROCKET_CACHE_ROOT_URL', 'vfs://public/wp-content/cache/' );

/**
 * The original fixture files need to loaded into memory before we mock them with Patchwork.
 * Add files here before the unit tests start.
 */
function load_original_fixtures_before_mocking() {
	$fixtures = [
		'/WP_Error.php',
		'/WP_Theme.php',
		'/WPDieException.php',
	];
	foreach ( $fixtures as $file ) {
		require_once PHPUNIT_WP_ROCKET_FIXTURES_DIR . $file;
	}
}

load_original_fixtures_before_mocking();
