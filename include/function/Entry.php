<?php if(!defined('CONNECTION_TYPE')) die();

class Entry {


  private $ID;
  private $name;
  private $date;
  private $read;
  private $ready;

  public function __construct($data) {
    $this->ID = $data['ID'];
    $this->name = $data['name'];
    $this->date = strtotime($data['date']);
    $this->type = $data['type'];
    $this->read = $data['read'];
    $this->ready = $data['ready'];
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
    return new Entry($result[0]);
  }
  catch(Exception $e) {
    SN()->create_error('could not retrieve entries: ' . $e->getMessage());
  }
}
