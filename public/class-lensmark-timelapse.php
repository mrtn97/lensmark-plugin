<?php

/**
 * Contains all submission-form functionalities.
 *
 * @link       https://wbth.m-clement.ch
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/public
 */

class Lensmark_Timelapse {

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
	 * Register styles for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

    public function enqueue_styles() {
        // enqueue dependencies when the shortcode is on the current page
        if ( get_post_type( get_the_ID() ) == 'photopost' ) {
		    wp_enqueue_style( 'lensmark-timelapse', plugin_dir_url( __FILE__ ) . 'css/lensmark-timelapse.css', array(), $this->version, 'all' );
        }
	}

    /**
	 * Register the JavaScripts
	 *
	 * @since    1.0.0
	 */

	public function enqueue_scripts() {
		// enqueue dependencies for photopost post type front-end
		if ( get_post_type( get_the_ID() ) == 'photopost' ) {
			wp_enqueue_script( 'lensmark-timelapse', plugin_dir_url( __FILE__ ) . 'js/lensmark-timelapse.js', array(), $this->version, false );
		}
	}

    /**
	 * Add new shortcode that displays the timelapse
	 * 
	 * @since    1.0.0
	 */
	public static function lensmark_add_timelapse_shortcode() {
		add_shortcode( 'lensmark-timelapse', 'lensmark_timelapse_html' );
		function lensmark_timelapse_html( $atts ) {
			// Get post ID
			global $post;
			$post_id = $post->ID;

			// Get attachments for the post
			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'posts_per_page' => -1,
				'post_parent' => $post_id,
				'exclude' => get_post_thumbnail_id(), //optional
				'post_mime_type' => 'image',
				'orderby' => 'date',
				'order' => 'ASC'
			) );
			ob_start();
            ?>
			<div class="timelapse-container">
            <?php
			foreach ( $attachments as $attachment ) {
				$image = wp_get_attachment_image_src( $attachment->ID, 'full' )[0];
				$date = get_the_date( 'd-m-Y H:i:s', $attachment->ID );
                ?>
				<div class="timelapse-image-container" >
				<img class="timelapse-image" data-date="<?php echo $date ?>" src="<?php echo $image ?>" />
				</div>
                <?php
			}
            ?>
			</div>
			<div class="timelapse-controls">
			<div>
			<button id="play-btn"><span class="dashicons dashicons-controls-play"></span></button>
			<button id="pause-btn"><span class="dashicons dashicons-controls-pause"></span></button>
			<button id="prev-btn"><span class="dashicons dashicons-controls-skipback"></span></button>
			<button id="next-btn"><span class="dashicons dashicons-controls-skipforward"></span></button>
			</div>
			<div id="date-text"></div>
			</div>
            <?php
            return ob_get_clean();
		}
	}
}