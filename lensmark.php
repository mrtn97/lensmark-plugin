<?php
/**
 * LENSMARK
 *
 * @package           LensmarkPackage
 * @author            Martin Clément
 * @copyright         2023 Berner Fachhochschule
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Lensmark
 * Plugin URI:        https://wbth.m-clement.ch/
 * Description:       Photomonitoring Plugin for Wordpress
 * Version:           0.0.1
 * Requires at least: 6.1.1
 * Requires PHP:      7.2
 * Author:            Martin Clément
 * Author URI:        https://m-clement.ch/
 * Text Domain:       lensmark
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 */


/**
 * Register the "photopost" custom post type
 */
function lnsmrk_setup_post_type() {
	register_post_type( 'photopost', [ 'public' => true ] );
}
add_action( 'init', 'lnsmrk_setup_post_type' );


/**
 * Activate the plugin.
 */
function lnsmrk_activate() {
	// Trigger our function that registers the custom post type plugin.
	lnsmrk_setup_post_type();
	// Clear the permalinks after the post type has been registered.
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'lnsmrk_activate' );


/**
 * Add Plugin Dashboard Page
 */
add_action( 'admin_menu', 'lnsmrk_dashboard_page' );
function lnsmrk_dashboard_page() {
	add_menu_page( 'Lensmark', 'Lensmark', 'manage_options', plugin_dir_path( __FILE__ ) . 'admin/index.php', 'lnsmrk_dashboard_page' , 'dashicons-camera', 20 );
}

/**
 * Add Plugin Settings Page
 */
add_action( 'admin_menu', 'lnsmrk_options_page' );
function lnsmrk_options_page() {
	add_submenu_page(plugin_dir_path( __FILE__ ) . 'admin/index.php', 'Settings', 'Settings', 'manage_options', plugin_dir_path( __FILE__ ) . 'admin/settings.php', 'lnsmrk_options_page');
}

?>