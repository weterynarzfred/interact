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

// add view files location
add_to_hook('display_view_urls', function($urls) {
  array_push($urls, __DIR__ . '/view/');
  return $urls;
});

// add ajax scripts location
add_to_hook('ajax_urls', function($urls) {
  array_push($urls, __DIR__ . '/ajax/');
  return $urls;
});

// add js scripts location
add_to_hook('set_option_scripts', function($data) {
  $data['value'][] = '/include/plugin/madokami/js/madokami.js';
  return $data;
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

/**
 * Scrapes madokami for downloadable chapters. Returns an array containing:
 * ```
 * 'url'      => {string}
 * 'filename' => {string}
 * 'name'     => {string}
 * 'chapter'  => {int}
 * ```
 */
function reader_get_madokami_files($entry) {
  $entry = get_entry($entry);

  $output = array();
  $madokami_url = $entry -> get_prop('madokami_url');
  if ($madokami_url != '') {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $madokami_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
      $curl,
      CURLOPT_USERPWD,
      get_option('madokami_user') . ":" . get_option('madokami_password')
    );
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $output = curl_exec($curl);
    curl_close($curl);

    $results = array();
    $table = preg_split('/<table id="index-table.*?>/is', $output, 2);
    if (!isset($table[1]) || !isset($table[0])) return false;
    $table = preg_split('/<\/table>/is', $table[1], 2);
    preg_replace_callback(
      '/<tr data-record="[0-9]*">[^<]*<td>[^<]*<a href="([^"]*)"[^>]*>(([^<]*)\.[^<.]+)<\/a>/is',
      function($matches) use (&$results) {
        $results[] = array(
          'url'       =>  $matches[1],
          'filename'  =>  $matches[2],
          'name'      =>  $matches[3],
          'chapter'   =>  get_chapter_number($matches[2]),
        );
      },
      $table[0]
    );

    usort($results, function($a, $b) {
      return $a['chapter'] < $b['chapter'];
    });

    $update_array = array(
      'madokami_filelist'	  =>  $results,
      'madokami_last_check'	=>  time(),
    );
    if ($results[0]['chapter'] > $entry -> get_prop('downloaded')) {
      $update_array['downloaded'] = $results[0]['chapter'];
    }

    $entry -> update($update_array);

    return $results;
  }
  return false;
}