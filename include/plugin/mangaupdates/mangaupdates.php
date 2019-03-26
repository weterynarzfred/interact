<?php if(!defined('CONNECTION_TYPE')) die();

add_to_hook('get_option_entry_properties', function($data) {
  $data[] = 'mangaupdates_ID';
  return $data;
});

add_to_hook('init', function() {
  if(!isset_option('mangaupdates_last_check')) {
    set_option('mangaupdates_last_check', 0);
  }
});

add_to_hook('ajax_urls', function($data) {
  $data[] = HOME_DIR . '/include/plugin/mangaupdates/ajax/';
  return $data;
});

add_to_hook('get_option_scripts', function($data) {
  $data[] = '/include/plugin/mangaupdates/js/mangaupdates.js';
  return $data;
});

add_to_hook('after_single_entry', function($entry) {
	if($entry->get_prop('mangaupdates_ID') !== '') { ?>
<div
	class="entry-mangaupdates-data"
	data-entry-id="<?php echo $entry->get_ID(); ?>"
	data-mangaupdates-id="<?php echo $entry->get_prop('mangaupdates_ID'); ?>"
></div>
	<?php }
});

add_to_hook('before_entry_list', function($entry) { ?>
<div
	id="mangaupdates-data"
	data-last-update="<?php echo get_option('mangaupdates_last_check'); ?>"
></div>
<?php });
