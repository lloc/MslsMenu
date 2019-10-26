<?php

/*
Plugin Name: MslsMenu
Plugin URI: https://github.com/lloc/MslsMenu
Description: Adds the Multisite Language Switcher to the primary-nav-menu
Version: 2.1
Author: Dennis Ploetner
Author URI: http://lloc.de/
Text Domain: mslsmenu
*/

/*
Copyright 2014  Dennis Ploetner  (email : re@lloc.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * MslsMenu Class
 * @package mslsmenu
 */
class MslsMenu {

	/**
	 * MslsMenu constructor.
	 */
	public function __construct() {
		load_plugin_textdomain( 'mslsmenu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Plugin init
	 *
	 * @return MslsMenu
	 */
	public static function init() {
		if ( class_exists( lloc\Msls\MslsOptions::class ) ) {
			add_filter( 'wp_nav_menu_items', [ MslsMenu::class, 'nav_item' ], 10, 2 );
			add_action( 'msls_admin_register', [ MslsMenu::class, 'admin_register' ] );
		}

		return new self;
	}

	/**
	 * Callback for wp_nav_menu_items
	 *
	 * @param string $items
	 * @param StdClass $args
	 *
	 * @return string
	 */
	function nav_item( $items, $args ) {
		$options   = lloc\Msls\MslsOptions::instance();
		$locations = (array) $options->mslsmenu_theme_location;

		if ( in_array( $args->theme_location, $locations ) ) {
			$mslsmenu = '';

			$obj = lloc\Msls\MslsOutput::init();
			foreach ( $obj->get( (int) $options->mslsmenu_display, false, (int) $options->only_with_translation ) as $item ) {
				$mslsmenu .= $options->mslsmenu_before_item . $item . $options->mslsmenu_after_item;
			}

			$items .= $options->mslsmenu_before_output . $mslsmenu . $options->mslsmenu_after_output;
		}

		return $items;
	}

	/**
	 * Callback for msls_admin_register
	 *
	 * @param string $page
	 */
	function admin_register( $page ) {
		$sid   = 'mslsmenu_section';
		$label = __( 'Menu Settings', 'mslsmenu' );
		add_settings_section( $sid, $label, null, $page );

		$args = [ 'msls_admin' => lloc\Msls\MslsAdmin::init() ];

		$label    = __( 'Theme Location', 'mslsmenu' );
		$callback = [ $this, 'theme_location' ];
		add_settings_field( 'mslsmenu_theme_location', $label, $callback, $page, $sid, $args );

		$label    = __( 'Display', 'mslsmenu' );
		$callback = [ $this, 'display' ];
		add_settings_field( 'mslsmenu_display', $label, $callback, $page, $sid, $args );

		$fields   = [
			'mslsmenu_before_output' => __( 'Text/HTML before the list', 'mslsmenu' ),
			'mslsmenu_after_output'  => __( 'Text/HTML after the list', 'mslsmenu' ),
			'mslsmenu_before_item'   => __( 'Text/HTML before each item', 'mslsmenu' ),
			'mslsmenu_after_item'    => __( 'Text/HTML after each item', 'mslsmenu' ),
		];
		$callback = [ $this, 'input' ];
		foreach ( $fields as $id => $label ) {
			$args['mslsmenu_input'] = $id;
			add_settings_field( $id, $label, $callback, $page, $sid, $args );
		}
	}

	/**
	 * Callback for mslsmenu_theme_location
	 *
	 * @param array $args
	 */
	function theme_location( $args ) {
		$locations = [];
		foreach ( get_nav_menu_locations() as $key => $value ) {
			$locations[ $key ] = $key;
		}

		$selected = (array) lloc\Msls\MslsOptions::instance()->mslsmenu_theme_location;

		$options = [
			sprintf(
				'<option value="" %s>%s</option>',
				selected( true, ( in_array( '', $selected ) ), false ),
				__( '-- empty --', 'mslsmenu' )
			)
		];
		foreach ( $locations as $value => $description ) {
			$options[] = sprintf(
				'<option value="%s" %s>%s</option>',
				$value,
				selected( true, ( in_array( $value, $selected ) ), false ),
				$description
			);
		}

		printf( '<select id="%1$s" name="msls[%1$s][]" multiple="multiple">%2$s</select>', 'mslsmenu_theme_location', implode( '', $options ) );
	}

	/**
	 * Callback for mslsmenu_display
	 *
	 * @param array $args
	 */
	function display( $args ) {
		$types   = lloc\Msls\MslsLink::get_types_description();
		$display = lloc\Msls\MslsOptions::instance()->mslsmenu_display;

		echo $args['msls_admin']->render_select( 'mslsmenu_display', $types, $display );
	}

	/**
	 * Callback for mslsmenu text-inputs
	 *
	 * @param array $args
	 */
	function input( $args ) {
		echo $args['msls_admin']->render_input( $args['mslsmenu_input'] );
	}

}

if ( function_exists( 'add_action' ) ) {
	add_action( 'plugins_loaded', function () {
		MslsMenu::init();
	} );
}
