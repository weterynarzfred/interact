<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<div class="container">
  <div class="title">here are your entries:</div><br>
  <div class="rmin"></div>
  <?php
  $entries = get_entries();
  display_view('part/entry_list', $entries);
  ?>
</div>
