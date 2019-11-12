<?php

set_option('active_plugins', array(
  'madokami',
), false);

// load plugins
call_user_func(function() {
  $url = HOME_DIR . '/include/plugin';
  if (is_dir($url)) {
    $plugins = get_option('active_plugins');
    for ($i = 0; $i < count($plugins); $i++) {
      $url = HOME_DIR . '/include/plugin/' . $plugins[$i] . '/' . $plugins[$i] . '.php';
      if (file_exists($url)) {
        include $url;
      }
    }
  }
});

$url = HOME_DIR . '/config.json';
$string = file_get_contents($url);
$config = json_decode($string, true);
foreach ($config as $option_name => $option_value) {
  set_option($option_name, $option_value, false);
}

SN() -> test_db_tables();

set_option('entry_properties', array(
  ['read', true],
  ['ready', true],
  ['downloaded', true],
  ['is_finished', true],
  ['cover', true],
  ['last_read_chapter', false],
  ['last_read_page', false],
), false);

try {
  $sql = "
    SELECT `name`, `value`
    FROM `e_interact_options`
  ";

  $sql = SN() -> db_connect() -> prepare($sql);

  $sql -> execute();
  $result = $sql -> fetchAll(PDO::FETCH_ASSOC);
  for ($i = 0; $i < count($result); $i++) {
    set_option($result[$i]['name'], $result[$i]['value']);
  }
}
catch (Exception $e) {
  SN() -> create_error('could not retrieve options: ' . $e -> getMessage());
}


if (!isset_option('manga_url')) {
  set_option('manga_url', '/storage/');
}
if (!isset_option('7z_path')) {
  set_option('7z_path', 'C:/Program Files/7-Zip/7z');
}

apply_hook('init');
