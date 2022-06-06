<?php

namespace Wptba\Frontend;

if (!defined('ABSPATH')) exit;

use Wptba\Common\Auth;
use Wptba\Common\Officer;


class User
{
	private static $globalNamespace = 'Wptba\Frontend\User';

	public static function do()
	{
		add_action('wp_ajax_nopriv_wptbaLogin', array(self::$globalNamespace, 'login'));
		add_action('wp_ajax_wptbaLogin', array(self::$globalNamespace, 'login'));

		add_action('wp_ajax_nopriv_wptbaGetUserDetails', array(self::$globalNamespace, 'getUserDetails'));
		add_action('wp_ajax_wptbaGetUserDetails', array(self::$globalNamespace, 'getUserDetails'));

		add_action('wp_ajax_nopriv_wptbaUploadDarkMode', array(self::$globalNamespace, 'wptbaUploadDarkMode'));
		add_action('wp_ajax_wptbaUploadDarkMode', array(self::$globalNamespace, 'wptbaUploadDarkMode'));

		add_action('wp_ajax_nopriv_wptbaDownloadDarkMode', array(self::$globalNamespace, 'wptbaDownloadDarkMode'));
		add_action('wp_ajax_wptbaDownloadDarkMode', array(self::$globalNamespace, 'wptbaDownloadDarkMode'));

		add_action('wp_ajax_nopriv_wptbaRegister', array(self::$globalNamespace, 'register'));
		add_action('wp_ajax_wptbaRegister', array(self::$globalNamespace, 'register'));
	}

	/**
	 * login
	 * responsible user login
	 * @return json
	 */
	public static function login()
	{
		/**
		 * Verifying Nonce
		 */
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) wp_die();

		/**
		 * Sanitizing user entered email
		 */
		$todoer_email = sanitize_email($_POST['email']);
		if ($todoer_email == '') wp_die();

		/**
		 * Sanitizing the user entered password
		 */
		$todoer_password = sanitize_text_field($_POST['password']);
		if ($todoer_password == '') wp_die();

		/**
		 * If User Not Found Then send user404
		 */
		$user = get_user_by('email', $todoer_email);
		if (!$user) {
			/**
			 * do not send exact reason of failure
			 */
			echo json_encode(0);
			wp_die();
		}

		/**
		 * Do one thing and one thing properly.
		 * User/author can't have multiple roles.
		 */
		if (count($user->roles) > 1) wp_die();
		if (!in_array('todoer', $user->roles)) wp_die();

		/**
		 * If User Found Then Check Password
		 */
		if (wp_check_password($todoer_password, $user->data->user_pass, $user->ID) === false) {
			/**
			 * do not send exact reason of failure.
			 */
			echo json_encode(0);

			wp_die();
		}

		/**
		 * If Password is Correct Then Create JWT Token
		 */

		if (get_option('wptba_encryption_key', null) != null) {
			$key = sanitize_text_field(get_option('wptba_encryption_key'));
		} else {
			wp_die();
		}

		if (get_option('wptba_autoLogOutDuration', null) != null) {
			$alo = (int)intval(get_option('wptba_autoLogOutDuration'));
		} else {
			wp_die();
		}



		$authObject = new Auth($key, $alo);
		$authObject->setData(array('ID' => $user->ID));
		$token = $authObject->encode();

		echo json_encode($token);
		//echo json_encode($authObject->decode($token, $key));
		wp_die();



		echo json_encode($user);
		wp_die();
	}

	/**
	 * getUserDetails
	 * Provides user Details
	 * @return void
	 */
	public static function getUserDetails()
	{
		/**
		 * Verifying Nonce
		 */
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) wp_die();


		$userID = Officer::validateRequest($_POST);
		if (gettype($userID) != 'integer') {
			echo json_encode(0);
			wp_die();
		}

		$user = get_user_by('ID', $userID);

		$user_meta = get_user_meta($userID, 'wptba_user_meta', true);
		if (!$user_meta) {
			/**
			 * incase user meta is not set
			 * Then manually sending nulled data
			 */
			$user_meta = array();
			$user_meta['bio'] = '';
			$user_meta['dark_mode'] = false;
		} else {
			/**
			 * incase user meta is set
			 * Then sanitizing the each key of user meta array
			 */
			$user_meta = unserialize($user_meta);
			array_key_exists('bio', $user_meta) ? $user_meta['bio'] =  sanitize_text_field($user_meta['bio']) : $user_meta['bio'] = '';
			array_key_exists('dark_mode', $user_meta) ? $user_meta['dark_mode'] = rest_sanitize_boolean($user_meta['dark_mode'])  : $user_meta['dark_mode'] = false;
		}



		$user_details = array();
		array_push(
			$user_details,
			array(
				'displayName' => $user->data->display_name,
				'niceName' => $user->data->user_nicename,
				'bio' => $user_meta['bio'],
				'darkMode' => $user_meta['dark_mode'],
			)
		);

		echo json_encode($user_details);
		wp_die();
	}

	/**
	 * wptbaUploadDarkMode
	 * Uploads Dark Mode data
	 * @return void
	 */
	public static function wptbaUploadDarkMode()
	{
		/**
		 * Verifying Nonce
		 */
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) wp_die();

		$userID = Officer::validateRequest($_POST);
		if (gettype($userID) != 'integer') {
			echo json_encode(0);
			wp_die();
		}

		$user_meta = get_user_meta($userID, 'wptba_user_meta', true);
		if (!$user_meta) {
			$user_meta = array();
			$user_meta['dark_mode'] = true;
			$update_status = update_user_meta($userID, 'wptba_user_meta', serialize($user_meta));

			echo json_encode('Created');
			wp_die();
		}

		$user_meta = unserialize($user_meta);
		if (array_key_exists('dark_mode', $user_meta)) {
			if (rest_sanitize_boolean($user_meta['dark_mode'])  === true) {
				$user_meta['dark_mode'] = rest_sanitize_boolean(false);
			} else {
				$user_meta['dark_mode'] = rest_sanitize_boolean(true);
			}
		} else {
			$user_meta['dark_mode'] = rest_sanitize_boolean(true);
		}

		$update_status = update_user_meta($userID, 'wptba_user_meta', serialize($user_meta));
		echo json_encode('Updated');
		wp_die();
	}

	/**
	 * wptbaDownloadDarkMode
	 * Sent Dark Mode data
	 * @return string
	 */
	public static function wptbaDownloadDarkMode()
	{
		/**
		 * Verifying Nonce
		 */
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) wp_die();

		$userID = Officer::validateRequest($_POST);
		if (gettype($userID) != 'integer') {
			echo json_encode(0);
			wp_die();
		}

		$user_meta = get_user_meta($userID, 'wptba_user_meta', true);

		if (!$user_meta) {

			echo json_encode(false);
			wp_die();
		} else {

			$user_meta = unserialize($user_meta);
			if (array_key_exists('dark_mode', $user_meta)) {
				$user_meta['dark_mode'] = rest_sanitize_boolean($user_meta['dark_mode']);
			} else {
				$user_meta['dark_mode'] = false;
			}


			echo json_encode($user_meta['dark_mode']);
			wp_die();
		}
	}



	//TODO: register
	public static function register()
	{
		/**
		 * Verifying Nonce
		 */
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) wp_die();
	}
}
