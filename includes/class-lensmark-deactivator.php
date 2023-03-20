<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wbth.m-clement.ch/
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Lensmark
 * @subpackage Lensmark/includes
 * @author     Martin Clément <martin.clement@outlook.com>
 */

 class Lensmark_Deactivator {

	/**
	 * Load all plugin functionalities that occur when deactivating the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		unregister_post_type('photopost');
		remove_shortcode('lensmark_submission_form');
	}

}

