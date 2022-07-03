<?php

namespace Wptba\Backend;

use JsonException;
use JsonSerializable;

use function PHPSTORM_META\type;

if (!defined('ABSPATH')) exit;

class Posts
{
	private static $globalNamespace = 'Wptba\Backend\Posts';

	public static function activate()
	{
		add_action('wp_ajax_wptbaUploadImage', array(self::$globalNamespace, 'uploadImage'));
		add_action('wp_ajax_wptbaGetAttachment', array(self::$globalNamespace, 'getAttchment'));
		add_action('wp_ajax_wptbaGetAttchmentId', array(self::$globalNamespace, 'getAttchmentId'));
	}


	/**
	 * uploadImage
	 * This function upload image
	 * @return void
	 */
	public static function uploadImage()
	{
		/**
		 * Checking if the nonce is valid
		 */
		if (!wp_verify_nonce($_POST['wptba_backend_nonce'], 'wptba_backend_nonce')) wp_die();


		/**
		 * Only allowing 'image' mime type
		 */
		if (self::checkIfImage(sanitize_text_field($_FILES['logo']['name'])) == false) {
			echo json_encode(0);
			wp_die();
		}

		/**
		 * Uploading the image
		 */
		$uploadStatus = wp_handle_upload($_FILES['logo'], array('test_form' => false), null);

		/**
		 * Checking if there is any error on upload
		 */
		if (array_key_exists('error', $uploadStatus)) {
			echo json_encode(1);
			wp_die();
		}



		/**
		 * Creating attachment for the image to show it in the media
		 */
		$attachment = array(
			'post_mime_type' => $uploadStatus['type'],
			'post_title' => preg_replace('/\.[^.]+$/', '', basename($uploadStatus['file'])),
			'post_content' => '',
			'post_status' => 'inherit',
			'guid' => $uploadStatus['url']
		);
		$attachmentId = wp_insert_attachment($attachment, $uploadStatus['url']);
		wp_update_attachment_metadata($attachmentId, wp_generate_attachment_metadata($attachmentId, $uploadStatus['file']));

		update_option('wptba_logo', intval($attachmentId));
		echo json_encode(wp_get_attachment_thumb_url($attachmentId));

		wp_die();
	}

	/**
	 * checkIfImage
	 * @param  mixed $imgBinary
	 * @return boolean
	 */
	public static function checkIfImage($imgName)
	{
		$type = wp_check_filetype($imgName)['type'];
		if (str_contains($type, 'image')) return true;
		return false;
	}

	/**
	 * getAttchemnt
	 *
	 * @return void
	 */
	public static function getAttchment()
	{
		/**
		 * Checking if the nonce is valid
		 */
		if (!wp_verify_nonce($_POST['wptba_backend_nonce'], 'wptba_backend_nonce')) wp_die();

		if (!$_POST['attchmentId']) {
			echo json_encode(0);
			wp_die();
		}

		$attachmentId =  intval($_POST['attchmentId']);

		$imageUrl = wp_get_attachment_thumb_url($attachmentId);

		echo json_encode($imageUrl);
		wp_die();
	}

	/**
	 * getAttchmentId
	 * 
	 * @return void
	 */
	public static function getAttchmentId()
	{
		/**
		 * Checking if the nonce is valid
		 */
		if (!wp_verify_nonce($_POST['wptba_backend_nonce'], 'wptba_backend_nonce')) wp_die();

		echo json_encode(get_option('wptba_logo'));
		wp_die();
	}
}
