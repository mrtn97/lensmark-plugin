<?php
function lnsmrk_shortcode_submission_form_html() {
	?>
	<h2>Submit Photo</h2>
	<form id="submission_form">
		<input type="hidden" id="photopostId" name="photopostId" value="">
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
		<label for="newsletter"></label>I would like to receive e-mails about the development and results of the photo monitoring project. (Optional)<br>
		<input type="submit" value="Submit">
	</form>
<?php
}
?>