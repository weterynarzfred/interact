<?php if(!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;

try {
  if(!isset($values['view'])) {
    throw new Exception('correct data not provided');
  }

  $view = get_view($values['view'], isset($values['details']) ? $values['details'] : NULL);
  $success = true;
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "get_view"; ' . $e);
}

$response = array(
  'success'  =>  $success,
  'errors'  =>  SN()->get_errors(),
	'type'	=>	'get_view',
);

if($success) {
  $response['fragments'] = array(
    array(
      'type'  =>  'replace',
      'element' =>  isset($values['target']) ? $values['target'] : '#content',
      'html'  =>  $view,
    ),
  );
}
else {
  $response['fragments'] = array(
    array(
      'type'  =>  'message',
      'html'  =>  'could not get view ' . $values['view'],
    ),
  );
}

echo json_encode($response);
