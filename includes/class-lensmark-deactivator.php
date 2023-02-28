<?php

/**
 * Fired during plugin deactivation
 * @link       http://wbth.m-clement.ch/
 * @since      0.1.0
 * @package    Lensmark
 * @subpackage Lensmark/includes
 */

 class Lensmark_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * @since    0.1.0
	 */
	public static function deactivate() {
		// Remove shortcode
		remove_shortcode('lensmark_submission_form');
	}

}

