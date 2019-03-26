<?php if(!defined('CONNECTION_TYPE')) die();

class Hook {

  private $name;
  private $callbacks = array();

  public function __construct($name) {
    $this->name = $name;
  }

  public function add_callback($callback) {
    $this->callbacks[] = $callback;
  }

  public function apply($data, $additional = NULL) {
    foreach($this->callbacks as $callback) {
      $data = $callback($data, $additional);
    }
    return $data;
  }

}

function add_to_hook($name, $callback) {
  if(!isset(SN()->hooks[$name])) {
    SN()->hooks[$name] = new Hook($name);
  }
  SN()->hooks[$name]->add_callback($callback);
}

function apply_hook($name, $data = NULL, $additional = NULL) {
  if(!isset(SN()->hooks[$name])) {
    SN()->hooks[$name] = new Hook($name);
  }
  return SN()->hooks[$name]->apply($data, $additional);
}
