<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<div class="container">
  <div class="rmin"></div>
  <div class="column-double">
    <form action="" class="ajax-form" data-form-action="config">
      <div class="input-line">
        <div class="input-label">db name:</div>
        <input type="text" name="db_name" placeholder="db_name" value="aaaa">
      </div>
      <div class="input-line">
        <div class="input-label">db user:</div>
        <input type="text" name="db_user" placeholder="db_user" value="root">
      </div>
      <div class="input-line">
        <div class="input-label">db pass:</div>
        <input type="password" name="db_password" placeholder="db_password">
      </div>
      <div class="input-line">
        <div class="input-label">db host:</div>
        <input type="text" name="db_host" placeholder="db_host" value="localhost">
      </div>
      <div class="text-right">
        <input type="submit" value="send" class="button">
      </div>
    </form>
  </div>
  <div class="rmin"></div>
</div>
