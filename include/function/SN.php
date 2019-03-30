<?php if(!defined('CONNECTION_TYPE')) die();

class SN {

  // views
  public $view;

  // error handling
  private $errors = array();
  public function create_error($error) {
    $this->errors[] = $error;
  }
  public function get_errors() {
    return $this->errors;
    $this->errors = array();
  }
  public function display_errors() {
    array_map(function($e) {
      echo '<div class="sn-error">'.$e.'</div>';
    }, $this->errors);
    $this->errors = array();
  }

  // database connection
  private $conn;
  public function test_db_connection($host, $name, $user, $pass) {
		try {
			$conn = new PDO("mysql:host=".$host.";dbname=".$name.";charset=utf8", $user, $pass);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}
		catch(PDOException $e) {
			$this->create_error("MySQL Connection failed: " . $e->getMessage());
      return false;
		}
    return true;
  }

  public function save_config($data) {
    $url = HOME_DIR . '/config.json';
    $handle = fopen($url, 'w');
    if(!$handle) {
      SN()->create_error('cannot open file: ' . $url);
      return false;
    }
    fwrite($handle, json_encode($data));
    fclose($handle);
    return true;
  }

  public function test_db_tables() {
    $tables_need_to_be_created = false;
    try {
      $sql = "
  			SHOW tables like 'e_interact_options'
  		";
      $sql = $this->db_connect()->prepare($sql);
      $sql->execute();
      $result = $sql->fetchAll(PDO::FETCH_ASSOC);
      $tables_need_to_be_created = count($result) === 0;
    }
    catch(Exception $e) {
  		SN()->create_error('could not retrieve table from db: ' . $e->getMessage());
  	}
    // create tables
    if($tables_need_to_be_created) {
      try {
    		$sql ="CREATE TABLE IF NOT EXISTS `e_interact_options` (
    			`option_id` INT NOT NULL AUTO_INCREMENT,
    			`name` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    			`value` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    			PRIMARY KEY (`option_id`),
    			UNIQUE `name` (`name`(95))
    		) ENGINE = MyISAM";
    		SN()->conn->exec($sql);
        $sql ="CREATE TABLE IF NOT EXISTS `e_interact_entries` (
    			`entry_id` INT NOT NULL AUTO_INCREMENT,
    			`name` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
          `type` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
					`state` SMALLINT,
    			PRIMARY KEY (`entry_id`)
    		) ENGINE = MyISAM";
    		SN()->conn->exec($sql);
        $sql ="CREATE TABLE IF NOT EXISTS `e_interact_entries_meta` (
    			`meta_id` INT NOT NULL AUTO_INCREMENT,
          `entry_id` INT NOT NULL,
    			`name` TINYTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    			`value` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
    			PRIMARY KEY (`meta_id`),
    			UNIQUE `entry_meta` (`entry_id`, `name`(95))
    		) ENGINE = MyISAM";
    		SN()->conn->exec($sql);
    	} catch(PDOException $e) {
    		SN()->create_error($e->getMessage());
    	}
    }

    return $tables_need_to_be_created;
  }

  public function db_connect() {
    if(!isset($this->conn)) {
      // $url = HOME_DIR . '/config.json';
      // $string = file_get_contents($url);
      // $config = json_decode($string, true);
  		try {
  			$this->conn = new PDO("mysql:host=".get_option('db_host').";dbname=".get_option('db_name').";charset=utf8", get_option('db_user'), get_option('db_password'));
  			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  			$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  		}
  		catch(PDOException $e) {
  			$this->create_error("MySQL Connection failed: " . $e->getMessage());
        return false;
  		}
  	}
    return $this->conn;
  }

  public function __construct() {}

}

function SN() {
  global $SN;
  return $SN;
}

global $SN;
$SN = new SN();
