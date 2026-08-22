<?php

namespace lloc\MslsMenuTests;

use Brain\Monkey;
use Brain\Monkey\Functions;

beforeAll(
	function () {
		Monkey\setUp();

		Functions\stubs(
			array(
				'plugin_basename' => 'abc',
				'__',
				'selected'        => 'selected="selected"',
			)
		);
	}
);

beforeEach(
	function () {
		$options                          = \Mockery::mock( 'lloc\Msls\Options\Options' );
		$options->mslsmenu_theme_location = array( 'test' );

		$this->sut = \MslsMenu::init( $options );
	}
);

afterAll(
	function () {
		Monkey\tearDown();
	}
);

it(
	'returns an instance of MslsMenu when factory receives an MslsOptions object',
	function () {
		expect( $this->sut )->toBeInstanceOf( \MslsMenu::class );
	}
);

it(
	'adds a filter when factory receives an MslsOptions object',
	function () {
		expect( has_filter( 'wp_nav_menu_items', array( $this->sut, 'nav_item' ) ) )->toEqual( 10 );
	}
);

it(
	'adds an action when factory receives an MslsOptions object',
	function () {
		expect( has_action( 'msls_admin_register', array( $this->sut, 'admin_register' ) ) )->toEqual( 10 );
	}
);

it(
	'calls add_settings_section on $sut->admin_register()',
	function () {
		Functions\expect( 'add_settings_section' )->once();

		$this->sut->admin_register( 'test' );
	}
);

it(
	'returns an empty string on $sut->nav_item()',
	function () {
		$expected = '';

		$args                 = new \stdClass();
		$args->theme_location = 'other';

		$result = $this->sut->nav_item( '', $args );

		expect( $result )->toEqual( $expected );
	}
);

it(
	'calls add_settings_field on $sut->add_settings()',
	function () {
		Functions\expect( 'add_settings_field' )->times( 6 );

		$this->sut->add_settings();
	}
);

it(
	'calls get_nav_menu_locations on $sut->theme_location()',
	function () {
		Functions\expect( 'get_nav_menu_locations' )->once()->andReturn( array( 'test' => 1 ) );
		Functions\expect( 'esc_attr' )->once()->andReturnFirstArg();
		Functions\expect( 'esc_html__' )->once()->andReturnFirstArg();

		$expected = '<select id="mslsmenu_theme_location" name="msls[mslsmenu_theme_location][]" multiple="multiple"><option value="" selected="selected">-- empty --</option><option value="test" selected="selected">test</option></select>';

		$this->sut->theme_location( array() );

		$this->expectOutputString( $expected );
	}
);

it(
	'prints a string on $sut->display()',
	function () {
		$expected = '<select></select>';

		$this->sut->display( array() );

		$this->expectOutputString( $expected );
	}
);

it(
	'prints a string on $sut->input()',
	function () {
		$expected = '<input />';

		$this->sut->input( array() );

		$this->expectOutputString( $expected );
	}
);

it(
	'appends the switcher on $sut->nav_item() when the theme location matches',
	function () {
		Functions\when( 'msls_output' )->justReturn( new \Output() );

		$options                          = \Mockery::mock( 'lloc\Msls\Options\Options' );
		$options->mslsmenu_theme_location = array( 'primary' );
		$options->mslsmenu_display        = 0;
		$options->only_with_translation   = false;
		$options->mslsmenu_before_output  = '<ul>';
		$options->mslsmenu_after_output   = '</ul>';
		$options->mslsmenu_before_item    = '<li>';
		$options->mslsmenu_after_item     = '</li>';

		$sut = \MslsMenu::init( $options );

		$args                 = new \stdClass();
		$args->theme_location = 'primary';

		expect( $sut->nav_item( '', $args ) )->toEqual( '<ul><li>de</li><li>en</li></ul>' );
	}
);
