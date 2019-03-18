<?php if(!defined('CONNECTION_TYPE')) die(); ?>
<div class="entry-progress">
  <?php $ready = $data->get_ready(); ?>
  <span class="entry-progress-separator">r:</span><span class="entry-read"><?php echo $data->get_read(); ?></span>
  <?php if($ready !== 0) { ?>
  <span class="entry-progress-separator">c:</span><span class="entry-ready"><?php echo $ready; ?></span>
  <?php } ?>
	<?php apply_hook('after_single_entry_progress', $data); ?>
</div>
