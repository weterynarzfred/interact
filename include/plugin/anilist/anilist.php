<?php if(!defined('CONNECTION_TYPE')) die();

add_to_hook('get_option_entry_properties', function($data) {
  $data[] = 'anilist_ID';
  return $data;
});

add_to_hook('init', function() {
  if(!isset_option('anilist_last_check')) {
    set_option('anilist_last_check', 0);
  }
});
