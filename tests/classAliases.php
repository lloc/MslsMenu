<?php

class Options {

	public static function instance() {
		return new self();
	}

}

class Admin {

	public static function init() {
		return new self();
	}
}

class Link {

	public static function get_types_description()  {
		return [];
	}

}

class Output {

	public static function init() {
		return new self();
	}
}

class_alias( Options::class, 'lloc\Msls\MslsOptions' );
class_alias( Admin::class, 'lloc\Msls\MslsAdmin' );
class_alias( Link::class, 'lloc\Msls\MslsLink' );
class_alias( Output::class, 'lloc\Msls\MslsOutput' );
