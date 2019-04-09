<?php if(!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;

try {
  if(!isset($values['id'])) {
    throw new Exception('correct data not provided');
  }

	$entry = get_entry($values['id']);
	// $entry->update($values);
	// $html_single_entry = get_view('part/single_entry', array('entry'=>$entry));
	// $html_reader_filelist = get_view(
	// 	'part/reader_filelist',
	// 	array('entry'=>$entry)
	// );
	// $html_madokami_filelist = get_view(
	// 	'part/madokami_filelist',
	// 	array(
	// 		'entry'	=>	$entry,
	// 		'skip_check'	=>	true,
	// 	)
	// );

	$success = true;
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "update_entry"; ' . $e);
}

$response = array(
  'success'  =>  $success,
  'errors'  =>  SN()->get_errors(),
	'type'	=>	'delete_entry',
);

if($success) {
  $response['fragments'] = array(
		// array(
    //   'type'  =>  'delete',
    //   'element' =>  '.view-part-madokami_filelist',
    //   'html'  => $html_madokami_filelist,
    // ),
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
