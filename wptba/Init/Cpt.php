<?php

namespace Wptba\Init;

if (!defined('ABSPATH')) exit;

final class Cpt
{
	private static $globalNameSpace = 'Wptba\Init\Cpt';
	public static function create()
	{
		self::createBoard();
		add_action('init', array(self::$globalNameSpace, 'createTaxonomy'));
		self::createUser();
	}

	public static function createBoard()
	{
		if (!post_type_exists('wp_todo_board')) {
			register_post_type('wp_todo_board', array(
				'labels' => array(
					'name' => __('Aavoya Todo Board', 'wp-todo-by-aavoya'),
					'singular_name' => __('Aavoya Todo Board', 'wp-todo-by-aavoya'),
					'add_new' => __('Add New', 'wp-todo-by-aavoya'),
					'add_new_item' => __('Add New Item', 'wp-todo-by-aavoya'),
					'edit_item' => __('Edit Item', 'wp-todo-by-aavoya'),
					'new_item' => __('New Item', 'wp-todo-by-aavoya'),
					'view_item' => __('View Item', 'wp-todo-by-aavoya'),
					'search_items' => __('Search Items', 'wp-todo-by-aavoya'),
					'not_found' => __('Nothing Found', 'wp-todo-by-aavoya'),
					'not_found_in_trash' => __('Nothing Found in Trash', 'wp-todo-by-aavoya'),
					'parent_item_colon' => ''
				),
				'description' => 'Aavoya Todo Board',
				'public' => true,
				'exclude_from_search' => true, // When a search is conducted through search.php, should it be excluded?
				'publicly_queryable' => true, // When a parse_request() search is conducted, should it be included?
				'show_ui' => true, // Should the primary admin menu be displayed?
				'show_in_nav_menus' => true, // Should it show up in Appearance > Menus?
				'show_in_menu' => true, // This inherits from show_ui, and determines *where* it should be displayed in the admin
				'show_in_admin_bar' => false, // Should it show up in the toolbar when a user is logged in?
				'has_archive' => false,
				'rewrite' => false
			));
		}
	}
	public static function createUser()
	{
		if (!post_type_exists('wp_todo_user')) {
			register_post_type('wp_todo_user', array(
				'labels' => array(
					'name' => __('Aavoya Todo User', 'wp-todo-by-aavoya'),
					'singular_name' => __('Aavoya Todo User', 'wp-todo-by-aavoya'),
					'add_new' => __('Add New', 'wp-todo-by-aavoya'),
					'add_new_item' => __('Add New Item', 'wp-todo-by-aavoya'),
					'edit_item' => __('Edit Item', 'wp-todo-by-aavoya'),
					'new_item' => __('New Item', 'wp-todo-by-aavoya'),
					'view_item' => __('View Item', 'wp-todo-by-aavoya'),
					'search_items' => __('Search Items', 'wp-todo-by-aavoya'),
					'not_found' => __('Nothing Found', 'wp-todo-by-aavoya'),
					'not_found_in_trash' => __('Nothing Found in Trash', 'wp-todo-by-aavoya'),
					'parent_item_colon' => ''
				),
				'description' => 'Aavoya Todo User',
				'public' => true,
				'exclude_from_search' => true, // When a search is conducted through search.php, should it be excluded?
				'publicly_queryable' => true, // When a parse_request() search is conducted, should it be included?
				'show_ui' => true, // Should the primary admin menu be displayed?
				'show_in_nav_menus' => true, // Should it show up in Appearance > Menus?
				'show_in_menu' => true, // This inherits from show_ui, and determines *where* it should be displayed in the admin
				'show_in_admin_bar' => false, // Should it show up in the toolbar when a user is logged in?
				'has_archive' => false,
				'rewrite' => false
			));
		}
	}

	public static function createTaxonomy()
	{
		if (taxonomy_exists('wp_todo_board_tag')) return;

		register_taxonomy(
			'wp_todo_board_tag',
			'wp_todo_board',
			array(
				'labels'	=>	array(
					'name' => 'User tag',
					'singular_name' => 'Wp todo board tag',
					'search_items' => 'Search Wp todo board tag',
					'popular_items' => 'Popular Wp todo board tag',
					'all_items' => 'All Wp todo board tag',
					'parent_item' => 'Parent Wp todo board tag',
					'parent_item_colon' => 'Parent Wp todo board tag:',
					'edit_item' => 'Edit Wp todo board tag',
					'view_item' => 'View Wp todo board tag',
					'not_found' => 'No Wp todo board tag found',

				),
				'description' => 'Wp todo board tag, to hold user id as Tag',
				'public' => true,
				'hierarchical' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_in_nav_menus' => true,
			)
		);
	}
}
