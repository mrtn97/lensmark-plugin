<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wbth.m-clement.ch/
 * @since      0.1.0
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
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $lensmark    The ID of this plugin.
	 */
	private $lensmark;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
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
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lensmark_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lensmark_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->lensmark, plugin_dir_url( __FILE__ ) . 'css/lensmark-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lensmark_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lensmark_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->lensmark, plugin_dir_url( __FILE__ ) . 'js/lensmark-admin.js', array( 'jquery' ), $this->version, false );

	}

}