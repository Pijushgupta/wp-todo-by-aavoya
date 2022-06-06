<?php

namespace Wptba\Init;

if (!defined('ABSPATH')) exit;

class Aau
{
	public static function init($autoApproveUser = true)
	{
		if (get_option('wptba_aau', null) != null) return;
		update_option('wptba_aau', rest_sanitize_boolean($autoApproveUser));
	}
}
