<?php
/**
 * Plugin Name: WP Core Text Widget Extensibility
 * Description: Workaround to allow the rich Text widget in WordPress 4.8.0 and 4.8.1 to be extended. This will be unnecessary as of WordPress 4.8.2.
 * Plugin URI: https://core.trac.wordpress.org/ticket/41536
 * Author: Weston Ruter, XWP
 * Author URI: https://make.xwp.co/
 * Version: 0.1.0
 * License: GPLv2+
 *
 * Copyright (c) 2017 XWP (https://xwp.co/)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

/**
 * Override text-widgets script with patched version.
 *
 * @param WP_Scripts $wp_scripts Scripts.
 */
function wp_core_text_widget_extensibility_override_script( $wp_scripts ) {
	if ( isset( $wp_scripts->registered['text-widgets'] ) ) {
		$wp_scripts->registered['text-widgets']->src = plugin_dir_url( __FILE__ ) . 'text-widgets-patched.js';
	}
}

/**
 * Register ID bases for Text extended Text widgets.
 *
 * @global WP_Widget_Factory $wp_widget_factory
 */
function wp_core_text_widget_extensibility_register_id_bases() {
	global $wp_widget_factory;
	foreach ( $wp_widget_factory->widgets as $widget ) {
		if ( $widget instanceof WP_Widget_Text ) {
			wp_add_inline_script( 'text-widgets', sprintf( 'wp.textWidgets.idBases.push( %s );', wp_json_encode( $widget->id_base ) ) );
		}
	}
}

/**
 * Show admin notice that the plugin is no longer appliable.
 */
function wp_core_text_widget_extensibility_inapplicable_plugin_notice() {
	?>
	<div class="notice notice-info">
		<p>Note: The "WP Core Text Widget Extensibility" plugin can be uninstalled because it is now irrelevant.</p>
	</div>
	<?php
}

if ( version_compare( get_bloginfo( 'version' ), '4.8.0', '>=' ) && version_compare( get_bloginfo( 'version' ), '4.8.1', '<=' ) ) {
	add_action( 'wp_default_scripts', 'wp_core_text_widget_extensibility_override_script' );
	add_action( 'widgets_init', 'wp_core_text_widget_extensibility_register_id_bases', 90 );
} else {
	add_action( 'admin_notices', 'wp_core_text_widget_extensibility_inapplicable_plugin_notice' );
}
