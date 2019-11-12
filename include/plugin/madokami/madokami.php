<?php

// add properties to entries
add_to_hook('set_option_entry_properties', function($array) {
  return array(
    'value' => array_merge($array['value'], array(
      ['madokami_url', true],
      ['madokami_filelist', false],
      ['madokami_last_check', false],
    )),
    'save' => $array['save'],
  );
});

// decode json when accessing madokami filelist
add_to_hook('get_prop_madokami_filelist', function($value, $entry) {
  return json_decode($value, true);
});

// encode json when changing madokami filelist
add_to_hook('update_entry', function($values, $entry) {
  if (isset($values['madokami_filelist'])) {
    $values['madokami_filelist'] = json_encode($values['madokami_filelist']);
  }
  return $values;
});

// add views
add_to_hook('display_view_urls', function($urls) {
  array_push($urls, __DIR__ . '/view/');
  return $urls;
});


// display madokami filelist
add_to_hook('after_single_entry_reader', function($data, $content) { ?>
<div class="column-double">
  <div class="text-strip">
    <?php display_view('part/madokami_filelist', array(
      'entry'       =>  $content['entry'],
      'skip_check'  =>  true,
      'files'       =>  $content['files'],
    )); ?>
  </div>
</div>
<?php });
