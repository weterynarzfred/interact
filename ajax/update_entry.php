<?php if(!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;
$error = false;

try {
  if(!isset($values['id'])) {
		$error = true;
    throw new Exception('correct data not provided');
  }
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "update_entry"; ' . $e);
}

if(!$error) {
	$entry = get_entry($values['id']);
	$entry->update($values);

	$html = get_view('part/single_entry', $entry);

	$success = true;
}

$response = array(
  'success'  =>  $success,
  'errors'  =>  SN()->get_errors(),
	'type'	=>	'update_entry',
);

if($success) {
  $response['fragments'] = array(
    array(
      'type'  =>  'replace',
      'element' =>  '#entry-' . $values['id'],
      'html'  => $html,
    ),
    array(
      'type'  =>  'message',
      'html'  =>  'the entry was updated',
    ),
  );
}
else {
  $response['fragments'] = array(
    array(
      'type'  =>  'message',
      'html'  =>  'could not update the entry in the database',
    ),
  );
}

echo json_encode($response);
