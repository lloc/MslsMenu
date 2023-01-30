<?php

namespace lloc\MslsMenuTests;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use Brain\Monkey\Functions;

class MslsMenu extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();

		Functions\expect( 'load_plugin_textdomain' )->atLeast()->once();
		Functions\when( 'plugin_basename' )->justReturn( 'abc' );
	}

	public function test_init(): void {
		$options = \Mockery::mock( 'lloc\Msls\MslsOptions' );
		$obj     = \MslsMenu::init( $options );

		$this->assertInstanceOf( \MslsMenu::class, $obj );
		$this->assertSame( 10, has_filter('wp_nav_menu_items', [ \MslsMenu::class, 'nav_item' ] ) );
		$this->assertSame( 10, has_action('msls_admin_register', [ \MslsMenu::class, 'admin_register' ] ) );
	}

	public function test_init_null(): void {
		$options = \Mockery::mock( 'lloc\Msls\MslsOptions' );
		$obj     = \MslsMenu::init( null );

		$this->assertInstanceOf( \MslsMenu::class, $obj );
		$this->assertFalse( has_filter('wp_nav_menu_items', [ \MslsMenu::class, 'nav_item' ] ) );
		$this->assertFalse( has_action('msls_admin_register', [ \MslsMenu::class, 'admin_register' ] ) );
	}

	public function test_admin_register_null() {
		Functions\expect( '__' )->once();
		Functions\expect( 'add_settings_section' )->once();

		$obj = new \MslsMenu( null );

		$obj->admin_register( 'test' );
		$this->expectOutputString( '' );
	}

	public function test_nav_item_null() {
		$obj = new \MslsMenu( null );

		$this->assertEquals( '', $obj->nav_item( '', (object)[] ) );
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

}
