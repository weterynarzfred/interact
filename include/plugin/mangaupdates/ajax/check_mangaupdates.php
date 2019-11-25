<?php if (!defined('CONNECTION_TYPE')) die();

function get_newest_chapter_number($entry_id, $mangaupdates_id) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL	=> 'https://www.mangaupdates.com/releases.html?stype=series&search=' . $mangaupdates_id,
    CURLOPT_RETURNTRANSFER => 1,
  ));

  $res = curl_exec($curl);
  curl_close($curl);
  $matches = array();
  preg_match_all('/<div class=\'col-1[a-z0-9- \':#=]*?><span>(.*?)<\/span><\/div>/i', $res, $matches);
  if(is_array($matches[1]) && count($matches[1]) > 0) {
    $chapters = array_map(function($e) {
      $matches = array();
      preg_match_all('/([0-9.]+)/i', $e, $matches);
      if(count($matches) <= 1) {
        return 0;
      }
      return intval(end($matches[1]));
    }, $matches[1]);
    return max($chapters);
  }
  return 0;
}

$values = $_POST['values'];
$success = false;
$changed = array();

try {
  if(!isset($values)) {
    throw new Exception('correct data not provided');
  }
  array_map(function($e) use (&$changed) {
    $entry = get_entry($e['entry']);
    $newest = get_newest_chapter_number($entry, $e['mangaupdates']);
    if($newest > $entry -> get_prop('ready')) {
      $entry -> update(array(
        'ready'	=>  $newest,
      ));
      $changed[] = array(
        'entry_id'  =>  $e['entry'],
        'name'      =>  $entry -> get_name(),
        'newest'    =>  $newest,
        'html'      =>  get_view('part/single_entry', array(
          'entry' => $entry,
          'classes'  => array('single-entry-new-ready'),
        )),
      );
    }
  }, $values);
  $success = true;
}
catch(Exception $e) {
  SN() -> create_error('ajax failed performing action "update_entry"; ' . $e);
}

$response = array(
  'success' =>  $success,
  'errors'  =>  SN() -> get_errors(),
  'type'    =>  'check_mangaupdates',
);

if($success) {
  set_option('mangaupdates_last_check', time());
  $response['fragments'] = array(
    array(
      'type'    =>  'replace',
      'element' =>  '#mangaupdates-data',
      'html'    =>  '<div id="mangaupdates-data" data-last-update="' . time() . '"></div>',
    ),
  );
  foreach ($changed as $e) {
    $response['fragments'][] = array(
      'type'    =>  'replace',
      'element' =>  '#entry-' . $e['entry_id'],
      'html'    =>  $e['html'],
    );
    $response['fragments'][] =
    array(
      'type'  =>  'message',
      'html'  =>  'ready chapters of ' . $e['name'] . ' updated to ' . $e['newest'],
    );
  }
}
else {
  $response['fragments'] = array(
    array(
      'type'  =>  'message',
      'html'  =>  'could not check mangaupdates',
    ),
  );
}

echo json_encode($response);
