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
			)
		);
	}
);


beforeEach(
	function () {
		$this->sut = \MslsMenu::init( null );
	}
);

afterAll(
	function () {
		Monkey\tearDown();
	}
);

it(
	'returns an instance of MslsMenu even when factory received null',
	function () {
		expect( $this->sut )->toBeInstanceOf( \MslsMenu::class );
	}
);

it(
	'adds no filter when factory received null',
	function () {
		expect( has_filter( 'wp_nav_menu_items', array( \MslsMenu::class, 'nav_item' ) ) )->toBeFalse();
	}
);

it(
	'adds no action when factory received null',
	function () {
		expect( has_action( 'msls_admin_register', array( \MslsMenu::class, 'admin_register' ) ) )->toBeFalse();
	}
);

it(
	'returns an empty string on $sut->nav_item() when factory received null',
	function () {
		$result = $this->sut->nav_item( '', (object) array() );

		expect( $result )->toEqual( '' );
	}
);
