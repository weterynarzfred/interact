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
  $url = HOME_DIR . '/view/' . $view . '.php';
  if(file_exists($url)) {
    include $url;
  }
  else {
    SN()->create_error('file ' . $url . ' not found');
  }
  SN()->display_errors();
}
