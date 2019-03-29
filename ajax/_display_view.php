<?php if(!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;

try {
  if(!isset($values['name'])) {
    throw new Exception('correct data not provided');
  }

  $view = get_view($values['name'], isset($values['value']) ? $values['value'] : NULL);
  $success = true;
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "display_view"; ' . $e);
}

$response = array(
  'success'  =>  $success,
  'errors'  =>  SN()->get_errors(),
	'type'	=>	'display_view',
);

if($success) {
  $response['fragments'] = array(
    array(
      'type'  =>  'update',
      'element' =>  '#content',
      'html'  =>  $view
    ),
  );
}
else {
  $response['fragments'] = array(
    array(
      'type'  =>  'message',
      'html'  =>  'could not display view ' . $values['name'],
    ),
  );
}

echo json_encode($response);
