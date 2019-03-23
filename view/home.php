<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<div class="container">
  <div class="title">here are your entries:</div><br>
  <div class="rmin"></div>
  <?php
  $entries = get_entries($data);
	$reading = array();
	$ready = array();
	$waiting = array();
	$finished = array();
	foreach ($entries as $entry) {
		if(intval($entry->get_prop('left_downloaded')) > 0) $reading[] = $entry;
		elseif(
			$entry->get_ready() > intval($entry->get_prop('reader_downloaded'))
			&& $entry->get_ready() > $entry->get_read()
		) $ready[] = $entry;
		elseif($entry->get_prop('is_finished')) $finished[] = $entry;
		else $waiting[] = $entry;
	}
	$entries = array_merge($reading, $ready, $waiting, $finished);
  display_view('part/entry_list', $entries);
  ?>
	<div class="button navigation-link" data-target="home" data-value='<?php echo json_encode(array('sort_by'=>'name')); ?>'>sort by title</div>
	<div class="button navigation-link" data-target="home" data-value='<?php echo json_encode(array('sort_by'=>'read_date')); ?>'>sort by read date</div>
</div>
