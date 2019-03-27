<?php if(!defined('CONNECTION_TYPE')) die(); ?>
<div class="view view-part-single_entry_progress entry-progress flex">
	<div class="entry-progress-col">
		<span class="entry-progress-separator">r:</span><span class="entry-read"><?php echo $entry->get_read(); ?></span>
	</div>
	<div class="entry-progress-col">
		<span class="entry-progress-separator">d:</span><span class="entry-ready"><?php echo $entry->get_prop('downloaded'); ?></span>
	</div>
	<div class="entry-progress-col">
		<span class="entry-progress-separator">c:</span><span class="entry-ready"><?php echo $entry->get_prop('ready'); ?></span>
	</div>
	<?php apply_hook('after_single_entry_progress', $data); ?>
</div>
