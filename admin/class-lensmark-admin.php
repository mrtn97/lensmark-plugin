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
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->lensmark, plugin_dir_url( __FILE__ ) . 'js/lensmark-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Include partials ressources
	 * 
	 * @since	1.0.0
	 */
	public function lensmark_include_admin_display() {
		include_once 'partials/lensmark-admin-display.php';
	}

	/**
	 * Add admin menu
	 *
	 * @since    1.0.0
	 */

	public static function lensmark_add_menu_page() {
		add_submenu_page(
			'edit.php?post_type=photopost',
			'Settings',
			'Settings',
			'manage_options',
			plugin_dir_path( __FILE__ ) . 'admin/partials/lensmark-admin-display.php',
			array( 'Lensmark_Admin_Display', 'lensmark_settings_page_html' )
		);
	}

	/**
	 * Setup lensmark plugin settings page.
	 *
	 * @since    1.0.0
	 */
	public function lensmark_settings_init() {
		// Register a new setting for "wporg" page.
		register_setting( 'lensmark_settings', 'lensmark_map_latitude' );
    	register_setting( 'lensmark_settings', 'lensmark_map_longitude' );
		register_setting( 'lensmark_settings', 'lensmark_map_zoom' );

		// Add map settings section
		add_settings_section(
			'lensmark_map_section', // Section ID
			'Map overview position', // Section title
			array( $this, 'lensmark_map_section_content' ), // Callback function to display the section description
			'lensmark_settings' // page settings
		);

		// Add map overview position LATITUDE
		add_settings_field(
			'lensmark_map_latitude', // Field ID
			'Latitude', // Field label
			array( $this,'lensmark_map_latitude_field'), // Callback function to display the field
			'lensmark_settings', // Page slug
			'lensmark_map_section', // Section ID
			array( 'label_for' => 'lensmark_map_latitude' ) // Additional field attributes
		);

		// Add map overview position LONGITUDE
		add_settings_field(
			'lensmark_map_longitude', // Field ID
			'Longitude', // Field label
			array( $this,'lensmark_map_longitude_field'), // Callback function to display the field
			'lensmark_settings', // Page slug
			'lensmark_map_section', // Section ID
			array( 'label_for' => 'lensmark_map_longitude' ) // Additional field attributes
		);

		// Add map overview ZOOM LEVEL
		add_settings_field(
			'lensmark_map_zoom', // Field ID
			'Zoom Level', // Field label
			array( $this,'lensmark_map_zoom_field'), // Callback function to display the field
			'lensmark_settings', // Page slug
			'lensmark_map_section', // Section ID
			array( 'label_for' => 'lensmark_map_zoom' ) // Additional field attributes
		);
	}


	/**
	 * Map section callback function.
	 *
	 * @param 	array $args  The settings array, defining title, id, callback.
	 * @since	1.0.0
	 */
	function lensmark_map_section_content( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Enter the desired position of the map overview element displaying all photoposts', 'lensmark' ); ?></p>
		<?php
	}

		/**
	 * Latitude field callback function
	 *
	 * @since	1.0.0
	 */
	function lensmark_map_latitude_field() {
		// Get the saved value, or use a default value of 46.70476
		$latitude = get_option('lensmark_map_latitude', '46.70476');
  
		// Output the field
		?>
    		<input type="text" name="lensmark_map_latitude" value="<?php echo esc_attr($latitude); ?>" />
			<p>Value Between: -90 and 90</p>
    	<?php
	}

	/**
	 * Longitude field callback function
	 *
	 * @since	1.0.0
	 */
	function lensmark_map_longitude_field() {
		// Get the saved value, or use a default value of 7.4506
		$longitude = get_option('lensmark_map_longitude', '7.4506');
  
		// Output the field
		?>
   		<input type="text" name="lensmark_map_longitude" value="<?php echo esc_attr($longitude); ?>" />
		<p>Value Between: -180 and 180</p>
    	<?php
	}

	/**
	 * Zoom field callback function
	 *
	 * @since	1.0.0
	 */
	function lensmark_map_zoom_field() {
		// Get the saved value, or use a default value of 12
		$zoom = get_option('lensmark_map_zoom', '12');
  
		// Output the field
		?>
   		<input type="text" name="lensmark_map_zoom" value="<?php echo esc_attr($zoom); ?>" />
		<p>Default: 12</p>
    	<?php
	}


}