<?php
/**
 * Initializes the wp-media/phpunit handler, which then calls the rocket unit test suite.
 */

define( 'WPMEDIA_PHPUNIT_ADDON_ROOT_TEST_DIR', __DIR__ );

require_once WPMEDIA_PHPUNIT_ROOT_DIR . 'vendor/wp-media/phpunit/Unit/bootstrap.php';
require_once WPMEDIA_PHPUNIT_ROOT_DIR . 'vendor/wp-media/phpunit-wp-rocket/Unit/bootstrap.php';

define( 'WPMEDIA_IS_TESTING', true ); // Used by wp-media/{package}.
