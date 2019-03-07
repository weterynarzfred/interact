<div class="entries">
  <?php
  if(count($data)) {
    foreach ($data as $entry) {
  ?>
  <div class="entry" data-id="<?php echo $entry->get_ID(); ?>">
    <div class="entry-name"><?php echo $entry->get_name(); ?></div>
    <div class="entry-date"><?php echo date('Y-m-d G:i:s', $entry->get_date()); ?></div>
  </div>
  <?php
    }
  }
  ?>
</div>
