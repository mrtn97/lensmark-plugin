<?php
/**
 * LENSMARK
 *
 * @package           LensmarkPackage
 * @author            Martin Clément
 * @copyright         2023 Berner Fachhochschule
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Lensmark
 * Plugin URI:        https://wbth.m-clement.ch/
 * Description:       Photomonitoring Plugin for Wordpress
 * Version:           0.0.1
 * Requires at least: 6.1.1
 * Requires PHP:      7.2
 * Author:            Martin Clément
 * Author URI:        https://m-clement.ch/
 * Text Domain:       lensmark
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 */


/**
 * Register the "photopost" custom post type
 */
function lnsmrk_setup_post_type() {
	register_post_type( 'photopost', [ 'public' => true ] );
}
add_action( 'init', 'lnsmrk_setup_post_type' );


/**
 * Activate the plugin.
 */
function lnsmrk_activate() {
	// Trigger our function that registers the custom post type plugin.
	lnsmrk_setup_post_type();
	// Clear the permalinks after the post type has been registered.
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'lnsmrk_activate' );


/**
 * Add Plugin Dashboard Page
 */
add_action( 'admin_menu', 'lnsmrk_dashboard_page' );
function lnsmrk_dashboard_page() {
	add_menu_page( 'Lensmark', 'Lensmark', 'manage_options', plugin_dir_path( __FILE__ ) . 'admin/index.php', 'lnsmrk_dashboard_page_html', 'dashicons-camera', 20 );
}

/**
 * Add Plugin Settings Page
 */
add_action( 'admin_menu', 'lnsmrk_options_page' );
function lnsmrk_options_page() {
	add_submenu_page( plugin_dir_path( __FILE__ ) . 'admin/index.php', 'Settings', 'Settings', 'manage_options', plugin_dir_path( __FILE__ ) . 'admin/settings.php', 'lnsmrk_options_page_html' );
}

/**
 * Register Photopost post type
 */
function lnsmrk_photopost_post_type() {
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
		'description' => 'Photopost custom post type.',
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
		'supports' => array( 'title', 'editor', 'author', 'thumbnail' ),
		'taxonomies' => array( 'category', 'post_tag' ),
		'show_in_rest' => true
	);

	register_post_type( 'Photopost', $args );
}
add_action( 'init', 'lnsmrk_photopost_post_type' );

/**
 * Add Photoposts meta boxes
 */
function lnsmrk_add_properties_box() {
		add_meta_box(
			'lnsmrk_properties_box',
			'Photopost properties',
			'lnsmrk_properties_box_html',
			'photopost',
			'normal',
		);
}
add_action( 'add_meta_boxes', 'lnsmrk_add_properties_box' );

/**
 * Properties Metabox
 */
function lnsmrk_properties_box_html( $post ) {
	?>
	<label for="wporg_field">Description for this field</label>
	<select name="wporg_field" id="wporg_field">
		<option value="">Select something...</option>
		<option value="something">Something</option>
		<option value="else">Else</option>
	</select>
	<?php
}

?>