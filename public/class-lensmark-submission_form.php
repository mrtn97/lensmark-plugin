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
			$photopostId = $_GET['photopostId']; 
			ob_start();
			?>
			<form id="submission_form">
				<input type="" id="photopostId" name="photopostId" value="<?php echo $photopostId; ?>">
				<input type="file" name="picture" accept="image" capture="environment">
				<label for="first-name">First name:</label>
				<input type="text" id="first-name" name="first-name" required><br>
				<label for="last-name">First name:</label>
				<input type="text" id="last-name" name="last-name" required><br>
				<label for="email">Email:</label>
				<input type="email" id="email" name="email" required><br>
				<input type="checkbox" id="terms" name="terms" value="checked" required>
				<label for="terms"></label>I have read and accept the <a href="" target="_blank">privacy policy</a>.<br>
				<input type="checkbox" id="newsletter" name="newsletter" value="checked">
				<label for="newsletter"></label>I would like to receive e-mails about the development and results of the photo
				monitoring project. (Optional)<br>
				<input type="submit" value="Submit">
			</form>
			<?php return ob_get_clean();
		}
	}

}
?>