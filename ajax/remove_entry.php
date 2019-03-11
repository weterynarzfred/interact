<?php if(!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;

try {
  if(!isset($values['id'])) {
    throw new Exception('correct data not provided');
  }
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "remove_entry"; ' . $e);
}

try {
  $sql = "
    DELETE FROM `interact_entries`
    WHERE `ID` = (:id)
  ";

  $sql = SN()->db_connect()->prepare($sql);
  $sql->bindParam(':id', $values['id']);

  $sql->execute();

  $sql = "
    DELETE FROM `interact_entries_meta`
    WHERE `entry_ID` = (:id)
  ";

  $sql = SN()->db_connect()->prepare($sql);
  $sql->bindParam(':id', $values['id']);

  $sql->execute();

  $success = true;
}
catch(Exception $e) {
  SN()->create_error('could not delete the entry: ' . $e->getMessage());
}

$response = array(
  'succsess'  =>  $success,
  'errors'  =>  SN()->get_errors(),
);

if($success) {
  $response['fragments'] = array(
    array(
      'type'  =>  'delete',
      'element' =>  '#entry-' . $values['id'],
    ),
    array(
      'type'  =>  'message',
      'html'  =>  'the entry was removed from the database',
    ),
  );
}
else {
  $response['fragments'] = array(
    array(
      'type'  =>  'message',
      'html'  =>  'could not remove the entry from the database',
    ),
  );
}

echo json_encode($response);
