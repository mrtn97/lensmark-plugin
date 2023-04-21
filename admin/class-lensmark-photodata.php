<?php

/**
 * Contains the photodata functionalities.
 * 
 * Contains admin and public facing functionalities --> includes
 *
 * @link       https://lensmark.org/article/photodata/
 * @since      1.0.0
 *
 * @package    Lensmark
 * @subpackage Lensmark/includes
 */
class Lensmark_Photodata {

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
	 * Add verification checkbox to photopost attachments.
	 * 
	 * @since	1.0.0
	 * @author	ChatGPT (https://chat.openai.com/)
	 * Adapted by: Martin Clément <martin.clement@outlook.com>
	 */
	public function lensmark_add_photodata_verification_field( $form_fields, $post ) {
		$parent_post_id = get_post_field( 'post_parent', $post->ID );
		if ( $parent_post_id ) {
			$parent_post = get_post( $post->post_parent );
			if ( $parent_post && 'photopost' == $parent_post->post_type ) {
				$verification_field = (bool) get_post_meta( $post->ID, 'verification_field', true );
				$form_fields['verification_field'] = array(
					'label' => __( 'Verify', 'lensmark' ),
					'input' => 'html',
					'html' => '<input type="checkbox" id="attachments-' . $post->ID . '-verification_field" name="attachments[' . $post->ID . '][verification_field]" value="1"' . ( $verification_field ? ' checked="checked"' : '' ) . ' /> ',
					'value' => $verification_field,
					'helps' => _e( 'Only verified photos are displayed in time lapse.', 'lensmark' )
				);
			}
		}
		return $form_fields;
	}


	/**
	 * Save verification checkbox status of photopost attachments.
	 * 
	 * @since	1.0.0
	 * @author	ChatGPT (https://chat.openai.com/)
	 */
	public function lensmark_save_photodata_verification_field( $post, $attachment ) {
		if ( isset( $attachment['verification_field'] ) ) {
			update_post_meta( $post['ID'], 'verification_field', sanitize_text_field( $attachment['verification_field'] ) );
		} else {
			delete_post_meta( $post['ID'], 'verification_field' );
		}
		return $post;
	}

	/**
	 * Add meta box listing all submitted photos.
	 * 
	 * @since	1.0.0
	 */
	public function lensmark_photodata_add_meta_box() {
		add_meta_box( 'lensmark_photodata_list', __( 'Photopost details', 'lensmark' ), [ $this, 'lensmark_photodata_list_callback' ], 'photopost', 'advanced', 'low' );
	}

	/**
	 * Photodata list meta box content
	 * 
	 * @since 	1.0.0
	 * @param	array	$post			Attachement parent ID
	 * @author	ChatGPT (https://chat.openai.com/)
	 * Adapted by: Martin Clément <martin.clement@outlook.com>
	 */
	public function lensmark_photodata_list_callback( $post ) {
		if ( is_object( $post ) ) {
			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'posts_per_page' => -1,
				'post_status' => null,
				'post_parent' => $post->ID,
			) );
		}

		if ( $attachments ) {
			echo '<div class="attachment-list">';
			foreach ( $attachments as $attachment ) {
				$edit_link = get_edit_post_link( $attachment->ID );
				echo '<div class="attachment-item">';
				echo '<div class="attachment-thumbnail">';
				echo '<a href="' . $edit_link . '" target="_blank">';
				echo wp_get_attachment_image( $attachment->ID, 'thumbnail' );
				echo '</a>';
				echo '</div>';
				echo '<div class="attachment-info">';
				echo '<h4>' . $attachment->post_title . '</h4>';
				$verification_field = get_post_meta( $attachment->ID, 'verification_field', true );
				echo '<label><input type="checkbox" name="attachment_verification[' . $attachment->ID . ']" value="1" ' . checked( $verification_field, true, false ) . ' disabled>' . __( 'Verified', 'lensmark' ) . '</label>';
				echo '<a href="' . $edit_link . '" class="button button-small" target="_blank">' . __( 'Edit Entry', 'lensmark' ) . '</a>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		} else {
			echo '<p>' . _e( 'No attachments found.', 'lensmark' ) . '</p>';
		}
	}

}