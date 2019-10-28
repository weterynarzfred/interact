<?php if(!defined('CONNECTION_TYPE')) die();

function isset_option($name) {
  return isset(SN() -> options[$name]);
}

function get_option($name, $data = NULL) {
    if (!isset_option($name)) {
      SN() -> create_error('option named ' . $name . ' does not exist');
      return false;
    }
    $result = SN() -> options[$name];
    $result = apply_hook('get_option_' . $name, $result, $data);
    return $result;
}

function set_option($name, $value, $save = true) {
  SN() -> options[$name] = $value;
  if ($save) {
    try {
      $sql = "
        INSERT INTO e_interact_options (`name`, `value`)
        VALUES(:name, :value) ON DUPLICATE KEY
        UPDATE `name` = VALUES(`name`), `value` = VALUES(`value`)
      ";
      $sql = SN() -> db_connect() -> prepare($sql);
      $sql -> bindParam(':name', $name);
      $sql -> bindParam(':value', $value);
      $sql -> execute();
    }
    catch(Exception $e) {
      SN() -> create_error('could not save the option: ' . $e -> getMessage());
    }
  }
}
