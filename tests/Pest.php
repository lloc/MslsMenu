<?php

class Options {

	public static function instance() {
		return new self();
	}
}

class Link {

	public static function get_types_description() {
		return array();
	}
}

class Output {

	public static function init() {
		return new self();
	}

	public function get( $display, $filter = false, $exists = false ) {
		return array( 'de', 'en' );
	}
}

class Select {

	public function __construct( ...$args ) { }

	public function render(): string {
		return '<select></select>';
	}
}

class Text {

	public function __construct( ...$args ) { }

	public function render(): string {
		return '<input />';
	}
}

class_alias( Options::class, 'lloc\Msls\Options\Options' );
class_alias( Link::class, 'lloc\Msls\Link\Link' );
class_alias( Output::class, 'lloc\Msls\Frontend\Output' );
class_alias( Select::class, 'lloc\Msls\Component\Input\Select' );
class_alias( Text::class, 'lloc\Msls\Component\Input\Text' );
