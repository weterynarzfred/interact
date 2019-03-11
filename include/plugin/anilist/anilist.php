<?php

add_to_hook('get_option_entry_properties', function($a) {
  $a[] = 'anilist_ID';
  return $a;
});

add_to_hook('init', function() {
  if(!isset_option('anilist_last_check')) {
    set_option('anilist_last_check', 0);
  }
});
