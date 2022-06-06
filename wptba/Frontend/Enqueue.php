<?php

namespace Wptba\Frontend;

if (!defined('ABSPATH')) exit;

class Enqueue
{




	public static function style()
	{
		return plugin_dir_url(__FILE__) . 'client/dist/main.css';
	}
	public static function font()
	{
		return plugin_dir_url(__FILE__) . 'client/dist/fonts.css';
	}
	public static function script()
	{
		return plugin_dir_url(__FILE__) . 'client/dist/main.js';
	}
}
