<?php

namespace lloc\MslsMenuTests;

use Brain\Monkey;
use Brain\Monkey\Functions;

beforeAll( function () {
	Monkey\setUp();

	Functions\expect( 'load_plugin_textdomain' )->atLeast()->once();
	Functions\stubs( [
		'plugin_basename' => 'abc',
		'__',
		'selected' => 'selected="selected"',
	] );
} );

beforeEach( function() {
	$options = \Mockery::mock( 'lloc\Msls\MslsOptions' );
	$options->mslsmenu_theme_location = [ 'test' ];

	$this->sut = \MslsMenu::init( $options );
} );

afterAll( function () {
	Monkey\tearDown();
} );

it( 'returns an instance of MslsMenu when factory receives an MslsOptions object', function () {
	expect( $this->sut )->toBeInstanceOf( \MslsMenu::class );
} );

it( 'adds a filter when factory receives an MslsOptions object', function () {
	expect( has_filter( 'wp_nav_menu_items', [ $this->sut, 'nav_item' ] ) )->toEqual( 10 );
} );

it( 'adds an action when factory receives an MslsOptions object', function () {
	expect( has_action( 'msls_admin_register', [ $this->sut, 'admin_register' ] ) )->toEqual( 10 );
} );

it( 'calls add_settings_section on $sut->admin_register()', function () {
	Functions\expect( 'add_settings_section' )->once();

	$this->sut->admin_register( 'test' );
} );

it( 'returns an empty string on $sut->nav_item()', function() {
	$expected = '';

	$args = new \stdClass();
	$args->theme_locations = 'test';

	$result = $this->sut->nav_item( '', $args );

	expect( $result )->toEqual( $expected );
} );

it( 'calls add_settings_field on $sut->add_settings()', function () {
	Functions\expect( 'add_settings_field' )->times( 6 );

	$this->sut->add_settings();
} );

it( 'calls get_nav_menu_locations on $sut->theme_location()', function () {
	Functions\expect( 'get_nav_menu_locations' )->once()->andReturn( [ 'test' => 1 ] );
    Functions\expect( 'esc_attr' )->once()->andReturnFirstArg();
    Functions\expect( 'esc_html__' )->once()->andReturnFirstArg();

	$expected = '<select id="mslsmenu_theme_location" name="msls[mslsmenu_theme_location][]" multiple="multiple"><option value="" selected="selected">-- empty --</option><option value="test" selected="selected">test</option></select>';

	$this->sut->theme_location( [] );

	$this->expectOutputString( $expected );
} );

it( 'prints a string on $sut->display()', function () {
	$expected = '<select></select>';

	$this->sut->display( [] );

	$this->expectOutputString( $expected );
} );

it( 'prints a string on $sut->input()', function () {
	$expected = '<input />';

	$this->sut->input( [] );

	$this->expectOutputString( $expected );
} );
