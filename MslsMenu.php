<?php
/**
 * MslsMenu
 *
 * @copyright Copyright (C) 2011-2022, Dennis Ploetner, re@lloc.de
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 or later
 * @wordpress-plugin
 *
 * Plugin Name: MslsMenu
 * Requires Plugins: multisite-language-switcher
 * Version: 2.5.1
 * Plugin URI: https://wordpress.org/plugins/mslsmenu/
 * Description: Adds the Multisite Language Switcher to the primary-nav-menu
 * Author: Dennis Ploetner
 * Author URI: http://lloc.de/
 * Text Domain: mslsmenu
 * Domain Path: /languages/
 * License: GPLv2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

declare( strict_types=1 );

/**
 * MslsMenu Class
 * @package mslsmenu
 */
final class MslsMenu {

	/**
	 * @var string
	 */
	protected $page;

	/**
	 * @var ?object
	 */
	protected $options;

	const SID = 'mslsmenu_section';

	/**
	 * @param ?object $options
	 */
	public function __construct( $options ) {
		$this->options = $options;

		load_plugin_textdomain( 'mslsmenu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Plugin init
	 *
	 * @param ?object $options
	 *
	 * @return MslsMenu
	 */
	public static function init( $options ): MslsMenu {
		$obj = new self( $options );

		if ( ! is_null( $options ) ) {
			add_filter( 'wp_nav_menu_items', [ $obj, 'nav_item' ], 10, 2 );
			add_action( 'msls_admin_register', [ $obj, 'admin_register' ] );
		}

		return $obj;
	}

	private function get_msls_output(): lloc\Msls\MslsOutput{
		return function_exists( 'msls_output' ) ? msls_output() : lloc\Msls\MslsOutput::init();
	}

	/**
	 * Callback for wp_nav_menu_items
	 *
	 * @param string $items
	 * @param \stdClass $args
	 *
	 * @return string
	 */
	public function nav_item( string $items, \stdClass $args ): string {
		$menu_locations = $this->options->mslsmenu_theme_location ?? '';
		$theme_location = $args->theme_location ?? '';

		if ( is_array( $menu_locations ) && in_array( $theme_location, $menu_locations ) ) {
			$menu = '';

			$obj = $this->get_msls_output();
			foreach ( $obj->get( (int) $this->options->mslsmenu_display, false, (int) $this->options->only_with_translation ) as $item ) {
				$menu .= $this->options->mslsmenu_before_item . $item . $this->options->mslsmenu_after_item;
			}

			$items .= $this->options->mslsmenu_before_output . $menu . $this->options->mslsmenu_after_output;
		}

		return $items;
	}

	/**
	 * Callback for msls_admin_register
	 *
	 * @param string $page
	 */
	public function admin_register( string $page ) {
		$label = __( 'Menu Settings', 'mslsmenu' );

		$this->page = $page;

		add_settings_section( self::SID, $label, [ $this, 'add_settings' ], $page );
	}

	/**
	 * Callback for add_settings_section in admin_register
	 */
	public function add_settings(): void {
		$args = [ 'msls_admin' => lloc\Msls\MslsAdmin::init() ];

		$label    = __( 'Theme Location', 'mslsmenu' );
		$callback = [ $this, 'theme_location' ];
		add_settings_field( 'mslsmenu_theme_location', $label, $callback, $this->page, self::SID, $args );

		$label    = __( 'Display', 'mslsmenu' );
		$callback = [ $this, 'display' ];
		add_settings_field( 'mslsmenu_display', $label, $callback, $this->page, self::SID, $args );

		$fields = [
			'mslsmenu_before_output' => __( 'Text/HTML before the list', 'mslsmenu' ),
			'mslsmenu_after_output'  => __( 'Text/HTML after the list', 'mslsmenu' ),
			'mslsmenu_before_item'   => __( 'Text/HTML before each item', 'mslsmenu' ),
			'mslsmenu_after_item'    => __( 'Text/HTML after each item', 'mslsmenu' ),
		];

		$callback = [ $this, 'input' ];
		foreach ( $fields as $id => $label ) {
			$args['mslsmenu_input'] = $id;
			add_settings_field( $id, $label, $callback, $this->page, self::SID, $args );
		}
	}

	/**
	 * Callback for mslsmenu_theme_location
	 *
	 * @param array $args
	 */
	public function theme_location( array $args ) {
		$menu_locations  = get_nav_menu_locations();
		$theme_locations = $this->options->mslsmenu_theme_location ?? '';
		$options         = [
			sprintf( '<option value="" %s>%s</option>', $this->selected( '', $theme_locations ), esc_html__( '-- empty --', 'mslsmenu' ) )
		];

		foreach ( array_keys( $menu_locations ) as $value ) {
			$options[] = sprintf( '<option value="%1$s" %2$s>%1$s</option>', esc_attr( $value ), $this->selected( $value, $theme_locations ) );
		}

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        printf( '<select id="%1$s" name="msls[%1$s][]" multiple="multiple">%2$s</select>', 'mslsmenu_theme_location', implode( '', $options ) );
	}

	/**
	 * @param string $needle
	 * @param mixed $locations
	 *
	 * @return string
	 */
	protected function selected( string $needle, $locations ): string {
		return is_array( $locations ) ? selected( true, in_array( $needle, $locations ), false ) : '';
	}

	/**
	 * Callback for mslsmenu_display
	 *
	 * @param array $args
	 */
	public function display( array $args ) {
		$types   = lloc\Msls\MslsLink::get_types_description();
		$display = $this->options->mslsmenu_display ?? '0';

        /**
         * Backward compatibility
         */
		if ( ! class_exists( lloc\Msls\Component\Input\Select::class ) ) {
			// @codeCoverageIgnoreStart

            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['msls_admin']->render_select( 'mslsmenu_display', $types, $display );

			return;
			// @codeCoverageIgnoreEnd
		}

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo ( new lloc\Msls\Component\Input\Select( 'mslsmenu_display', $types, $display ) )->render();
	}

	/**
	 * Callback for mslsmenu text-inputs
	 *
	 * @param array $args
	 */
	public function input( array $args ) {
        /**
         * Backward compatibility
         */
		if ( ! class_exists( 'lloc\Msls\Component\Input\Text' ) ) {
			// @codeCoverageIgnoreStart

            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $args['msls_admin']->render_input( $args['mslsmenu_input'] );

			return;
			// @codeCoverageIgnoreEnd
		}

		$key   = $args['mslsmenu_input'] ?? '';
		$value = $this->options->$key ?? '';

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo ( new lloc\Msls\Component\Input\Text( $key, $value ) )->render();
	}

}

// @codeCoverageIgnoreStart
if ( function_exists( 'add_action' ) ) {
	add_action( 'plugins_loaded', function () {
		$options = class_exists( lloc\Msls\MslsOptions::class ) ? lloc\Msls\MslsOptions::instance() : null;
		MslsMenu::init( $options );
	} );
}
// @codeCoverageIgnoreEnd