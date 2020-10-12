<?php

namespace lloc\MslsMenuTests;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use Brain\Monkey\Functions;

class MslsMenu extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	public function test_init() {
		Functions\expect( 'load_plugin_textdomain' );
		Functions\expect( 'plugin_basename' );

		$obj = \MslsMenu::init();

		$this->assertInstanceOf( \MslsMenu::class, $obj );
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

}
