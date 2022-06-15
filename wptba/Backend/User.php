<?php

namespace Wptba\Backend;

class User
{

	private static $globalNamespace = 'Wptba\Backend\User';

	public static function activate()
	{
		add_action('wp_ajax_wptbaGetPendingUsers', array(self::$globalNamespace, 'getPendingUsers'));
		add_action('wp_ajax_wptbaPostToUser', array(self::$globalNamespace, 'postToUser'));
	}

	public static function getPendingUsers()
	{
		if (!wp_verify_nonce($_POST['wptba_backend_nonce'], 'wptba_backend_nonce')) wp_die();

		$pendingUsers = 	get_posts(array(
			'post_type' => 'wp_todo_user',
			'posts_per_page' => -1
		));

		if (empty($pendingUsers)) {
			echo json_encode('null');
			wp_die();
		}

		$pendingUsers = array_map(function ($pendingUser) {
			$pendingUserMeta = get_post_meta($pendingUser->ID, 'wptba_user_post_meta', true);

			return array(
				'id' => $pendingUser->ID,
				'name' => sanitize_text_field($pendingUserMeta['name']),
				'email' => sanitize_email($pendingUserMeta['userEmail'])
			);
		}, $pendingUsers);
		echo json_encode($pendingUsers);
		wp_die();
	}


	/**
	 * postToUser
	 * This to convert a pending user(post:'') to a regular user(real user)
	 * @param string $postID
	 * @return string
	 */
	public static function postToUser()
	{
		/**
		 * Checking if the nonce is valid
		 */
		if (!wp_verify_nonce($_POST['wptba_backend_nonce'], 'wptba_backend_nonce')) wp_die();

		/**
		 * Checking if the post id is provied or not 
		 * We are converting post to user so we need to know the post id
		 */
		$userId = intval($_POST['postId']);
		if (!$userId) wp_die();

		/**
		 * Checking if the post actually exists
		 */
		$userAsPost = get_post($userId);
		if ($userAsPost == null) wp_die();

		/**
		 * getting post meta since we saved user registration details in post meta
		 */
		$postMeta = get_post_meta($userAsPost->ID, 'wptba_user_post_meta', true);
		if (empty($postMeta)) wp_die();

		/**
		 * Extracting post meta and assigning it to vatiable to use in creating user
		 */
		$userName 	= sanitize_text_field($postMeta['userName']);
		$userEmail 	= sanitize_email($postMeta['userEmail']);
		$name 			= sanitize_text_field($postMeta['name']);

		/**
		 * Checking if we have all the required data
		 */
		if (empty($userName) || empty($userEmail) || empty($name)) wp_die();

		/**
		 * Checking if the user already exists or not by its email
		 */
		$user = get_user_by('email', $userEmail);
		if ($user != false) wp_die();
		unset($user);

		/**
		 * Cehcking the user already exists or not by its login name(username)
		 */
		$user = get_user_by('login', $userName);
		if ($user != false) wp_die();
		unset($user);

		/**
		 * Genarating a random password for the user
		 * and storing it in separate variable to use it in the confimation 
		 * Email
		 */
		$password = wp_generate_password();

		/**
		 * Creating User and storing the user ID retured from wp_insert_user,
		 * in a variable. To use it to store user meta
		 */
		$userID = wp_insert_user(array(
			'user_login'	=> $userName,
			'user_email'	=> $userEmail,
			'user_pass'		=> $password,
			'first_name'	=> $name,
			'role'				=> 'todoer'
		));

		/**
		 * Checking if the user creation was successful or not
		 */
		if (gettype($userID) != 'integer') wp_die();

		/**
		 * Adding User Meta
		 */
		add_user_meta($userID, 'wptba_user_meta', serialize(array(
			'bio' => '',
			'dark_mode' => true,
		)));

		/**
		 * Sending email to the user with the password
		 */
		self::sentActivationEmail($userEmail, $userName, $password);

		/**
		 * Deleting the pending user POST and its Meta
		 */
		wp_delete_post($userAsPost->ID, true);
		delete_user_meta($userAsPost->ID, 'wptba_user_post_meta');

		/**
		 * Sending AJAX response as success 
		 */
		echo json_encode('success');

		/**
		 * Stopping the execution of the script
		 */
		wp_die();
	}

	/**
	 * sentActivationEmail
	 * Sending Credentials to User as an Email
	 * @param  string $userEmail
	 * @param  string $userName
	 * @param  string $password
	 * @return void
	 */
	public static function sentActivationEmail($userEmail, $userName, $password)
	{
		wp_mail(
			$userEmail,
			'Login Credentials',
			'Your login credentials are:</br>
			<p>Email: ' . $userEmail . '</p></br>
			<p>Password: ' . $password . '</p>'
		);
	}
}
