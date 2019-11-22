<?php if (!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;

try {
  if (
    !isset($values['id'])
  ) {
    throw new Exception('correct data not provided');
  }

  $madokami_files = reader_get_madokami_files($values['id']);

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

if ($success) {
  $response['debug'] = $madokami_files;
  $response['fragments'] = array(
    array(
      'type'  =>  'message',
      'html'  =>  'checked madokami for ' . get_entry($values['id']) -> get_name(),
    ),
  );
}

echo json_encode($response);
