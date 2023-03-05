<?php

/**
 * The Submission Form where citizen-scientists will submit photos
 *
 * @link       http://wbth.m-clement.ch/
 * @since      0.5.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/admin
 * @author     Martin Clément <martin.clement@outlook.com>
 */

class Lensmark_Submission_Form {

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
	 * Add new shortcode that will display the submission form
	 */
	public static function lensmark_add_submission_form_shortcode() {
		add_shortcode( 'lensmark_submission_form', 'lensmark_shortcode_submission_form_html' );
		/**
		 * The photopostId must be specified in the url (also for the QR-Code).
		 * The photopostId is used to assign the submitted photo to the correct photopost
		 * 
		 * https://some.site.com/somePage.html?photopostId=[postId]
		 * */
		function lensmark_shortcode_submission_form_html( $atts, $content = null ) {
			ob_start();
			if (isset($_POST['post_id'])) {
				$post_id = sanitize_text_field($_POST['post_id']);
				// Use $post_id in your code here
			  }
			?>
			<form id="photo_entry_submission" method="post" action="#" enctype="multipart/form-data">
				<!--Add Type hidden to hide-->
				<input type="hidden" id="post_id" name="post_id" value="<?php echo $post_id; ?>">
				<label for="file">Photo:</label>
				<input type="file" id="photo_entry" name="photo_entry" accept="image" capture="environment" multiple="false">
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
			</form>
			<?php return ob_get_clean();

		}
	}
}