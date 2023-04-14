<?php

/**
 * Contains the photodata functionalities.
 * 
 * Contains admin and public facing functionalities --> includes
 *
 * @link       http://wbth.m-clement.ch/
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
	 * Add approval checkbox to photopost attachments.
	 * 
	 * @since	1.0.0
	 */
	public function lensmark_add_photodata_approval_field( $form_fields, $post ) {
		$parent_post_id = get_post_field( 'post_parent', $post->ID );
		if ( $parent_post_id ) {
			$parent_post = get_post( $post->post_parent );
			if ( $parent_post && 'photopost' == $parent_post->post_type ) {
				$approval_field = (bool) get_post_meta( $post->ID, 'approval_field', true );
				$form_fields['approval_field'] = array(
					'label' => 'Approved',
					'input' => 'html',
					'html' => '<input type="checkbox" id="attachments-' . $post->ID . '-approval_field" name="attachments[' . $post->ID . '][approval_field]" value="1"' . ( $approval_field ? ' checked="checked"' : '' ) . ' /> ',
					'value' => $approval_field,
					'helps' => ''
				);
			}
		}
		return $form_fields;
	}


	/**
	 * Save approval checkbox of photopost attachments.
	 * 
	 * @since	1.0.0
	 */
	public function lensmark_save_photodata_approval_field( $post, $attachment ) {
		if ( isset( $attachment['approval_field'] ) ) {
			update_post_meta( $post['ID'], 'approval_field', sanitize_text_field( $attachment['approval_field'] ) );
		} else {
			delete_post_meta( $post['ID'], 'approval_field' );
		}
		return $post;
	}

	/**
	 * Add meta box for photoposts post-type
	 * 
	 * @since	1.0.0
	 */
	public function lensmark_photodata_add_meta_box() {
		add_meta_box( 'lensmark_photodata_list', 'Photopost details', [ $this, 'lensmark_photodata_list_callback' ], 'photopost', 'advanced', 'low' );
	}

	/**
	 * Photodata list meta box content
	 * 
	 * @since 	1.0.0
	 * @param	array	$post			Attachement parent ID
	 */
	public function lensmark_photodata_list_callback( $post ) {
		if ( is_object( $post ) ) {
			$attachments = get_posts( array(
				'post_type'      => 'attachment',
				'posts_per_page' => -1,
				'post_status'    => null,
				'post_parent'    => $post->ID,
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
				$approval_field = get_post_meta( $attachment->ID, 'approval_field', true );
				echo '<label><input type="checkbox" name="attachment_approval[' . $attachment->ID . ']" value="1" ' . checked( $approval_field, true, false ) . ' disabled>Approved? Edit entry to approve/delete this photo</label>';
				echo '<a href="' . $edit_link . '" class="button button-small" target="_blank">' . __( 'Edit Entry', 'textdomain' ) . '</a>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		} else {
			echo '<p>' . __( 'No attachments found.', 'textdomain' ) . '</p>';
		}
	}

}