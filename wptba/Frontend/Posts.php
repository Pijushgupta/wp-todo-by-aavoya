<?php

namespace Wptba\Frontend;

if (!defined('ABSPATH')) exit;

use Wptba\Common\Officer;

class Posts
{
	private static $globalNamespace = 'Wptba\Frontend\Posts';
	public static function do()
	{
		add_action('wp_ajax_nopriv_wptbaGetPosts', array(self::$globalNamespace, 'getPosts'));
		add_action('wp_ajax_wptbaGetPosts', array(self::$globalNamespace, 'getPosts'));

		add_action('wp_ajax_nopriv_wptbaAddPost', array(self::$globalNamespace, 'addPost'));
		add_action('wp_ajax_wptbaAddPost', array(self::$globalNamespace, 'addPost'));

		add_action('wp_ajax_nopriv_wptbaGetPostMeta', array(self::$globalNamespace, 'getPostMeta'));
		add_action('wp_ajax_wptbaGetPostMeta', array(self::$globalNamespace, 'getPostMeta'));

		add_action('wp_ajax_nopriv_wptbaSetPostMeta', array(self::$globalNamespace, 'setPostMeta'));
		add_action('wp_ajax_wptbaSetPostMeta', array(self::$globalNamespace, 'setPostMeta'));

		add_action('wp_ajax_nopriv_wptbaDeletePost', array(self::$globalNamespace, 'deletePost'));
		add_action('wp_ajax_wptbaDeletePost', array(self::$globalNamespace, 'deletePost'));

		add_action('wp_ajax_nopriv_wptbaGetTags', array(self::$globalNamespace, 'getTags'));
		add_action('wp_ajax_wptbaGetTags', array(self::$globalNamespace, 'getTags'));

		add_action('wp_ajax_nopriv_wptbaRemoveTag', array(self::$globalNamespace, 'removeTag'));
		add_action('wp_ajax_wptbaRemoveTag', array(self::$globalNamespace, 'removeTag'));
	}

	public static function getPosts()
	{

		/**
		 * Verifying Nonce
		 */
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) wp_die();

		$userID = Officer::validateRequest($_POST);
		if (gettype($userID) != 'integer') wp_die();

		$posts = get_posts(array(
			'author' => $userID,
			'post_type' => 'wp_todo_board',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'date',
			'order' => 'DESC'
		));

		if (empty($posts)) {
			echo json_encode('null');
			wp_die();
		}

		$posts = array_map(function ($post) {
			return array(
				'id' => $post->ID,
				'title' => $post->post_title,

			);
		}, $posts);

		echo json_encode($posts);
		wp_die();
	}

	public static function addPost()
	{
		/**
		 * Verifying Nonce
		 */
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) wp_die();

		$userID = Officer::validateRequest($_POST);
		if (gettype($userID) != 'integer') wp_die();

		$post_id = wp_insert_post(array(
			'post_title' => sanitize_text_field($_POST['title']),
			'post_type' => 'wp_todo_board',
			'post_status' => 'publish',
			'post_author' => $userID
		));

		add_post_meta(
			$post_id,
			'wp_todo_board_meta',
			serialize(
				array(
					'todos' => array(
						array(
							'title' => 'To do',
							'data' => array('Drink water', 'Eat', 'Sleep')
						),
					)
				)
			)
		);

		echo json_encode(array('id' => $post_id, 'title' => sanitize_text_field($_POST['title'])));
		wp_die();
	}

	public static function getPostMeta()
	{
		/**
		 * Verifying Nonce
		 */
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) wp_die();

		$userID = Officer::validateRequest($_POST);
		if (gettype($userID) != 'integer') wp_die();

		$post_id = intval($_POST['post_id']);

		$meta = get_post_meta($post_id, 'wp_todo_board_meta', true);
		/*TODO: sanitize data  */
		$meta = unserialize($meta);
		echo json_encode($meta);
		wp_die();
	}

	public static function setPostMeta()
	{
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) wp_die();

		$userID = Officer::validateRequest($_POST);
		if (gettype($userID) != 'integer') wp_die();

		$post_id = intval($_POST['post_id']);

		/**
		 * Checking if post belongs to user
		 */
		if ($userID != get_post_field('post_author', $post_id)) wp_die();

		$meta = (array)json_decode(str_replace('\\', '', $_POST['meta']), true);


		for ($i = 0; $i < count($meta); $i++) {
			$meta[$i]['data'] = array_map(function ($item) {
				return sanitize_text_field($item);
			}, $meta[$i]['data']);

			$meta[$i]['title'] = sanitize_text_field($meta[$i]['title']);
		}
		$meta = array(
			'todos' => $meta
		);

		echo json_encode(update_post_meta($post_id, 'wp_todo_board_meta', serialize($meta)));
		wp_die();
	}

	public static function deletePost()
	{
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) wp_die();

		$userID = Officer::validateRequest($_POST);
		if (gettype($userID) != 'integer') wp_die();

		$post_id = intval($_POST['post_id']);

		/**
		 * Checking if post belongs to user
		 */
		if ($userID != get_post_field('post_author', $post_id)) wp_die();



		delete_post_meta($post_id, 'wp_todo_board_meta');
		wp_delete_post($post_id, true);
		echo json_encode($post_id);
		wp_die();
	}

	public static function getTags()
	{
		//get post tags
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) {
			wp_die();
		}

		$userID = Officer::validateRequest($_POST);
		if (gettype($userID) != 'integer') {
			echo json_encode(0);
			wp_die();
		}

		$post_id = intval($_POST['post_id']);

		$tags = get_the_terms($post_id, 'wp_todo_board_tag');

		if ($tags == false) {
			echo json_encode('null');
			wp_die();
		}

		$tags = array_map(function ($tag) {
			return array(
				'id' => $tag->name
			);
		}, $tags);

		echo json_encode($tags);
		wp_die();
	}

	/**
	 * removeTag
	 * 
	 * @return void
	 */
	public static function removeTag()
	{
		/**
		 * verifying nonce 
		 */
		if (!wp_verify_nonce($_POST['wptba_nonce'], 'wptba_nonce')) {
			wp_die();
		}

		/**
		 * verifying jwt  
		 */
		$userID = Officer::validateRequest($_POST);
		if (gettype($userID) != 'integer') {
			echo json_encode(0);
			wp_die();
		}

		/**
		 * checking if tag id and post id provided or not 
		 */
		if (!$_POST['tagId'] or !$_POST['postId']) wp_die();

		/**
		 * Sanitizing post id and term/tag id
		 */
		$tagId 	= intval($_POST['tagId']);
		$postId = intval($_POST['postId']);

		/**
		 * removing the term/tag id
		 */
		$status = wp_remove_object_terms($postId, $tagId, 'wp_todo_board_tag');

		/**
		 * checking if there is an error during term removing process
		 * if there is an error, which will be wp_error object,
		 * is_wp_error is a method/function to check wp_error object
		 * 
		 * if there is an error, sending false as signal and terminating the 
		 * flow with wp_die();
		 */
		if (is_wp_error($status)) {
			echo json_encode(false);
			wp_die();
		}

		/**
		 * sending success signal in case there is not error 
		 * and terminating the flow with wp_die();
		 */
		echo json_encode(true);
		wp_die();
	}
}
