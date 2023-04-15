<?php

/**
 * Set the zoom value of the overview map of [lensmark-map-overview]
 *
 *
 * @link       http://wbth.m-clement.ch/
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/admin/partials/map
 */

 
// Get the saved value, or use a default value of 12
$zoom = get_option('lensmark_map_zoom', '12');
?>
<input type="text" name="lensmark_map_zoom" value="<?php echo esc_attr($zoom); ?>" />
<p><?php esc_html_e( 'Default: 12', 'lensmark' );?></p>