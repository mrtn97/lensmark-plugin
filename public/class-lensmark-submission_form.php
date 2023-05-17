<?php

/**
 * Contains all submission form functionalities.
 *
 * @link       https://lensmark.org/article/photoposts/
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/public
 */

class Lensmark_Submission_Form {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $lensmark    The ID of this plugin.
	 */
	private $lensmark;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $lensmark       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $lensmark, $version ) {

		$this->lensmark = $lensmark;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets
	 * 
	 * @since	1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'lensmark-submission_form', plugin_dir_url( __FILE__ ) . 'css/lensmark-submission_form.css', array(), $this->version, 'all' );
	}

	/**
	 * Submission form shortcode content
	 * 
	 * @since    1.0.0
	 */
	public function lensmark_submission_form_shortcode() {
		/**
		 * The photopost_id must be specified in the url (also for the QR-Code).
		 * The photopost_id is used to assign the submitted photo to the correct photopost
		 * 
		 * https://some.site.com/somePage.html?photopost_id=[post-id]
		 * */
		echo '<div id="message"></div>';
		if ( isset( $_GET['photopost_id'] )) {
			$photopost_id = $_GET['photopost_id'];
			ob_start();
			?>
			<form id="photo_entry_submission" method="post" action="#" enctype="multipart/form-data">
				<input type="hidden" id="photopost_id" name="photopost_id" value="<?php echo $photopost_id; ?>">
				<label for="file">
					<?php _e( 'Photo', 'lensmark' ) ?>:
				</label>
				<input type="file" id="photo_entry" name="photo_entry" accept="image" capture="environment" multiple="false" data-content="<?php _e('Take a photo', 'lensmark');?>" >
				<label for="first-name">
					<?php _e( 'First name', 'lensmark' ) ?>:
				</label>
				<input type="text" id="first-name" name="first-name" required>
				<label for="last-name">
					<?php _e( 'Last name', 'lensmark' ) ?>:
				</label>
				<input type="text" id="last-name" name="last-name" required>
				<label for="email">
					<?php _e( 'Email', 'lensmark' ) ?>:
				</label>
				<input type="email" id="email" name="email" required>
				<span><input type="checkbox" id="terms" name="terms" value="checked" required>
					<label for="terms"><a href="<?php echo get_privacy_policy_url() ?>" target="_blank"><?php _e( 'I have read and accept the privacy policy page', 'lensmark' ) ?></a>.</label>
				</span>
				<?php wp_nonce_field( 'photo_entry', 'photo_entry_nonce' ); ?>
				<input type="submit" id="submit_photo_entry" class="wp-block-button" name="submit_photo_entry"
					value="<?php _e( 'Submit', 'lensmark' ) ?>">
			</form>
			<?php
			return ob_get_clean();
		} else {
			$message = sprintf(
				'<div class="alert error"><h4><span class="dashicons dashicons-warning"></span>%s</h4><p>%s</p></div>',
				__( 'Error', 'lensmark' ),
				__( 'Photopost ID does not exist. Please scan the QR code again or type in the url manually.', 'lensmark' )
			);
			// Set the message in the placeholder element if it exists
			$message_element = '<div id="message">' . $message . '</div>';
			echo sprintf( $message_element );
		}
		return ob_get_clean();
	}

	/**
	 * Handle photo submission upload and attaching it to the photopost.
	 * 
	 * @since	1.0.0
	 * @source	ChatGPT (https://chat.openai.com)
	 * Adapted by: Martin Clément <martin.clement@outlook.com>
	 */
	public function lensmark_submit_entry() {
		// Check that the nonce is valid.
		if (
			isset( $_POST['photo_entry_nonce'], $_POST['photopost_id'] )
			&& wp_verify_nonce( $_POST['photo_entry_nonce'], 'photo_entry' )
		) {
			// Dependencies to handle the image upload on the frontend
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );

			// Attach image to photopost with id given in the url as a parameter
			$attachment_id = media_handle_upload( 'photo_entry', $_POST['photopost_id'] );
			$photopost_permalink = get_permalink($_POST['photopost_id']);

			// If the upload fails:
			if ( is_wp_error( $attachment_id ) ) {
				$message = sprintf(
					'<div class="alert error"><h4><span class="dashicons dashicons-warning"></span>%s</h4><p>%s</p></div>',
					__( 'Error', 'lensmark' ),
					__( 'Something went wrong, please try again.', 'lensmark' )
				);
			} else {
				$message = sprintf(
					'<div class="alert success">
						<h4><span class="dashicons dashicons-yes"></span>%s</h4>
						<p>%s</p>
						<p>%s</p>
						<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
						<div class="wp-block-button is-style-outline" ><a href="'. $photopost_permalink . '" class="wp-block-button__link wp-element-button">' . __('Open Photopost page', 'lensmark') . '</a></div>
					</div>',
					__( 'Thank you!', 'lensmark' ),
					__( 'The image was uploaded successfully and will be reviewed by the organization.', 'lensmark' ),
					__( 'You may close this tab now or check out submitted photos from this photopost.', 'lensmark'),
				);
			}
		} else {
			// If the nonce is not valid
			$message = sprintf(
				'<div class="alert error"><h4><span class="dashicons dashicons-warning"></span>%s</h4><p>%s</p></div>',
				__( 'Error', 'lensmark' ),
				__( 'Security check has failed, please try again.', 'lensmark' )
			);
		}
		// Set the message in the placeholder element if it exists
		if ( isset( $_POST['photo_entry_nonce'], $_POST['photopost_id'] ) ) {
			$message_element = '<div id="message">' . $message . '</div>';
			echo sprintf( $message_element );
		}

	}
}