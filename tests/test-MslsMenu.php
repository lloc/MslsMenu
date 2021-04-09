<?php

namespace lloc\MslsMenuTests;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use Brain\Monkey\Functions;

class MslsMenu extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();

		Functions\expect( 'load_plugin_textdomain' );
		Functions\when( 'plugin_basename' )->justReturn( 'abc' );
	}

	public function test_init() {
		$this->assertInstanceOf( \MslsMenu::class, \MslsMenu::init() );
	}

	public function test_admin_register() {
		Functions\expect( '__' )->once();
		Functions\expect( 'add_settings_section' )->once();

		$obj = new \MslsMenu();

		$obj->admin_register( 'test' );
		$this->expectOutputString( '' );
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

}
