<?php

/**
 * Set the longitude value of the overview map of [lensmark-map-overview]
 *
 *
 * @link       http://wbth.m-clement.ch/
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/admin/partials/map
 */

 
// Get the saved value, or use a default value of 7.4506
$longitude = get_option('lensmark_map_longitude', '7.4506');
?>
<input type="text" name="lensmark_map_longitude" value="<?php echo esc_attr($longitude); ?>" />
<p><?php esc_html_e( 'min. -180, max. 180', 'lensmark' );?></p>