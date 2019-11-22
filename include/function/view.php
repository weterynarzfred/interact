<?php
function set_view($view) {
  SN() -> view = $view;
}

function get_view($view = NULL, $data = NULL) {
  ob_start();
  display_view($view, $data);
  return ob_get_clean();
}

function display_view($_view = NULL, $_data = NULL) {
  if(!$_view) {
    if(!SN() -> view) SN() -> view = '404';
    $_view = SN() -> view;
  }
  $_urls = array(
    HOME_DIR . '/view/',
  );
  $_urls = apply_hook('display_view_urls', $_urls);
  $_found = false;
  foreach ($_urls as $_url) {
    if(file_exists($_url . $_view . '.php')) {
      if(is_array($_data)) {
        foreach ($_data as $_key => $_value) {
          ${$_key} = $_value;
        }
      }
      include $_url . $_view . '.php';
      $_found = true;
      break;
    }
  }

  if(!$_found) {
    SN() -> create_error('file ' . $_url . $_view  . ' not found');
  }
  SN() -> display_errors();
}
