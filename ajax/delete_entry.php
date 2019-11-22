<?php if(!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;

try {
  if(!isset($values['id'])) {
    throw new Exception('correct data not provided');
  }

	$entry = get_entry($values['id']);
	$entry->delete();

	$success = true;
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "delete_entry"; ' . $e);
}

$response = array(
  'success'  =>  $success,
  'errors'  =>  SN()->get_errors(),
	'type'	=>	'delete_entry',
);

if($success) {
  $response['fragments'] = array(
		array(
      'type'  =>  'delete',
      'element' =>  '#entry-' . $values['id'],
    ),
    array(
      'type'  =>  'message',
      'html'  =>  'the entry was removed',
    ),
  );
}
else {
  $response['fragments'] = array(
    array(
      'type'  =>  'message',
      'html'  =>  'could not delete the entry from the database',
    ),
  );
}

echo json_encode($response);
