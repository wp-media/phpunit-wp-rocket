<?php

namespace WP_Rocket\Tests\Integration;

use WC_Install;
use WPMedia\PHPUnit\BootstrapManager;
use function Patchwork\redefine;

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

tests_add_filter(
	'muplugins_loaded',
	function () {
		if ( BootstrapManager::isGroup( 'WithSCCSS' ) ) {
			// Load Simple Custom CSS plugin.
			require PHPUNIT_WP_ROCKET_ROOT_DIR . '/vendor/wpackagist-plugin/simple-custom-css/simple-custom-css.php';
			update_option(
				'sccss_settings',
				[
					'sccss-content' => '.simple-custom-css { color: red; }',
				]
			);
		}

		if ( BootstrapManager::isGroup( 'WithAmp' ) ) {
			// Load AMP plugin.
			require PHPUNIT_WP_ROCKET_ROOT_DIR . '/vendor/wpackagist-plugin/amp/amp.php';
		}

		if ( BootstrapManager::isGroup( 'WithAmpAndCloudflare' ) ) {
			// Load AMP plugin.
			require PHPUNIT_WP_ROCKET_ROOT_DIR . '/vendor/wpackagist-plugin/amp/amp.php';
			update_option(
				'wp_rocket_settings',
				[
					'do_cloudflare'               => 1,
					'cloudflare_protocol_rewrite' => 1,
				]
			);
		}

		// Set the path and URL to our virtual filesystem.
		define( 'WP_ROCKET_CACHE_ROOT_PATH', 'vfs://public/wp-content/cache/' );
		define( 'WP_ROCKET_CACHE_ROOT_URL', 'http://example.org/wp-content/cache/' );

		if ( BootstrapManager::isGroup( 'WithSmush' ) ) {
			// Load WP Smush.
			require PHPUNIT_WP_ROCKET_ROOT_DIR . '/vendor/wpackagist-plugin/wp-smushit/wp-smush.php';
		}

		if ( BootstrapManager::isGroup( 'WithWoo' ) ) {
			// Load WooCommerce.
			define( 'WC_TAX_ROUNDING_MODE', 'auto' );
			define( 'WC_USE_TRANSACTIONS', false );
			require PHPUNIT_WP_ROCKET_ROOT_DIR . '/vendor/woocommerce/woocommerce/woocommerce.php';
		}

		if ( BootstrapManager::isGroup( 'BeaverBuilder' ) ) {
			define( 'FL_BUILDER_VERSION', '5.3' );
		}

		if ( BootstrapManager::isGroup( 'Elementor' ) ) {
			define( 'ELEMENTOR_VERSION', '2.0' );
		}

		if ( BootstrapManager::isGroup( 'Hummingbird' ) ) {
			define( 'WP_ADMIN', true );
			require PHPUNIT_WP_ROCKET_ROOT_DIR . '/vendor/wpackagist-plugin/hummingbird-performance/wp-hummingbird.php';
		}

		if ( BootstrapManager::isGroup( 'Cloudways' ) ) {
			$_SERVER['cw_allowed_ip'] = true;
		}

		// Overload the license key for testing.
		redefine( 'rocket_valid_key', '__return_true' );

		if ( BootstrapManager::isGroup( 'DoCloudflare' ) ) {
			update_option( 'wp_rocket_settings', [ 'do_cloudflare' => 1 ] );
		}

		// Load the plugin: Add the code into Rocket's bootstrap file at priority of 10.
	},
	9
);

// install WC.
tests_add_filter(
	'setup_theme',
	function () {
		if ( ! BootstrapManager::isGroup( 'WithWoo' ) ) {
			return;
		}
		// Clean existing install first.
		define( 'WP_UNINSTALL_PLUGIN', true );
		define( 'WC_REMOVE_ALL_DATA', true );
		include PHPUNIT_WP_ROCKET_ROOT_DIR . '/vendor/woocommerce/woocommerce/uninstall.php';

		WC_Install::install();

		// Reload capabilities after install, see https://core.trac.wordpress.org/ticket/28374.
		if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
			$GLOBALS['wp_roles']->reinit();
		} else {
			$GLOBALS['wp_roles'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			wp_roles();
		}

		echo esc_html( 'Installing WooCommerce...' . PHP_EOL );
	}
);
