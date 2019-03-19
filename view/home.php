<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<div class="container">
  <div class="title">here are your entries:</div><br>
  <div class="rmin"></div>
  <?php
  $entries = get_entries($data);
  display_view('part/entry_list', $entries);
  ?>
	<div class="button navigation-link" data-target="home" data-value='<?php echo json_encode(array('sort_by'=>'name')); ?>'>sort by title</div>
	<div class="button navigation-link" data-target="home" data-value='<?php echo json_encode(array('sort_by'=>'read_date')); ?>'>sort by read date</div>
</div>
