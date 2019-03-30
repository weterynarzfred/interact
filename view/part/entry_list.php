<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<div class="view view-part-entry_list">
	<div class="entry-list flex flex-wrap flex-justify-space-around flex-align-start column">
		<?php
		$entries = get_entries();
		if($entries) {
			foreach ($entries as $entry) {
				display_view('part/single_entry', array('entry'=>$entry));
			}
		}
		?>
	</div>
</div>
