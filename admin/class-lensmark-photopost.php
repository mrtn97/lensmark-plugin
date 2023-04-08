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
class Lensmark_Photopost {

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
	 * Add the photopost post type including all settings
	 *
	 * @since    1.0.0
	 */
	public static function lensmark_photopost_post_type() {
		$labels = array(
			'name' => _x( 'Photoposts', 'Post type general name', 'photopost' ),
			'singular_name' => _x( 'Photopost', 'Post type singular name', 'photopost' ),
			'menu_name' => _x( 'Photoposts', 'Admin Menu text', 'photopost' ),
			'name_admin_bar' => _x( 'Photopost', 'Add New on Toolbar', 'photopost' ),
			'add_new' => __( 'Add New', 'photopost' ),
			'add_new_item' => __( 'Add New photopost', 'photopost' ),
			'new_item' => __( 'New photopost', 'photopost' ),
			'edit_item' => __( 'Edit photopost', 'photopost' ),
			'view_item' => __( 'View photopost', 'photopost' ),
			'all_items' => __( 'All photoposts', 'photopost' ),
			'search_items' => __( 'Search photoposts', 'photopost' ),
			'parent_item_colon' => __( 'Parent photoposts:', 'photopost' ),
			'not_found' => __( 'No photoposts found.', 'photopost' ),
			'not_found_in_trash' => __( 'No photoposts found in Trash.', 'photopost' ),
			'featured_image' => _x( 'Photopost Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'photopost' ),
			'set_featured_image' => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'photopost' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'photopost' ),
			'use_featured_image' => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'photopost' ),
			'archives' => _x( 'Photopost archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'photopost' ),
			'insert_into_item' => _x( 'Insert into photopost', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'photopost' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this photopost', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'photopost' ),
			'filter_items_list' => _x( 'Filter photoposts list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'photopost' ),
			'items_list_navigation' => _x( 'Photoposts list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'photopost' ),
			'items_list' => _x( 'Photoposts list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'photopost' ),
		);
		$args = array(
			'labels' => $labels,
			'description' => 'Photopost post type that contains all submitted images per photopost.',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'photopost' ),
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-location',
			'supports' => array( 'title', 'editor', 'author', 'thumbnail' ),
			'taxonomies' => array( 'category', 'post_tag' ),
			'show_in_rest' => true
		);
		register_post_type( 'Photopost', $args );
		// Clear the permalinks after the post type has been registered.
		flush_rewrite_rules();
	}

	/**
	 * Add meta box for photoposts
	 * 
	 * @since	1.0.0
	 */
	public function lensmark_photopost_meta_box() {
		add_meta_box( 'lensmark_photopost_coordinates', 'Photopost coordinates', [ $this, 'lensmark_photopost_coordinates_html' ], 'photopost' );
	}

	/**
	 * Render Meta Box content.
	 *
	 * @since 	1.0.0
	 * @param 	WP_Post $post The post object.
	 */
	public function lensmark_photopost_coordinates_html( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'lensmark_photopost_coordinates', 'lensmark_photopost_coordinates_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$latitude = get_post_meta( $post->ID, 'latitude', true );
		$longitude = get_post_meta( $post->ID, 'longitude', true );

		// Display the form, using the current value.
		?>
		<label for="latitude">Latitude:</label>
		<input type="number" id="latitude" name="latitude" min="-90" max="90" value="<?php echo esc_attr( $latitude ); ?>" />
		<label for="longitude">Longitude:</label>
		<input type="number" id="longitude" name="longitude" min="-180" max="180"
			value="<?php echo esc_attr( $longitude ); ?>" />
		<?php
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @since	1.0.0
	 * @param 	int $post_id The ID of the post being saved.
	 */
	public function lensmark_photopost_save_meta_box_data( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['lensmark_photopost_coordinates_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['lensmark_photopost_coordinates_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'lensmark_photopost_coordinates' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' == $_POST['photopost'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, it's safe for us to save the data now. */

		// Sanitize the user input.
		$latitude_data = sanitize_text_field( $_POST['latitude'] );
		$longitude_data = sanitize_text_field( $_POST['longitude'] );

		// Update the meta field.
		update_post_meta( $post_id, 'latitude', $latitude_data );
		update_post_meta( $post_id, 'longitude', $longitude_data );
	}

}