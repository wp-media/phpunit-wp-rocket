<?php

namespace WP_Rocket\Tests\Unit;

use WP_Rocket\Tests\StubTrait;
use WP_Rocket\Tests\VirtualFilesystemTrait;
use WPMedia\PHPUnit\Unit\VirtualFilesystemTestCase;

abstract class FilesystemTestCase extends VirtualFilesystemTestCase {
	use StubTrait;
	use VirtualFilesystemTrait;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		// Clean out the cached dirs before we run these tests.
		_rocket_get_cache_dirs( '', '', true );
	}

	public static function tearDownAfterClass() {
		parent::tearDownAfterClass();

		// Clean out the cached dirs before we leave this test class.
		_rocket_get_cache_dirs( '', '', true );
	}

	public function setUp() {
		$this->initDefaultStructure();

		parent::setUp();

		$this->stubRocketGetConstant();
		$this->stubWpNormalizePath();
		$this->redefineRocketDirectFilesystem();
	}

	protected function tearDown() {
		parent::tearDown();

		unset( $GLOBALS['debug_fs'] );

		// Reset the cached state.
		_rocket_get_cache_dirs( '', '', true );
	}
}
