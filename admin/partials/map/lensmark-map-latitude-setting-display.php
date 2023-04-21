<?php

/**
 * Set the latitude value of the overview map of [lensmark-map-overview]
 *
 *
 * @link       https://lensmark.org/article/map/
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/admin/partials/map
 */

 
// Get the saved value, or use a default value of 46.70476
$latitude = get_option('lensmark_map_latitude', '46.70476');
?>
<input type="text" name="lensmark_map_latitude" value="<?php echo esc_attr($latitude); ?>" />
<p><?php esc_html_e( 'min. -90, max. 90', 'lensmark' );?></p>