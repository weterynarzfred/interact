<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<div class="view view-part-single_entry single-entry brick">
	<div class="entry-name"><?php echo $entry->get_name(); ?></div>
	<?php display_view('part/single_entry_progress', array('entry'=>$entry)); ?>
	<div class="entry-image"></div>
	<div
		class="button get-view"
		data-view="edit_entry"
		data-details='<?php echo json_encode(array('entry'=>$entry->get_id())); ?>'
		data-target=".next-container"
	>edit</div>
</div>
