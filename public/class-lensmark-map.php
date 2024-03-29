<?php

/**
 * Contains all map functionalities. 
 * 
 * [lensmark-map-overview] is a leaflet.js map which displays all photoposts.
 *
 * @link       https://lensmark.org/article/map/
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/public
 */

class Lensmark_Map {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $lensmark    The ID of this plugin.
	 */
	private $lensmark;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $lensmark       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $lensmark, $version ) {

		$this->lensmark = $lensmark;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets
	 * 
	 * @since	1.0.0
	 */
	public function enqueue_styles() {
		// enqueue dependencies when the shortcode is on the current page
		if ( has_shortcode( get_post()->post_content, 'lensmark-map-overview' ) ) {
			wp_enqueue_style( 'lensmark-map', plugin_dir_url( __FILE__ ) . 'css/lensmark-map.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'leaflet-css', 'https://unpkg.com/leaflet@1.9.3/dist/leaflet.css', array(), '1.9.3', 'all', array( 'integrity' => 'sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=', 'crossorigin' => '' ) );
		}

	}

	/**
	 * Register JavaScript
	 * 
	 * @since	1.0.0
	 */
	public function enqueue_scripts() {
		// enqueue dependencies when the shortcode is on the current page
		if ( has_shortcode( get_post()->post_content, 'lensmark-map-overview' ) ) {
			wp_enqueue_script( 'leaflet-js', 'https://unpkg.com/leaflet@1.9.3/dist/leaflet.js', array(), '1.9.3', true, array( 'integrity' => 'sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=', 'crossorigin' => '' ) );
			wp_enqueue_script( 'lensmark-map', plugin_dir_url( __FILE__ ) . 'js/lensmark-map-overview.js', array( 'jquery', 'leaflet-js' ), $this->version, false );
			wp_localize_script( 'lensmark-map', 'lensmark_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), ) );
			wp_enqueue_script( 'leaflet-sleep', plugin_dir_url( __FILE__ ) . 'js/Leaflet.Sleep.js', array( 'leaflet-js' ), $this->version, false );
		}
	}

	/**
	 * Map overview shortcode content
	 * 
	 * @since	1.0.0
	 * @author  Codex (codex.wordpress.org)
	 * @source	https://codex.wordpress.org/Shortcode_API
	 * @param 	array 		$atts 		User defined attributes	
	 */
	public function lensmark_map_overview_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'width' => '100%', // default width
			'height' => '800px', // default height
		), $atts ) );
		ob_start();
		?>
		<div id="map" style="width: <?php echo $width ?>; height: <?php echo $height ?>"></div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Retrieve photopost metadata for JavaScript usage.
	 * 
	 * Sends attributes of photoposts via wp_send_json() to lensmark-map-overview.js
	 * 
	 * @since	1.0.0
	 * @source  ChatGPT (https://chat.openai.com/)
	 * Adapted by: Martin Clément <martin.clement@outlook.com>
	 */
	public function lensmark_get_photoposts() {
		// get map settings
		$map_pos_latitude = get_option( 'lensmark_map_latitude', );
		$map_pos_longitude = get_option( 'lensmark_map_longitude', );
		$map_zoom = get_option( 'lensmark_map_zoom' );
		// set map pin content
		$activation_date_label = __('Active since', 'lensmark');
		$id_label = __('Photopost', 'lensmark');
		$location_label = __('Location', 'lensmark');
		$position_label = __('Position', 'lensmark');
		$open_button_label = __('Open', 'lensmark');
		// get photopost specific data
		$args = array(
			'post_type' => 'photopost',
			'posts_per_page' => -1,
		);
		$posts = get_posts( $args );
		$result = array();
		foreach ( $posts as $post ) {
			// post specific data
			$id = $post->ID;
			$title = $post->post_title;
			$excerpt = $post->post_excerpt;
			$decimal_points = get_option('lensmark_map_decimal_points',);
			$latitude = get_post_meta( $post->ID, 'latitude', true );
			$latitude_formatted = number_format($latitude, $decimal_points);
			$longitude = get_post_meta( $post->ID, 'longitude', true );
			$longitude_formatted = number_format($longitude, $decimal_points);
			$location = get_post_meta( $id, 'location', true );
			$date_format = get_option( 'date_format' );
			// Format date to use the WordPress general settings
			$activation_date_data = get_post_meta( $post->ID, 'activation_date', true );
			$activation_date = date( $date_format, strtotime( $activation_date_data ) );
			$link = get_permalink( $id );
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );
			$thumbnail_url = $thumbnail ? $thumbnail[0] : '';

			if ( $latitude && $longitude ) {
				$result[] = array(
					// map settings
					'map_pos_latitude' => $map_pos_latitude,
					'map_pos_longitude' => $map_pos_longitude,
					'map_zoom' => $map_zoom,
					// photopost data labels
					'activation_date_label' => $activation_date_label,
					'id_label' => $id_label,
					'location_label' => $location_label,
					'position_label' => $position_label,
					'open_button_label' => $open_button_label,
					// photopost data
					'id' => $id,
					'title' => $title,
					'excerpt' => $excerpt,
					'latitude' => $latitude,
					'latitude_formatted' => $latitude_formatted,
					'longitude' => $longitude,
					'longitude_formatted' => $longitude_formatted,
					'location' => $location,
					'activation_date' => $activation_date,
					'link' => $link,
					'thumbnail_url' => $thumbnail_url,
				);
			}
		}
		wp_send_json( $result );
	}
}