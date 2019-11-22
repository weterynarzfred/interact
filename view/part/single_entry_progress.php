<?php if (!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry
*/

$entry = get_entry($entry);
?>
<div class="view view-part-single_entry_progress entry-progress flex">
  <div class="entry-progress-col">
    <span class="entry-progress-separator">read:</span>
    <span class="entry-progress-value"><?php echo $entry -> get_prop('read'); ?></span>
  </div>
  <div class="entry-progress-col">
    <span class="entry-progress-separator">down:</span>
    <span class="entry-progress-value"><?php echo $entry -> get_prop('downloaded'); ?></span>
  </div>
  <div class="entry-progress-col">
    <span class="entry-progress-separator">ready:</span>
    <span class="entry-progress-value"><?php echo $entry -> get_prop('ready'); ?></span>
  </div>
  <?php apply_hook('after_single_entry_progress', $entry); ?>
</div>
