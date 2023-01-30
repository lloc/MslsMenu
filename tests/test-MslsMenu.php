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
		Functions\stubs( [
			'plugin_basename' => 'abc',
			'__'
		] );
	}

	public function test_init(): void {
		$options = \Mockery::mock( 'lloc\Msls\MslsOptions' );
		$obj     = \MslsMenu::init( $options );

		$this->assertInstanceOf( \MslsMenu::class, $obj );
		$this->assertSame( 10, has_filter('wp_nav_menu_items', [ \MslsMenu::class, 'nav_item' ] ) );
		$this->assertSame( 10, has_action('msls_admin_register', [ \MslsMenu::class, 'admin_register' ] ) );
	}

	public function test_admin_register() {
		Functions\expect( 'add_settings_section' )->once();

		$options = \Mockery::mock( 'lloc\Msls\MslsOptions' );
		$obj     = \MslsMenu::init( $options );

		$obj->admin_register( 'test' );
		$this->expectOutputString( '' );
	}

	public function test_add_settings(): void {
		$options = \Mockery::mock( 'lloc\Msls\MslsOptions' );
		$obj     = \MslsMenu::init( $options );

		Functions\expect( 'add_settings_field' )->times( 6 );

		$obj->add_settings();
		$this->expectOutputString( '' );
	}

	public function test_theme_location() {
		Functions\expect( 'get_nav_menu_locations' )->once()->andReturn( [] );

		$options = \Mockery::mock( 'lloc\Msls\MslsOptions' );
		$obj     = \MslsMenu::init( $options );

		$obj->theme_location( [] );

		$expected = '<select id="mslsmenu_theme_location" name="msls[mslsmenu_theme_location][]" multiple="multiple"><option value="" >-- empty --</option></select>';
		$this->expectOutputString( $expected );
	}

	public function test_display() {
		$options = \Mockery::mock( 'lloc\Msls\MslsOptions' );
		$obj     = \MslsMenu::init( $options );

		$obj->display( [] );
		$this->expectOutputString( '' );
	}

	public function test_input() {
		$options = \Mockery::mock( 'lloc\Msls\MslsOptions' );
		$obj     = \MslsMenu::init( $options );

		$obj->input( [] );
		$this->expectOutputString( '' );
	}

	public function test_init_null(): void {
		$obj     = \MslsMenu::init( null );

		$this->assertInstanceOf( \MslsMenu::class, $obj );
		$this->assertFalse( has_filter('wp_nav_menu_items', [ \MslsMenu::class, 'nav_item' ] ) );
		$this->assertFalse( has_action('msls_admin_register', [ \MslsMenu::class, 'admin_register' ] ) );
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
