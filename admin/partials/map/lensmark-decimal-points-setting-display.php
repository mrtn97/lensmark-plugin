<?php

/**
 * Set the zoom value of the overview map of [lensmark-map-overview]
 *
 *
 * @link       https://lensmark.org/article/map/
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/admin/partials/map
 */

 
// Get the saved value, or use a default value of 4
$decimal_points = get_option('lensmark_map_decimal_points', '4');
?>
<input type="text" name="lensmark_map_decimal_points" value="<?php echo esc_attr($decimal_points); ?>" />
<p><?php esc_html_e( 'Default: 4', 'lensmark' );?></p>