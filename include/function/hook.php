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

  public function apply($data) {
    foreach($this->callbacks as $callback) {
      $data = $callback($data);
    }
    return $data;
  }

}

function register_hook($name) {
  SN()->hooks[$name] = new Hook($name);
}

function add_to_hook($name, $callback) {
  SN()->hooks[$name]->add_callback($callback);
}

function apply_hook($name, $data = NULL) {
  return SN()->hooks[$name]->apply($data);
}


/*
usage:
first call
register_hook('[hook name]');

then add callbacks (eg in plugins)
add_to_hook('[hook name]', [callback function]);

and finally apply when needed
$result = SN()->hooks['[hook name]']->apply($result);
*/
