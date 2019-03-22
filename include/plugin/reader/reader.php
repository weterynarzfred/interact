<?php if(!defined('CONNECTION_TYPE')) die();
add_to_hook('get_option_scripts', function($data) {
  $data[] = '/include/plugin/reader/js/reader.js';
  return $data;
});

add_to_hook('display_view_urls', function($data) {
  $data[] = HOME_DIR . '/include/plugin/reader/view/';
  return $data;
});

add_to_hook('get_option_entry_properties', function($data) {
  $data[] = 'reader_folder';
  $data[] = 'last_read_file';
  $data[] = 'last_read_page';
  $data[] = 'reader_downloaded';
  $data[] = 'left_downloaded';
  return $data;
});

add_to_hook('get_prop_reader_folder', function($data, $entry) {
  if($data === '') {
    if(is_dir(get_option('manga_url') . $entry->get_name())) {
      $data = $entry->get_name();
    }
  }
  return $data;
});

add_to_hook('update_entry', function($values, $entry) {
	// recalculate left_downloaded field if reader_downloaded or read changed
	if(isset($values['reader_downloaded']) || isset($values['read'])) {
		$downloaded = isset($values['reader_downloaded'])
			? $values['reader_downloaded']
			: $entry->get_prop('reader_downloaded');
		$read = isset($values['read'])
			? $values['read']
			: $values->get_read();
		$values['left_downloaded'] = intval($downloaded) - intval($read);
	}

	// update read_date field if last_read_page grows
	if(
		isset($values['last_read_page'])
		&& !isset($values['read_date'])
		&& $values['last_read_page'] > $entry->get_prop('last_read_page')
	) $values['read_date'] = date('Y-m-d G:i:s');

  return $values;
});

add_to_hook('after_single_entry_buttons', function($data) {
  if($data->get_prop('reader_folder')) {
?>
<div class="navigation-link" data-target="reader" data-value="<?php echo $data->get_ID(); ?>">
	<div class="icon link">&#x1f56e;</div>
</div>
<?php
  }
});

add_to_hook('after_single_entry_progress', function($data) {
	$downloaded = $data->get_prop('reader_downloaded');
	if($downloaded != 0) { ?>
  <span class="entry-progress-separator">d:</span> <span class="entry-downloaded"><?php echo $downloaded; ?></span>
  <?php }
});

if(!isset_option('manga_url')) {
  set_option('manga_url', 'F:/interact_reader/');
}
