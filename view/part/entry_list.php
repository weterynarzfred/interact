<?php if(!defined('CONNECTION_TYPE')) die(); ?>
<div class="entries">
<?php
if($data) {
	usort($data, function($a, $b) {
		return $b->get_prop('left_downloaded') > $a->get_prop('left_downloaded');
	});
  foreach ($data as $entry) {
    display_view('part/single_entry', $entry); ?>
  <?php }
}
?>
</div>
<div class="rmin"></div>
<div class="button add-entry">add a new entry</div>
