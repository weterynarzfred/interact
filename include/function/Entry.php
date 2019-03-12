<?php if(!defined('CONNECTION_TYPE')) die();

class Entry {


  private $ID;
  private $name;
  private $date;
  private $type;
  private $read;
  private $ready;
  private $props = array(); // properties from entries_meta

  public function __construct($data) {
    $this->ID = $data['ID'];
    $this->name = $data['name'];
    $this->date = strtotime($data['date']);
    $this->type = $data['type'];
    $this->read = $data['read'];
    $this->ready = $data['ready'];

    // get properties from entry_meta table
    $entry_properties = get_option('entry_properties');
    if($entry_properties) {
      $prop_string = '\'' . implode('\', \'', $entry_properties) . '\'';
      try {
    		$sql = "
    			SELECT `name`, `value`
    			FROM `interact_entries_meta`
          WHERE `entry_ID` = " . $this->ID . "
          AND `name` in (" . $prop_string . ")
        ";

    		$sql = SN()->db_connect()->prepare($sql);

    		$sql->execute();
    		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
        for ($i=0; $i < count($result); $i++) {
          $this->props[$result[$i]['name']] = $result[$i]['value'];
        }
    	}
    	catch(Exception $e) {
    		SN()->create_error('could not retrieve entry properties: ' . $e->getMessage());
    	}
    }

  }

  public function get_ID() {
    return $this->ID;
  }
  public function get_name() {
    return $this->name;
  }
  public function get_date() {
    return $this->date;
  }
  public function get_type() {
    return $this->type;
  }
  public function get_read() {
    return $this->read;
  }
  public function get_ready() {
    return $this->ready;
  }
  public function get_prop($name) {
    if(!isset($this->props[$name])) {
      return false;
    }
    return $this->props[$name];
  }
}

function get_entries() {
  try {
		$sql = "
			SELECT `ID`, `name`, `date`, `type`, `read`, `ready`
			FROM `interact_entries`
      ORDER BY `date` DESC
      LIMIT 5 OFFSET 0
		";

		$sql = SN()->db_connect()->prepare($sql);

		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
    $entries = array();
    for ($i=0; $i < count($result); $i++) {
      $entries[] = new Entry($result[$i]);
    }
		return $entries;
	}
	catch(Exception $e) {
		SN()->create_error('could not retrieve entries: ' . $e->getMessage());
	}
}

function get_entry($ID) {
  try {
    $sql = "
      SELECT `ID`, `name`, `date`, `type`, `read`, `ready`
      FROM `interact_entries`
      WHERE `ID` = :id
    ";

    $sql = SN()->db_connect()->prepare($sql);
    $sql->bindParam(':id', $ID);

    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    if(!$result) return false;
    return new Entry($result[0]);
  }
  catch(Exception $e) {
    SN()->create_error('could not retrieve entries: ' . $e->getMessage());
  }
}
