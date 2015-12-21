<?php

/*
Plugin Name: MslsMenu
Plugin URI: https://github.com/lloc/MslsMenu
Description: Adds the Multisite Language Switcher to the primary-nav-menu
Version: 1.3.1
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
		add_filter( 'wp_nav_menu_items', array( $this, 'nav_item' ), 10, 2 );
		add_action( 'msls_admin_register', array( $this, 'admin_register' ) );
	}

	/**
	 * Plugin init
	 */
	public static function init() {
		load_plugin_textdomain( 'mslsmenu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

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
		if ( function_exists( 'the_msls' ) ) {
			$options   = MslsOptions::instance();
			$locations = (array) $options->mslsmenu_theme_location;

			if ( in_array( $args->theme_location, $locations ) ) {
				$mslsmenu = '';

				$obj = new MslsOutput;
				foreach ( $obj->get( (int) $options->mslsmenu_display ) as $item ) {
					$mslsmenu .= $options->mslsmenu_before_item . $item . $options->mslsmenu_after_item;
				}

				$items .= $options->mslsmenu_before_output . $mslsmenu . $options->mslsmenu_after_output;
			}
		}

		return $items;
	}

	/**
	 * Callback for msls_admin_register
	 *
	 * @param string $page
	 */
	function admin_register( $page ) {
		add_settings_section( 'mslsmenu_section', __( 'Menu Settings', 'mslsmenu' ), null, $page );

		$args = array( 'msls_admin' => new MslsAdmin() );

		$callback = array( $this, 'theme_location' );
		add_settings_field( 'mslsmenu_theme_location', __( 'Theme Location', 'mslsmenu' ), $callback, $page, 'mslsmenu_section', $args );

		$callback = array( $this, 'display' );
		add_settings_field( 'mslsmenu_display', __( 'Display', 'mslsmenu' ), $callback, $page, 'mslsmenu_section', $args );

		$callback = array( $this, 'input' );
		$fields   = array(
			'mslsmenu_before_output' => __( 'Text/HTML before the list', 'mslsmenu' ),
			'mslsmenu_after_output'  => __( 'Text/HTML after the list', 'mslsmenu' ),
			'mslsmenu_before_item'   => __( 'Text/HTML before each item', 'mslsmenu' ),
			'mslsmenu_after_item'    => __( 'Text/HTML after each item', 'mslsmenu' ),
		);
		foreach ( $fields as $id => $label ) {
			$args['mslsmenu_input'] = $id;
			add_settings_field( $id, $label, $callback, $page, 'mslsmenu_section', $args );
		}
	}

	/**
	 * Callback for mslsmenu_theme_location
	 *
	 * @param array $args
	 */
	function theme_location( $args ) {
		$locations = array();
		foreach ( get_nav_menu_locations() as $key => $value ) {
			$locations[ $key ] = $key;
		}

		$selected = (array) MslsOptions::instance()->mslsmenu_theme_location;

		$options = array(
			sprintf(
				'<option value="" %s>%s</option>',
				selected( true, ( in_array( '', $selected ) ), false ),
				__( '-- empty --', 'mslsmenu' )
			)
		);
		foreach ( $locations as $value => $description ) {
			$options[] = sprintf(
				'<option value="%s" %s>%s</option>',
				$value,
				selected( true, ( in_array( $value, $selected ) ), false ),
				$description
			);
		}

		printf(
			'<select id="%1$s" name="msls[%1$s][]" multiple="multiple">%2$s</select>',
			'mslsmenu_theme_location',
			implode( '', $options )
		);
	}

	/**
	 * Callback for mslsmenu_display
	 *
	 * @param array $args
	 */
	function display( $args ) {
		echo $args['msls_admin']->render_select(
			'mslsmenu_display',
			MslsLink::get_types_description(),
			MslsOptions::instance()->mslsmenu_display
		);
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

MslsMenu::init();
