<?php if (!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;

try {
  if (
    !isset($values['id'])
  ) {
    throw new Exception('correct data not provided');
  }

  $was_updated;
  $madokami_files = reader_get_madokami_files($values['id'], $was_updated);

  $success = true;
}
catch(Exception $e) {
  SN() -> create_error('ajax failed performing action "madokami_scrape"; ' . $e);
}

$response = array(
  'success' =>  $success,
  'errors'  =>  SN() -> get_errors(),
  'type'    =>  'madokami_scrape',
);

if ($success && $was_updated) {
  $html = get_view('part/single_entry', array(
    'entry' => $values['id'],
    'classes'  => array('single-entry-new-downloaded'),
  ));
  $response['fragments'] = array(
    array(
      'type'  =>  'message',
      'html'  =>  'found new files on madokami for ' . get_entry($values['id']) -> get_name(),
    ),
    array(
      'type'    =>  'replace',
      'element' =>  '#entry-' . $values['id'],
      'html'    =>  $e['html'],
    ),
  );
}

echo json_encode($response);
