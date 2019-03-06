<?php if(!defined('CONNECTION_TYPE')) die();

class SN {


  // views
  private $view;
  public function set_view($view) {
    $this->view = $view;
  }
  public function display_view() {
    if(!$this->view) $this->view = '404';
    $url = HOME_DIR . '/view/' . $this->view . '.php';
    include HOME_DIR . '/view/header.php';
    if(file_exists($url)) {
      include $url;
    }
    else {
      $this->create_error('file ' . $url . ' not found');
    }
    $this->display_errors();
    include HOME_DIR . '/view/footer.php';
  }


  // error handling
  private $errors = array();
  public function create_error($error) {
    $this->errors[] = $error;
  }
  public function display_errors() {
    array_map(function($e) {
      echo '<div class="sn-error">'.$e.'</div>';
    }, $this->errors);
  }


  // database connection
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
    $url = HOME_DIR . '/config.php';
    $handle = fopen($url, 'w');
    if(!$handle) {
      SN()->create_error('cannot open file: ' . $url);
    }
    fwrite($handle, json_encode($data));
    fclose($handle);
  }
  public function db_connect() {
    if(!isset($this->conn)) {
  		try {
  			$this->conn = new PDO("mysql:host=".$host.";dbname=".$name.";charset=utf8", $user, $pass);
  			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  			$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  		}
  		catch(PDOException $e) {
  			$this->create_error("MySQL Connection failed: " . $e->getMessage());
  		}
  	}
  }


  public function __construct() {

  }

}

function SN() {
  global $SN;
  return $SN;
}

global $SN;
$SN = new SN();
