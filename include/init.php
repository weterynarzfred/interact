<?php if(!defined('CONNECTION_TYPE')) die();

define('HOME_DIR', dirname(__DIR__));

// register functions
call_user_func(function() {
	$d = scandir(HOME_DIR . '/include/function');
	$files = array_map(function($f) {return HOME_DIR . '/include/function/' . $f;}, $d);
	$files = array_filter($files, function($f) {return is_file($f);});
	if($files) {
		foreach($files as $file) {
			include $file;
		}
	}
});

if(file_exists(HOME_DIR. '/config.json')) {
	SN()->test_db_tables();
  set_view('home');
}
else {
  set_view('config');
}

if(CONNECTION_TYPE === 'manual') {
	display_view('part/header');
	display_view();
	display_view('part/footer');
}
elseif(CONNECTION_TYPE === 'ajax') {
	if(isset($_POST['action'])) {
		$file = HOME_DIR . '/ajax/' . $_POST['action'] . '.php';
		if(file_exists($file)) {
			include $file;
			return;
		}
	}
	http_response_code(404);
	die();
}