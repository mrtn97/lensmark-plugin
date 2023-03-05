<?php

/**
 * This class handles photo submissions
 *
 * @link       http://wbth.m-clement.ch/
 * @since      0.5.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/public
 * @author     Martin Clément <martin.clement@outlook.com>
 */

class Lensmark_Submission_Handler {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.5.0
	 * @access   private
	 * @var      string    $lensmark    The ID of this plugin.
	 */
	private $lensmark;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.5.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.5.0
	 * @param      string    $lensmark       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $lensmark, $version ) {

		$this->lensmark = $lensmark;
		$this->version = $version;

	}

	/**
	 * Function that will handle photo submissions.
	 */
	function lensmark_submit_entry() {
		// Check that the nonce is valid.
		if (
			isset( $_POST['photo_entry_nonce'], $_POST['photopost_id'] )
			&& wp_verify_nonce( $_POST['photo_entry_nonce'], 'photo_entry' )
		) {
			// These files need to be included as dependencies when on the front end.
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );

			// Let WordPress handle the upload.
			// Remember, 'photo_entry' is the name of our file input in our form above.
			$attachment_id = media_handle_upload( 'photo_entry',  $_POST['photopost_id'] );

			if ( is_wp_error( $attachment_id ) ) {
				// There was an error uploading the image.
			} else {
				// The image was uploaded successfully!
			}

		} else {
			// The security check failed, maybe show the user an error.
		}
	}

}