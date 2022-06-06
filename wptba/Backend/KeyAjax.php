<?php

namespace Wptba\Backend;

if (!defined('ABSPATH')) exit;

use Wptba\Common\Key;

class KeyAjax
{


	private static $globalScopeName = 'Wptba\Backend\KeyAjax';

	public static function init()
	{
		add_action('wp_ajax_setKeyWptba', array(self::$globalScopeName, 'setKeyWptba'));
		add_action('wp_ajax_getKetKeyWptba', array(self::$globalScopeName, 'getKetKeyWptba'));
	}

	public static function setKeyWptba()
	{
		if (!wp_verify_nonce($_POST['wptba_backend_nonce'], 'wptba_backend_nonce')) wp_die();

		$key  = Key::generate();
		$key = sanitize_text_field($key);
		echo json_encode(Key::set($key));
		wp_die();
	}

	public static function getKetKeyWptba()
	{
		if (!wp_verify_nonce($_POST['wptba_backend_nonce'], 'wptba_backend_nonce')) wp_die();

		echo json_encode(Key::get());
		wp_die();
	}
}
