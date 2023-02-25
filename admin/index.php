<?php
/**
 * Plugin Dashboard Page Content
 */
function lnsmrk_dashboard_page() {
    ?>
    <div class="wrap">
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <form action="options.php" method="post">
        <?php
        // output security fields for the registered setting "lnsmrk_options"
        settings_fields( 'lnsmrk_options' );
        // output setting sections and their fields
        // (sections are registered for "lnsmrk", each field is registered to a specific section)
        do_settings_sections( 'lnsmrk' );
        // output save settings button
        submit_button( __( 'Save Settings', 'textdomain' ) );
        ?>
      </form>
    </div>
    <?php
}

?>