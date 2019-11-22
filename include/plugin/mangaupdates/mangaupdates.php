<?php if (!defined('CONNECTION_TYPE')) die();

// add properties to entries
add_to_hook('set_option_entry_properties', function($array) {
  return array(
    'value' => array_merge($array['value'], array(
      ['mangaupdates_ID', true],
    )),
    'save' => $array['save'],
  );
});

// set initial value to mangaupdates_last_check option
add_to_hook('init', function() {
  if (!isset_option('mangaupdates_last_check')) {
    set_option('mangaupdates_last_check', 0);
  }
});

// add ajax scripts location
add_to_hook('ajax_urls', function($urls) {
  array_push($urls, __DIR__ . '/ajax/');
  return $urls;
});

// add js scripts location
add_to_hook('set_option_scripts', function($data) {
  $data['value'][] = '/include/plugin/mangaupdates/js/mangaupdates.js';
  return $data;
});

// add mangaupdates meta to entries in the archive
add_to_hook('after_single_entry', function($entry) {
  if ($entry -> get_prop('mangaupdates_ID') !== '') { ?>
<div
  class="entry-mangaupdates-data"
  data-entry-id="<?php echo $entry -> get_ID(); ?>"
  data-mangaupdates-id="<?php echo $entry -> get_prop('mangaupdates_ID'); ?>"
></div>
  <?php }
});

// add mangaupdates meta to the entry list
add_to_hook('before_entry_list', function() { ?>
<div
  id="mangaupdates-data"
  data-last-update="<?php echo get_option('mangaupdates_last_check'); ?>"
></div>
<?php });
