<?php if(!defined('CONNECTION_TYPE')) die();

class Entry {

  private $entry_id;
  private $name;
  private $read_date;
  private $type;
  private $read;
  private $ready;
  private $props = array(); // properties from entries_meta

  public function __construct($data) {
    $this->entry_id = $data['entry_id'];
    $this->name = $data['name'];
    $this->type = $data['type'];
    $this->state = $data['state'];

    // get properties from entry_meta table
    $entry_properties = get_option('entry_properties');
    if($entry_properties) {
      $prop_string = '\'' . implode('\', \'', $entry_properties) . '\'';
      try {
    		$sql = "
    			SELECT `name`, `value`
    			FROM `interact_entries_meta`
          WHERE `entry_id` = " . $this->entry_id . "
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
    return $this->entry_id;
  }
  public function get_name() {
    return $this->name;
  }
  public function get_read_date() {
    return $this->read_date;
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
      return '';
    }
    $prop = $this->props[$name];
    $prop = apply_hook('get_prop_' . $name, $prop, $this);
    return $prop;
  }

	public function update($values) {
		$values = apply_hook('update_entry', $values, $this);

		// update the read date if reading progress is updated
		if(
			isset($values['read'])
			&& !isset($values['read_date'])
		) $values['read_date'] = date('Y-m-d G:i:s');

		if(!isset($values['name'])) $values['name'] = $this->name;
		if(!isset($values['type'])) $values['type'] = $this->type;
		if(!isset($values['state'])) $values['state'] = $this->state;

		try {
			$sql = "
				UPDATE `interact_entries`
				SET
					`name` = :name,
					`type` = :type,
					`state` = :state,
				WHERE `entry_id` = :entry_id
			";
			$sql = SN()->db_connect()->prepare($sql);
			$sql->bindParam(':entry_id', $this->entry_id);
			$sql->bindParam(':name', $values['name']);
			$sql->bindParam(':type', $values['type']);
			$sql->bindParam(':state', $values['state']);
			$sql->execute();
			$entry_properties = get_option('entry_properties');
			if($entry_properties) {
				$set = array();
				for ($i=0; $i < count($entry_properties); $i++) {
					if(isset($values[$entry_properties[$i]])) {
						$set[] = '(\'' . $this->entry_id . '\', \'' . $entry_properties[$i] . '\', :' . $entry_properties[$i] . ')';
					}
				}
				if(count($set)) {
					$set_string = 'VALUES ' . implode(', ', $set);
					$sql = "
						INSERT INTO interact_entries_meta (`entry_id`, `name`, `value`)
						" . $set_string . "
						ON DUPLICATE KEY UPDATE
						`value` = VALUES(`value`)
					";
					$sql = SN()->db_connect()->prepare($sql);
					for ($i=0; $i < count($entry_properties); $i++) {
						if(isset($values[$entry_properties[$i]])) {
							$sql->bindParam(':' . $entry_properties[$i], $values[$entry_properties[$i]]);
						}
					}
					$sql->execute();
				}
			}
		}
		catch(Exception $e) {
			SN()->create_error('could not update the entry: ' . $e->getMessage());
		}
	}

}

function get_entries($options = array()) {
	$sort_by = 'state';
	if(isset($options['sort_by'])) {
		if(in_array($options['sort_by'], array('entry_id', 'name', 'type', 'state'))) {
			$sort_by = $options['sort_by'];
		}
	}
  try {
		$sql = "
			SELECT `entry_id`, `name`, `type`, `state`
			FROM `interact_entries`
      ORDER BY " . $sort_by . " DESC
      LIMIT 50 OFFSET 0
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

function get_entry($entry_id) {
	if($entry_id instanceof Entry) return $entry_id;
  try {
    $sql = "
      SELECT `entry_id`, `name`, `type`, `state`
      FROM `interact_entries`
      WHERE `entry_id` = :entry_id
    ";

    $sql = SN()->db_connect()->prepare($sql);
    $sql->bindParam(':entry_id', $entry_id);

    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    if(!$result) return false;
    return new Entry($result[0]);
  }
  catch(Exception $e) {
    SN()->create_error('could not retrieve entries: ' . $e->getMessage());
  }
}
