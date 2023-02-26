<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wbth.m-clement.ch/
 * @since             0.1.0
 * @package           Lensmark
 *
 * @wordpress-plugin
 * Plugin Name:       Lensmark
 * Plugin URI:        http://wbth.m-clement.ch/
 * Description:       Photomonitoring Plugin for WordPress
 * Version:           0.1.0
 * Author:            Martin Clément <martin.clement@outlook.com> Berner Fachhochschule
 * Author URI:        http://m-clement.ch/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lensmark
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '0.1.0' );

/**
 * Plugin Activation
 */
function activate_lensmark() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lensmark-activator.php';
	Lensmark_Activator::activate();
}

/**
 * Plugin Deactivation
 */
function deactivate_lensmark() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lensmark-deactivator.php';
	Lensmark_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lensmark' );
register_deactivation_hook( __FILE__, 'deactivate_lensmark' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lensmark.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_lensmark() {

	$plugin = new Lensmark();
	$plugin->run();

}
run_lensmark();

?>