<?php if(!defined('CONNECTION_TYPE')) die();

class Entry {

  private $entry_id;
  private $name;
  private $read_date;
  private $type;
  private $read;
  private $ready;
  private $props = array(); // properties from entries_meta

  public function __construct($data = array()) {
    $this->name = isset($data['name']) ? $data['name'] : '';
    $this->type = isset($data['type']) ? $data['type'] : '';
    $this->state = isset($data['state']) ? $data['state'] : '';

		if(isset($data['entry_id'])) {
			 $this->entry_id = $data['entry_id'];
	    // get properties from entry_meta table
	    $entry_properties = get_option('entry_properties');
	    if($entry_properties) {
				$entry_property_names = array_map(function($e) {return $e[0];}, $entry_properties);
	      $prop_string = '\'' . implode('\', \'', $entry_property_names) . '\'';
	      try {
	    		$sql = "
	    			SELECT `name`, `value`
	    			FROM `e_interact_entries_meta`
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

  }

  public function get_id() {
    return $this->entry_id;
  }
  public function get_name() {
    return $this->name;
  }
  public function get_type() {
    return $this->type;
  }
  public function get_state() {
    return $this->state;
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
		// if(
		// 	isset($values['read'])
		// 	&& !isset($values['read_date'])
		// ) $values['read_date'] = date('Y-m-d G:i:s');

		if(!isset($values['name'])) $values['name'] = $this->name;
		if(!isset($values['type'])) $values['type'] = $this->type;
		if(!isset($values['state'])) $values['state'] = $this->state;

		try {
			// if entry already in the database
			if($this->entry_id) {
				$sql = "
					UPDATE `e_interact_entries`
					SET
						`name` = :name,
						`type` = :type,
						`state` = :state
					WHERE `entry_id` = :entry_id
				";
				$sql = SN()->db_connect()->prepare($sql);
				$sql->bindParam(':entry_id', $this->entry_id);
			}
			// if entry needs to be created
			else {
				$sql = "
			    INSERT INTO `e_interact_entries` (`name`, `type`, `state`)
			    VALUES (:name, :type, :state)
			  ";
			  $sql = SN()->db_connect()->prepare($sql);
			}
			$sql->bindParam(':name', $values['name']);
			$sql->bindParam(':type', $values['type']);
			$sql->bindParam(':state', $values['state']);
			$sql->execute();

			// get newly created id
			if(!$this->entry_id) {
				$this->entry_id = SN()->db_connect()->lastInsertId();
			}
			$this->name = $values['name'];
			$this->type = $values['type'];
			$this->state = $values['state'];

			$entry_properties = get_option('entry_properties');
			if($entry_properties) {
				$set = array();
				for ($i = 0; $i < count($entry_properties); $i++) {
					if(isset($values[$entry_properties[$i][0]])) {
						$set[] = '(\'' . $this->entry_id . '\', \'' . $entry_properties[$i][0] . '\', :' . $entry_properties[$i][0] . ')';
					}
				}
				if(count($set)) {
					$set_string = 'VALUES ' . implode(', ', $set);
					$sql = "
						INSERT INTO e_interact_entries_meta (`entry_id`, `name`, `value`)
						" . $set_string . "
						ON DUPLICATE KEY UPDATE
						`value` = VALUES(`value`)
					";
					$sql = SN()->db_connect()->prepare($sql);
					for ($i=0; $i < count($entry_properties); $i++) {
						if(isset($values[$entry_properties[$i][0]])) {
							$sql->bindParam(':' . $entry_properties[$i][0], $values[$entry_properties[$i][0]]);
						}
					}
					$sql->execute();
					for ($i = 0; $i < count($entry_properties); $i++) {
						if(isset($values[$entry_properties[$i][0]])) {
							$this->props[$entry_properties[$i][0]] = $values[$entry_properties[$i][0]];
						}
					}
				}
			}
		}
		catch(Exception $e) {
			SN()->create_error('could not update the entry: ' . $e->getMessage());
		}
	}

  public function delete() {
    // delete storage
    $url = HOME_DIR . reader_get_folder_url($this);
    if(is_dir($url)) {
      delete_dir($url);
    }
    try {
      $sql = "
        DELETE FROM `e_interact_entries`
        WHERE `entry_id` = " . $this->entry_id
      ;
      $sql = SN()->db_connect()->prepare($sql);
      $sql->execute();

      $sql = "
        DELETE FROM `e_interact_entries_meta`
        WHERE `entry_id` = " . $this->entry_id
      ;
      $sql = SN()->db_connect()->prepare($sql);
      $sql->execute();
    }
    catch(Exception $e) {
      SN()->create_error('could not retrieve entries: ' . $e->getMessage());
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
			FROM `e_interact_entries`
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
	elseif(!$entry_id) return new Entry();
  try {
    $sql = "
      SELECT `entry_id`, `name`, `type`, `state`
      FROM `e_interact_entries`
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

function create_entry() {
	try {
	  $sql = "
	    INSERT INTO `e_interact_entries` (`name`)
	    VALUES (:name)
	  ";

	  $sql = SN()->db_connect()->prepare($sql);
	  $name = 'tempname';
	  $sql->bindParam(':name', $name);
	  $sql->execute();

		return get_entry(SN()->db_connect()->lastInsertId());
	}
	catch(Exception $e) {
	  SN()->create_error('could not create an entry: ' . $e->getMessage());
	}
}
