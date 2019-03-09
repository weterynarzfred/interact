<?php if(!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;

try {
  if(!isset($values['name'])) {
    throw new Exception('correct data not provided');
  }

  $view = get_view($values['name'], isset($values['data']) ? $values['data'] : NULL);
  $success = true;
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "display_view"; ' . $e);
}

$response = array(
  'succsess'  =>  $success,
  'errors'  =>  SN()->get_errors(),
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
      'html'  =>  'could not display view ' . $value['name'],
    ),
  );
}

echo json_encode($response);
