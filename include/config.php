<?php

$url = HOME_DIR . '/config.json';
$string = file_get_contents($url);
$config = json_decode($string, true);
foreach ($config as $option_name => $option_value) {
	set_option($option_name, $option_value, false);
}

SN()->test_db_tables();

set_option('entry_properties', array(
	'read',
	'ready',
	'downloaded',
	'is_finished',
	'cover',
	'madokami_url'
), false);


try {
	$sql = "
		SELECT `name`, `value`
		FROM `e_interact_options`
	";

	$sql = SN()->db_connect()->prepare($sql);

	$sql->execute();
	$result = $sql->fetchAll(PDO::FETCH_ASSOC);
	for ($i=0; $i < count($result); $i++) {
		set_option($result[$i]['name'], $result[$i]['value']);
	}
}
catch(Exception $e) {
	SN()->create_error('could not retrieve options: ' . $e->getMessage());
}

// load plugins
call_user_func(function() {
	$url = HOME_DIR . '/include/plugin';
	if(is_dir($url)) {
		$d = scandir($url);
		$plugins = array_filter($d, function($f) {return is_dir(HOME_DIR . '/include/plugin/' . $f);});
		if($plugins) {
			for ($i=0; $i < count($plugins); $i++) {
				$url = HOME_DIR . '/include/plugin/' . $plugins[$i] . '/' . $plugins[$i] . '.php';
				if(file_exists($url)) {
					include $url;
				}
			}
		}
	}
});

if(!isset_option('manga_url')) {
  set_option('manga_url', HOME_DIR . '/storage/');
}

apply_hook('init');
