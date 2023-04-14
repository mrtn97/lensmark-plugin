<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wbth.m-clement.ch/
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lensmark
 * @subpackage Lensmark/admin
 * @author     Martin Clément <martin.clement@outlook.com>
 */
class Lensmark_Admin {

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
	 * @param      string    $lensmark       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $lensmark, $version ) {

		$this->lensmark = $lensmark;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->lensmark, plugin_dir_url( __FILE__ ) . 'css/lensmark-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Setup lensmark plugin settings page.
	 *
	 * @since    1.0.0
	 */
	public function lensmark_settings_init() {
		/**
		 * Add 'Settings' submenu page
		 */
		add_submenu_page(
			'edit.php?post_type=photopost',
			'Settings',
			'Settings',
			'edit_posts',
			'lensmark-settings',
			array( $this, 'lensmark_admin_interface_callback' )
		);

		/**
		 * Add plugin settings sections
		 */

		// Add map settings section
		add_settings_section(
			'lensmark_map_section', // Section ID
			'Map Overview', // Section title
			array( $this, 'lensmark_map_section_callback' ), // Callback function to display the section description
			'lensmark-map-settings' // page settings
		);

		/**
		 * Register map settings
		 */
		register_setting( 'lensmark-map-settings', 'lensmark_map_latitude' ); // LATITUDE
		register_setting( 'lensmark-map-settings', 'lensmark_map_longitude' ); // LONGITUDE
		register_setting( 'lensmark-map-settings', 'lensmark_map_zoom' ); // ZOOM LEVEL


		/**
		 * Add map settings
		 */
		// Add map overview position LATITUDE
		add_settings_field(
			'lensmark_map_latitude', // Field ID
			'Latitude', // Field label
			array( $this, 'lensmark_map_latitude_setting_callback' ), // Callback function to display the field
			'lensmark-map-settings', // Page slug
			'lensmark_map_section', // Section ID
		);

		// Add map overview position LONGITUDE
		add_settings_field(
			'lensmark_map_longitude', // Field ID
			'Longitude', // Field label
			array( $this, 'lensmark_map_longitude_setting_callback' ), // Callback function to display the field
			'lensmark-map-settings', // Page slug
			'lensmark_map_section', // Section ID
		);

		// Add map overview ZOOM LEVEL
		add_settings_field(
			'lensmark_map_zoom', // Field ID
			'Zoom Level', // Field label
			array( $this, 'lensmark_map_zoom_setting_callback' ), // Callback function to display the field
			'lensmark-map-settings', // Page slug
			'lensmark_map_section', // Section ID
		);
	}

	/**
	 * Plugin Settings interface callback
	 * 
	 * @since	1.0.0
	 */
	public function lensmark_admin_interface_callback() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/lensmark-admin-display.php';
	}


	/**
	 * Map section callback function.
	 *
	 * @since	1.0.0
	 */
	public function lensmark_map_section_callback() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/map/lensmark-map-section-display.php';
	}

	/**
	 * Help section callback function.
	 *
	 * @since	1.0.0
	 */
	public function lensmark_help_section_callback() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/help/lensmark-help-section-display.php';
	}

	/**
	 * Latitude field callback function
	 *
	 * @since	1.0.0
	 */
	public function lensmark_map_latitude_setting_callback() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/map/lensmark-map-latitude-setting-display.php';
	}

	/**
	 * Longitude field callback function
	 *
	 * @since	1.0.0
	 */
	public function lensmark_map_longitude_setting_callback() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/map/lensmark-map-longitude-setting-display.php';
	}

	/**
	 * Zoom field callback function
	 *
	 * @since	1.0.0
	 */
	public function lensmark_map_zoom_setting_callback() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/map/lensmark-map-zoom-setting-display.php';
	}


}