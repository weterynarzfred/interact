<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<div class="container view view-home" data-view="home">

	<?php display_view('part/entry_list'); ?>

	<div class="brick">
		<div
			class="button get-view"
			data-view="edit_entry"
			data-details='<?php echo json_encode(array('entry'=>-1)); ?>'
			data-target=".next-container"
		>add new</div>
	</div>

</div>
