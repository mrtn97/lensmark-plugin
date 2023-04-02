<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://wbth.m-clement.ch/
 * @since      0.1.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/admin/partials
 */
?>

<?php

/**
	 * Class containing all plugin settings
	 *
	 * @since    1.0.0
	 */
class Lensmark_Admin_Display {
    public static function lensmark_settings_page_html() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        } ?>
            <div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "lensmark_settings"
			settings_fields( 'lensmark_settings' );
			// output setting sections and their fields
			// (sections are registered for "lensmark", each field is registered to a specific section)
			do_settings_sections( 'lensmark_settings' );
			// output save settings button
			submit_button( __( 'Save Settings', 'textdomain' ) );
			?>
		</form>
        <p>Lensmark is a project between Bern University of Applied Sciences & Förderverein Region Gantrisch</p>
	</div>
            <?php
        }

}


?>