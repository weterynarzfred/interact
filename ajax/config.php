<?php if(!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;
$tables_just_created = false;

try {
  if(
    !isset($values['db_host']) ||
    !isset($values['db_name']) ||
    !isset($values['db_user']) ||
    !isset($values['db_password'])
  ) {
    throw new Exception('correct data not provided');
  }
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "config"; ' . $e);
}

if(SN()->test_db_connection(
  $values['db_host'],
  $values['db_name'],
  $values['db_user'],
  $values['db_password'])
) {
  $data = [
    'db_host' =>  $values['db_host'],
    'db_name' =>  $values['db_name'],
    'db_user' =>  $values['db_user'],
    'db_password' =>  $values['db_password'],
  ];
  $success = SN()->save_config($data);
  if($success) {
    $tables_just_created = SN()->test_db_tables();
  }
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
      'html'  =>  SN()->get_view('home'),
    ),
    array(
      'type'  =>  'message',
      'html'  =>  'database credentials saved succesfully',
    ),
  );
}
else {
  $response['fragments'] = array(
    array(
      'type'  =>  'message',
      'html'  =>  'could not connect to the database',
    ),
  );
}
if($tables_just_created) {
  $response['fragments'][] = array(
    'type'  =>  'message',
    'html'  =>  'tables were created',
  );
}


echo json_encode($response);
