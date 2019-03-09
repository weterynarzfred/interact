<?php if(!defined('CONNECTION_TYPE')) die();

class Options {

  public function __construct($args = NULL) {
    $this->set_options($args);
  }

  public function set_options($args = NULL) {
    if(isset($args)) {
      foreach($args as $key => $arg) {
        register_hook('get_option_' . $key);
        $this->{$key} = $arg;
      }
    }
  }

}


function get_option($name) {
  $result = SN()->options->{$name};
  $result = SN()->hooks['get_option_' . $name]->apply($result);
  return $result;
}
