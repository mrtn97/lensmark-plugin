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


if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

//Get the active tab from the $_GET param
$default_tab = null;
$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $default_tab;
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<h2 class="nav-tab-wrapper">
		<?php
			$tabs = array(
				'map' => __('Map settings', 'lensmark'),
				'help' => __('Help', 'lensmark'),
			);
			//set current tab
			$tab = ( isset($_GET['tab']) ? $_GET['tab'] : 'map' );
			?>
			<?php foreach( $tabs as $key => $value ): ?>
				<a class="nav-tab <?php if( $tab == $key ){ echo 'nav-tab-active'; } ?>" href="<?php echo admin_url() ?>edit.php?post_type=photopost&page=lensmark-settings&tab=<?php echo $key; ?>"><?php echo $value; ?></a>
		<?php endforeach; ?>
	</h2>

	<div class="tab-content">
		<?php if( $tab == 'map' ): ?>
				
			<?php flush_rewrite_rules(); ?>
			<form method="post" action="options.php">
				<?php settings_fields('lensmark-map-settings'); ?>
				<?php do_settings_sections('lensmark-map-settings'); ?>
				<?php submit_button('Save'); ?>
			</form>

		<?php elseif( $tab == 'help' ): ?>

			<?php include plugin_dir_path( dirname( __FILE__ ) ) . '/partials/help/lensmark-help-section-display.php'; ?>

		<?php endif; ?>
	</div>
</div>
<p>Lensmark is a project between Bern University of Applied Sciences & Förderverein Region Gantrisch</p>