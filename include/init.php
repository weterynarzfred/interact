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

set_option('scripts', array(
	'/vendor/jquery.timeago.js',
	'/js/main.js',
	'/js/get_view.js',
	'/js/messages.js',
	'/js/interactions.js',
	'/js/lazy-cakes.js',
	'/js/download.js',
	'/js/reader-chapter.js',
), false);

if(file_exists(HOME_DIR. '/config.json')) {
	include HOME_DIR . '/include/config.php';

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
		$urls = array(
			HOME_DIR . '/ajax/',
		);
		$urls = apply_hook('ajax_urls', $urls);
		$found = false;
		foreach ($urls as $url) {
			if(file_exists($url . $_POST['action'] . '.php')) {
				include $url . $_POST['action'] . '.php';
				$found = true;
				return;
			}
		}
	}
	http_response_code(404);
	die();
}
