<?php if(!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;
$error = false;

try {
  if(!isset($values['id'])) {
    throw new Exception('correct data not provided');
  }

	$entry = get_entry($values['id']);
	$entry->update($values);
	$html = get_view('part/single_entry', array('entry'=>$entry));

	$success = true;
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "update_entry"; ' . $e);
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
      'element' =>  '#entry-' . $entry->get_id(),
      'html'  => $html,
			'onEmpty'	=>	array(
	      'type'  =>  'append',
	      'element' =>  '.entry-list',
	      'html'  => $html,
			),
    ),
    array(
      'type'  =>  'message',
      'html'  =>  'the entry was updated',
    ),
  );
	if($values['id'] == '') {
		$response['lastInsertedId'] = $entry->get_id();
	}
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
