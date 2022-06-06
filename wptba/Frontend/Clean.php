<?php

namespace Wptba\Frontend;

if (!defined('ABSPATH')) exit;


class Clean
{

	private static $globallyScoopedClassName = 'Wptba\Frontend\Clean';

	public static function all()
	{
		add_action('wp_print_scripts', array(self::$globallyScoopedClassName, 'scripts'), 9999);
		add_action('wp_print_styles', array(self::$globallyScoopedClassName, 'style'), 9999);
	}

	public static function scripts()
	{
		global $wp_scripts;
		$wp_scripts->queue = array();
	}

	public static function style()
	{
		global $wp_styles;
		$wp_styles->queue = array();
	}
}
