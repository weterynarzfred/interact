<?php
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
  return $data;
});

add_to_hook('get_prop_reader_folder', function($data, $e) {
  if($data === '') {
    if(is_dir(get_option('manga_url') . $e->get_name())) {
      $data = $e->get_name();
    }
  }
  return $data;
});

add_to_hook('after_single_entry', function($data) {
  if($data->get_prop('reader_folder')) {
?>
  <div class="text-right">
    <div class="button navigation-link" data-target="reader" data-value="<?php echo $data->get_ID(); ?>">read</div>
  </div>
<?php
  }
});

if(!isset_option('manga_url')) {
  set_option('manga_url', 'F:/interact_reader/');
}
