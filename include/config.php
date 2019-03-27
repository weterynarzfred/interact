<?php
SN()->test_db_tables();

set_option('entry_properties', array(
	'is_finished',
), false);
set_option('scripts', array(
	'/js/main.js',
	'/js/get_view.js',
	'/js/messages.js',
	'/js/interactions.js',
), false);


try {
	$sql = "
		SELECT `name`, `value`
		FROM `interact_options`
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
	$d = scandir(HOME_DIR . '/include/plugin');
	$plugins = array_filter($d, function($f) {return is_dir(HOME_DIR . '/include/plugin/' . $f);});
	if($plugins) {
		for ($i=0; $i < count($plugins); $i++) {
			$url = HOME_DIR . '/include/plugin/' . $plugins[$i] . '/' . $plugins[$i] . '.php';
			if(file_exists($url)) {
				include $url;
			}
		}
	}
});

apply_hook('init');
