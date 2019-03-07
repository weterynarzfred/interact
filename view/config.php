<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<form action="" class="ajax-form" data-form-action="config">
  <div class="input">
    <input type="text" name="db_name" placeholder="db_name" value="aaaa">
  </div>
  <div class="input">
    <input type="text" name="db_user" placeholder="db_user" value="root">
  </div>
  <div class="input">
    <input type="password" name="db_password" placeholder="db_password">
  </div>
  <div class="input">
    <input type="text" name="db_host" placeholder="db_host" value="localhost">
  </div>
  <div class="input">
    <input type="submit" value="send">
  </div>
</form>
