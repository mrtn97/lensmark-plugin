<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://lensmark.org/documentation
 * @since             1.0.0
 * @package           Lensmark
 *
 * @wordpress-plugin
 * Plugin Name:       Lensmark
 * Plugin URI:        https://lensmark.org/
 * Description:       Photo-monitoring Plugin for WordPress
 * Version:           1.0.0
 * Author:            Martin Clément <martin.clement@outlook.com> Bern University of Applied Sciences
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
 */
define( 'LENSMARK_VERSION', '1.0.0' );

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
 * @since    1.0.0
 */
function run_lensmark() {

	$plugin = new Lensmark();
	$plugin->run();

}
run_lensmark();

?>