<?php

/*
Plugin Name: MslsMenu
Plugin URI: https://github.com/lloc/MslsMenu
Description: Adds the Multisite Language Switcher to the primary-nav-menu
Version: 0.2
Author: Dennis Ploetner
Author URI: http://lloc.de/
*/

/*
 Copyright 2013  Dennis Ploetner  (email : re@lloc.de)

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
 * Plugin init
 * @package mslsmenu
 */
function mtw_plugin_init() {
	load_plugin_textdomain(
		'mslsmenu',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages/'
	);
}
add_action( 'init', 'mtw_plugin_init' );

/**
 * Callback for wp_nav_menu_items
 * @package mslsmenu
 * @param string $items
 * @param array $args
 * @return string
 */
function mslsmenu_item( $items, $args ) {
	if ( function_exists( 'the_msls' ) ) {
		$options = MslsOptions::instance();

		if ( $options->mslsmenu_theme_location == $args->theme_location ) {
			$mslsmenu = '';

			$obj = new MslsOutput;
			foreach ( $obj->get( (int) $options->mslsmenu_display ) as $item ) {
				$mslsmenu .= $options->mslsmenu_before_item . $item . $options->mslsmenu_after_item;
			}

			$items .= $options->mslsmenu_before_output . $mslsmenu . $options->mslsmenu_after_output;
		}
		return $items;
	}
}
add_filter( 'wp_nav_menu_items', 'mslsmenu_item', 10, 2 );

/**
 * Callback for msls_admin_register
 * @package mslsmenu
 * @param string $page
 */
function mslsmenu_admin_register( $page ) {
	add_settings_section( 'mslsmenu_section', __( 'Menu Settings' ), null, $page );

	$args = array( 'msls_admin' => new MslsAdmin() );
	
	add_settings_field( 'mslsmenu_theme_location', __( 'Theme Location', 'msls' ), 'mslsmenu_theme_location', $page, 'mslsmenu_section', $args );
	add_settings_field( 'mslsmenu_display', __( 'Display', 'msls' ), 'mslsmenu_display', $page, 'mslsmenu_section', $args );
	add_settings_field( 'mslsmenu_before_output', __( 'Text/HTML before the list', 'msls' ), 'mslsmenu_before_output', $page, 'mslsmenu_section', $args );
	add_settings_field( 'mslsmenu_after_output', __( 'Text/HTML after the list', 'msls' ), 'mslsmenu_after_output', $page, 'mslsmenu_section', $args );
	add_settings_field( 'mslsmenu_before_item', __( 'Text/HTML before each item', 'msls' ), 'mslsmenu_before_item', $page, 'mslsmenu_section', $args );
	add_settings_field( 'mslsmenu_after_item', __( 'Text/HTML after each item', 'msls' ), 'mslsmenu_after_item', $page, 'mslsmenu_section', $args );
}
add_action( 'msls_admin_register', 'mslsmenu_admin_register' );

/**
 * Callback for mslsmenu_theme_location
 * @package mslsmenu
 * @param array $args
 */
function mslsmenu_theme_location( $args ) {
	$locations = array( 0 => '' );
	foreach ( get_nav_menu_locations() as $key => $value ) {
		$locations[ $key ] = $key;
	}

	echo $args['msls_admin']->render_select(
		'mslsmenu_theme_location',
		$locations,
		MslsOptions::instance()->mslsmenu_theme_location
	); // xss ok
}

/**
 * Callback for mslsmenu_display
 * @package mslsmenu
 * @param array $args
 */
function mslsmenu_display( $args ) {
	echo $args['msls_admin']->render_select(
		'mslsmenu_display',
		MslsLink::get_types_description(),
		MslsOptions::instance()->mslsmenu_display
	); // xss ok
}

/**
 * Callback for mslsmenu_before_output
 * @package mslsmenu
 * @param array $args
 */
function mslsmenu_before_output( $args ) {
	echo $args['msls_admin']->render_input( 'mslsmenu_before_output' ); // xss ok
}

/**
 * Callback for mslsmenu_after_output
 * @package mslsmenu
 * @param array $args
 */
function mslsmenu_after_output( $args ) {
	echo $args['msls_admin']->render_input( 'mslsmenu_after_output' ); // xss ok
}

/**
 * Callback for mslsmenu_before_item
 * @package mslsmenu
 * @param array $args
 */
function mslsmenu_before_item( $args  ) {
	echo $args['msls_admin']->render_input( 'mslsmenu_before_item' ); // xss ok
}

/**
 * Callback for mslsmenu_after_item
 * @package mslsmenu
 * @param array $args
 */
function mslsmenu_after_item( $args  ) {
	echo $args['msls_admin']->render_input( 'mslsmenu_after_item' ); // xss ok
}
