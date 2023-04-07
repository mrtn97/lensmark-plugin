<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wbth.m-clement.ch/
 * @since      1.0.0
 * @package    Lensmark
 * @subpackage Lensmark/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Lensmark
 * @subpackage Lensmark/includes
 * @author     Martin Clément <martin.clement@outlook.com>
 */

class Lensmark {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Lensmark_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $lensmark    The string used to uniquely identify this plugin.
	 */
	protected $lensmark;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LENSMARK_VERSION' ) ) {
			$this->version = LENSMARK_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->lensmark = 'lensmark';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Lensmark_Loader. Orchestrates the hooks of the plugin.
	 * - Lensmark_i18n. Defines internationalization functionality.
	 * - Lensmark_Admin. Defines all hooks for the admin area.
	 * - Lensmark_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lensmark-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lensmark-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lensmark-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-lensmark-public.php';

		/**
		 * The class responsible for actions regarding the photopost post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lensmark-photopost.php';

		$this->loader = new Lensmark_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Lensmark_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Lensmark_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Lensmark_Admin( $this->get_lensmark(), $this->get_version() );
		$photopost = new Lensmark_Photopost( $this->get_lensmark(), $this->get_version());

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Load settings content
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'lensmark_add_menu_page');
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'lensmark_settings_init');
		// Load post-type content
		$this->loader->add_action( 'init', $photopost, 'lensmark_photopost_post_type');
		$this->loader->add_action( 'add_meta_boxes', $photopost, 'lensmark_photopost_meta_box');
		$this->loader->add_action( 'save_post', $photopost, 'lensmark_photopost_save_meta_box_data');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Lensmark_Public( $this->get_lensmark(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		// Load submission form content
		$this->loader->add_action( 'init', $plugin_public, 'lensmark_add_submission_form_shortcode');
		$this->loader->add_action( 'init', $plugin_public, 'lensmark_submit_entry');
		$this->loader->add_action( 'activated_plugin', $plugin_public, 'lensmark_add_submission_form_page');
		$this->loader->add_action( 'deactivated_plugin', $plugin_public, 'lensmark_trash_submission_form_page');
		// Load map content
		$this->loader->add_action( 'init', $plugin_public, 'lensmark_add_map_overview_shortcode');
		$this->loader->add_action( 'wp_ajax_lensmark_get_photoposts', $plugin_public, 'lensmark_get_photoposts' );
		$this->loader->add_action( 'wp_ajax_nopriv_lensmark_get_photoposts', $plugin_public, 'lensmark_get_photoposts' );
		// Load timelapse content
		$this->loader->add_action( 'init', $plugin_public, 'lensmark_add_timelapse_shortcode');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_lensmark() {
		return $this->lensmark;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Lensmark_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}