<?php
function set_view($view) {
  SN()->view = $view;
}

function get_view($view = NULL, $data = NULL) {
  ob_start();
  display_view($view, $data);
  return ob_get_clean();
}
function display_view($view = NULL, $data = NULL) {
  if(!$view) {
    if(!SN()->view) SN()->view = '404';
    $view = SN()->view;
  }
  $urls = array(
    HOME_DIR . '/view/',
  );
  $urls = apply_hook('display_view_urls', $urls);
  $found = false;
  foreach ($urls as $url) {
    if(file_exists($url . $view . '.php')) {
			if(is_array($data)) {
				foreach ($data as $key => $value) {
					${$key} = $value;
				}
			}
      include $url . $view . '.php';
      $found = true;
      break;
    }
  }

  if(!$found) {
    SN()->create_error('file ' . $url . $view  . ' not found');
  }
  SN()->display_errors();
}
