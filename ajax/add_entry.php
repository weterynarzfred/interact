<?php if(!defined('CONNECTION_TYPE')) die();

$success = false;

try {
  $sql = "
    INSERT INTO `interact_entries` (`name`)
    VALUES (:name)
  ";

  $sql = SN()->db_connect()->prepare($sql);
  $name = 'tempname';
  $sql->bindParam(':name', $name);
  $sql->execute();

  $entry = get_entry(SN()->db_connect()->lastInsertId());
  $html = get_view('part/single_entry', $entry);
  $success = true;
}
catch(Exception $e) {
  SN()->create_error('could not create an entry: ' . $e->getMessage());
}

$response = array(
  'succsess'  =>  $success,
  'errors'  =>  SN()->get_errors(),
);

if($success) {
  $response['fragments'] = array(
    array(
      'type'  =>  'append',
      'element' =>  '.entries',
      'html'  =>  $html,
    ),
    array(
      'type'  =>  'message',
      'html'  =>  'the entry was added to the database',
    ),
  );
}
else {
  $response['fragments'] = array(
    array(
      'type'  =>  'message',
      'html'  =>  'could not add the entry to the database',
    ),
  );
}

echo json_encode($response);
