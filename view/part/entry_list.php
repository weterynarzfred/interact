<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<div class="view view-part-entry_list">
	<div class="entry-list">
		<pre>
			<?php
			$entries = get_entries();
			print_r($entries);
			?>
		</pre>
		<?php
		if($entries) {
			foreach ($entries as $entry) {
				display_view('part/single_entry', array('entry'=>$entry));
			}
		}
		else { ?>
			<p>no entries found</p>
		<?php } ?>
	</div>
</div>
