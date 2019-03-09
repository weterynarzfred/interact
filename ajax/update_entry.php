<?php if(!defined('CONNECTION_TYPE')) die();

$values = $_POST['values'];
$success = false;

try {
  if(
    !isset($values['id']) ||
    !isset($values['name']) ||
    !isset($values['type']) ||
    !isset($values['read']) ||
    !isset($values['ready'])
  ) {
    throw new Exception('correct data not provided');
  }
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "update_entry"; ' . $e);
}

try {
  $sql = "
    UPDATE `interact_entries`
    SET `name` = :name, `type` = :type, `read` = :read, `ready` = :ready
    WHERE `ID` = :id
  ";

  $sql = SN()->db_connect()->prepare($sql);
  $sql->bindParam(':id', $values['id']);
  $sql->bindParam(':name', $values['name']);
  $sql->bindParam(':type', $values['type']);
  $sql->bindParam(':read', $values['read']);
  $sql->bindParam(':ready', $values['ready']);
  $sql->execute();

  $html = get_view('home');

  $success = true;
}
catch(Exception $e) {
  SN()->create_error('could not update the entry: ' . $e->getMessage());
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
