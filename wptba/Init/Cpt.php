<?php

namespace Wptba\Init;

if (!defined('ABSPATH')) exit;

final class Cpt
{
	public static function create()
	{
		self::createBoard();
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
}
