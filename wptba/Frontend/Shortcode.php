<?php

namespace Wptba\Frontend;

if (!defined('ABSPATH')) exit;


use Wptba\Frontend\Template;


class Shortcode
{

	private static $globallyScoopedClassName = 'Wptba\Frontend\Shortcode';


	public static function activate(){
		add_shortcode('wptba', array(self::$globallyScoopedClassName, 'init'));
		
	}


	public static function init(){
		if (!is_admin()) {

		add_filter('template_include', array(self::$globallyScoopedClassName, 'templateInclude'));
			
		}
	}

	public static function templateInclude($template){
		
		$template = dirname(__FILE__) . '/template.php';
		
		return $template;
	}
}
