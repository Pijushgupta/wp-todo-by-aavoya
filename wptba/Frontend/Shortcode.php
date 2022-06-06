<?php

namespace Wptba\Frontend;

if (!defined('ABSPATH')) exit;

use Wptba\Frontend\Clean;
use Wptba\Frontend\Template;


class Shortcode
{

	private static $globallyScoopedClassName = 'Wptba\Frontend\Shortcode';


	public static function activate()
	{
		add_shortcode('wptba', array(self::$globallyScoopedClassName, 'init'));
	}


	public static function init()
	{
		if (!is_admin()) {
			Clean::all();

			Template::show();
			die;
		}
	}
}
