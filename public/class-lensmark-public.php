<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wbth.m-clement.ch
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Lensmark
 * @subpackage Lensmark/public
 * @author     Martin Clément <martin.clement@outlook.com>
 */
class Lensmark_Public {

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
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lensmark_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lensmark_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->lensmark, plugin_dir_url( __FILE__ ) . 'css/lensmark-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lensmark_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lensmark_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->lensmark, plugin_dir_url( __FILE__ ) . 'js/lensmark-public.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Add new shortcode that will display the submission form
	 */
	public static function lensmark_add_submission_form_shortcode() {
		add_shortcode( 'lensmark_submission_form', 'lensmark_shortcode_submission_form_html' );
		function lensmark_shortcode_submission_form_html($atts, $content = null) {
			/**
			 * The photopostId must be specified in the url (also for the QR-Code).
			 * The photopostId is used to assign the submitted photo to the correct photopost
			 * 
			 * https://some.site.com/somePage.html?photopostId=[postId]
			 * */
			if ( isset( $_GET['photopost_id'] ) ) {
				$photopost = $_GET['photopost_id'];
				ob_start();
				?>
				<form id="photo_entry_submission" method="post" action="#" enctype="multipart/form-data">
					<!--Add Type hidden to hide-->
					<input type="hidden" id="photopost_id" name="photopost_id" value="<?php echo $photopost; ?>" disabled><br>
					<label for="file">Photo:</label>
					<input type="file" id="photo_entry" name="photo_entry" accept="image" capture="environment" multiple="false"><br>
					<label for="first-name">First name:</label>
					<input type="text" id="first-name" name="first-name" required><br>
					<label for="last-name">Last name:</label>
					<input type="text" id="last-name" name="last-name" required><br>
					<label for="email">Email:</label>
					<input type="email" id="email" name="email" required><br>
					<input type="checkbox" id="terms" name="terms" value="checked" required>
					<label for="terms">I have read and accept the <a href="" target="_blank">privacy policy</a>.</label><br>
					<input type="checkbox" id="newsletter" name="newsletter" value="checked">
					<label for="newsletter">I would like to receive e-mails about the development and results of the photo
						monitoring project. (Optional)</label><br>
					<?php wp_nonce_field( 'photo_entry', 'photo_entry_nonce' ); ?>
					<input type="submit" id="submit_photo_entry" name="submit_photo_entry" value="Submit">
				</form> <?
				return ob_get_clean();
			} else {
				ob_start();
				?>
				<h2>Error</h2>
				<?php
				return ob_get_clean();
			}
		}
	}

	/**
	 * Function that will handle photo submissions.
	 */
	function lensmark_submit_entry() {
		// Check that the nonce is valid.
		if (
			isset( $_POST['photo_entry_nonce'], $_POST['photopost'] )
			&& wp_verify_nonce( $_POST['photo_entry_nonce'], 'photo_entry' )
		) {
			// These files need to be included as dependencies when on the front end.
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );

			// Let WordPress handle the upload.
			// Remember, 'photo_entry' is the name of our file input in our form above.
			$attachment_id = media_handle_upload( 'photo_entry', $_POST['photopost'] );

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