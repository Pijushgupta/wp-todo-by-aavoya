<?php

namespace Wptba\Frontend;

if (!defined('ABSPATH')) exit;

use Wptba\Frontend\Enqueue;

class Template
{



	public static function show()
	{
?>
		<!DOCTYPE html>
		<html lang="en">

		<head>
			<meta charset="UTF-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">

			<title>Wp Todo - by Aavoya.co</title>
			<link rel="stylesheet" href="<?php echo Enqueue::style(); ?>">
			<link rel="stylesheet" href="<?php echo Enqueue::font(); ?>">
			<script>
				var wptba_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
				var wptba_nonce = '<?php echo wp_create_nonce('wptba_nonce'); ?>';
			</script>
		</head>

		<body>
			<div class='wptodobyaavoya'>

			</div>
			<script src="<?php echo Enqueue::script(); ?>"></script>
		</body>

		</html>
<?php
	}
}
