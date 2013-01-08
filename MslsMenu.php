<?php

/*
Plugin Name: MslsMenu
Plugin URI: http://lloc.de/multisite-language-switcher-ins-menu-einbauen.html
Description: Adds the Multisite Language Switcher to the primary-nav-menu
Version: 0.1
Author: Dennis Ploetner
Author URI: http://lloc.de/
*/

function mslsmenu_item( $items, $args ) {
	if ( class_exists( 'MslsAutoloader' ) && 'primary-nav' == $args->theme_location && !method_exists( $args->walker, 'is_dropdown' ) ) {
		$obj = new MslsOutput;
		foreach ( $obj->get( 0 ) as $item )
			$items .= '<li id="menu-item-msls" class="menu-item">' . $item . '</li>';
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'mslsmenu_item', 10, 2 );

function mslsmenu_link_create() {
	class MslsMenuItem extends MslsLink {
		protected $format_string = '<img src="{src}" alt="{alt}"/><strong>{txt}</strong><span>locale</span>';
	}
	return new MslsMenuItem();
}
add_filter( 'msls_link_create', 'mslsmenu_link_create' );

function mslsmenu_add_css() {
	wp_register_style( 'mslsmenu-style', plugins_url( 'style.css', __FILE__ ) );
	wp_enqueue_style( 'mslsmenu-style' );
}
add_action( 'wp_enqueue_scripts', 'mslsmenu_add_css' );
