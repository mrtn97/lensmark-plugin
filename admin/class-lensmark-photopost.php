<?php

/**
 * Contains the photopost functionalities.
 * 
 * A photo post is a plugin-specific custom post-type that can be supplemented with additional information. Latitude, longitude, location, activation date (as of 1.0.0). In addition, submitted photos are always attached to a photopost.
 * 
 * @link		https://lensmark.org/article/photoposts/
 * @since		1.0.0
 *
 * @package		Lensmark
 * @subpackage	Lensmark/includes
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
	 * Registers the plugin-specific post type " photopost".
	 *
	 * @since	1.0.0
	 * @author	Codex (https://profiles.wordpress.org/codex/)
	 * @source	https://developer.wordpress.org/reference/functions/add_meta_box/
	 * 
	 * Adapted by: Martin Clément <martin.clement@outlook.com>
	 */
	public function lensmark_photopost_post_type() {
		$labels = array(
			'name' => _x( 'Photoposts', 'Post type general name', 'lensmark' ),
			'singular_name' => _x( 'Photopost', 'Post type singular name', 'lensmark' ),
			'menu_name' => _x( 'Photoposts', 'Admin Menu text', 'lensmark' ),
			'name_admin_bar' => _x( 'Photopost', 'Add New on Toolbar', 'lensmark' ),
			'add_new' => __( 'Add New', 'lensmark' ),
			'add_new_item' => __( 'Add New photopost', 'lensmark' ),
			'new_item' => __( 'New photopost', 'lensmark' ),
			'edit_item' => __( 'Edit photopost', 'lensmark' ),
			'view_item' => __( 'View photopost', 'lensmark' ),
			'all_items' => __( 'All photoposts', 'lensmark' ),
			'search_items' => __( 'Search photoposts', 'lensmark' ),
			'parent_item_colon' => __( 'Parent photoposts:', 'lensmark' ),
			'not_found' => __( 'No photoposts found.', 'lensmark' ),
			'not_found_in_trash' => __( 'No photoposts found in Trash.', 'lensmark' ),
			'featured_image' => _x( 'Photopost Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'lensmark' ),
			'set_featured_image' => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'lensmark' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'lensmark' ),
			'use_featured_image' => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'lensmark' ),
			'archives' => _x( 'Photopost archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'lensmark' ),
			'insert_into_item' => _x( 'Insert into photopost', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'lensmark' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this photopost', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'lensmark' ),
			'filter_items_list' => _x( 'Filter photoposts list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'lensmark' ),
			'items_list_navigation' => _x( 'Photoposts list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'lensmark' ),
			'items_list' => _x( 'Photoposts list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'lensmark' ),
		);
		$args = array(
			'labels' => $labels,
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
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'comments' ),
			'taxonomies' => array( 'category', 'post_tag' ), // optional
			'show_in_rest' => true
		);
		register_post_type( 'Photopost', $args );
		// Clear the permalinks after the post type has been registered.
		flush_rewrite_rules();
	}

	/**
	 * Adds photo post details to Metabox
	 * 
	 * @since	1.0.0
	 */
	public function lensmark_photopost_add_details_meta_box() {
		add_meta_box( 'lensmark_photopost_details', __( 'Photopost details', 'lensmark' ), [ $this, 'lensmark_photopost_details_meta_box_callback' ], 'photopost', 'side', 'low' );
	}

	/**
	 * Render Meta Box content.
	 *
	 * @since 	1.0.0
	 * @param 	WP_Post $post The post object.
	 * @author	Codex (https://profiles.wordpress.org/codex/)
	 * @source	https://developer.wordpress.org/reference/functions/add_meta_box/
	 * Adapted by: Martin Clément <martin.clement@outlook.com>
	 */
	public function lensmark_photopost_details_meta_box_callback( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'lensmark_photopost_details', 'lensmark_photopost_details_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$latitude = get_post_meta( $post->ID, 'latitude', true );
		$longitude = get_post_meta( $post->ID, 'longitude', true );
		$location = get_post_meta( $post->ID, 'location', true );
		$activation_date = get_post_meta( $post->ID, 'activation_date', true );

		// Display the form, using the current value.
		?>
		<div class="lensmark-option_container">
			<label for="latitude">
				<?php _e( 'Latitude', 'lensmark' ) ?>:
			</label>
			<input type="number" id="latitude" name="latitude" min="-90" max="90"
				value="<?php echo esc_attr( $latitude ); ?>" />
			<label for="longitude">
				<?php _e( 'Longitude', 'lensmark' ) ?>:
			</label>
			<input type="number" id="longitude" name="longitude" min="-180" max="180"
				value="<?php echo esc_attr( $longitude ); ?>" />
			<label for="location">
				<?php _e( 'Location', 'lensmark' ) ?>:
			</label>
			<input type="text" id="location" name="location" value="<?php echo esc_attr( $location ); ?>" />
			<label for="activation-date">
				<?php _e( 'Activation date', 'lensmark' ) ?>:
			</label>
			<input type="date" id="activation-date" name="activation_date"
				value="<?php echo esc_attr( $activation_date ); ?>" />
		</div>
		<?php
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @since	1.0.0
	 * @param 	int		$post_id	The ID of the post being saved.
	 * 
	 * @author	Codex (https://profiles.wordpress.org/codex/)
	 * @source	https://developer.wordpress.org/reference/functions/add_meta_box/
	 * Adapted by: Martin Clément <martin.clement@outlook.com>
	 */
	public function lensmark_photopost_save_meta_box_data( $post_id ) {
		// Check if our nonce is set (-> security measure).
		if ( ! isset( $_POST['lensmark_photopost_details_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['lensmark_photopost_details_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'lensmark_photopost_details' ) ) {
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
		$location_data = sanitize_text_field( $_POST['location'] );
		$activation_date_data = sanitize_text_field( $_POST['activation_date'] );

		// Update the meta field.
		update_post_meta( $post_id, 'latitude', $latitude_data );
		update_post_meta( $post_id, 'longitude', $longitude_data );
		update_post_meta( $post_id, 'location', $location_data );
		update_post_meta( $post_id, 'activation_date', $activation_date_data );
	}

	/**
	 * Add the shortcode: [lensmark-photopost-details] which displays details from the photopost
	 * 
	 * @since	1.0.0
	 */
	public function lensmark_add_photopost_details_shortcode() {
		add_shortcode( 'lensmark-photopost-details', array( $this, 'lensmark_photopost_details_callback' ) );
	}


	/**
	 * [lensmark-photopost-details] displayed content
	 * 
	 * @since	1.0.0
	 */
	public function lensmark_photopost_details_callback() {
		global $post;
		// Get post meta data
		$latitude = get_post_meta( $post->ID, 'latitude', true );
		$longitude = get_post_meta( $post->ID, 'longitude', true );
		$location = get_post_meta( $post->ID, 'location', true );
		$date_format = get_option( 'date_format' );
		// Format date to use the WordPress general settings
		$activation_date_data = get_post_meta( $post->ID, 'activation_date', true );
		$activation_date = date( $date_format, strtotime( $activation_date_data ) );
		ob_start();
		?>
		<div>
			<p class="has-small-font-size"><strong>
					<span class="dashicons dashicons-post-status"></span>
					<?php _e( 'Location', 'lensmark' ); ?>:
				</strong>
				<?php echo $location ?>
			</p>
			<p class="has-small-font-size"><strong>
					<span class="dashicons dashicons-location-alt"></span>
					<?php _e( 'Position', 'lensmark' ); ?>:
				</strong>
				<?php echo $latitude ?>,
				<?php echo $longitude ?>
			</p>
			<p class="has-small-font-size"><strong>
					<span class="dashicons dashicons-calendar-alt"></span>
					<?php _e( 'Active since', 'lensmark' ); ?>:
				</strong>
				<?php echo $activation_date ?>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}



}