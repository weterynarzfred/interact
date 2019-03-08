<div class="entry" id="entry-<?php echo $data->get_ID(); ?>" data-id="<?php echo $data->get_ID(); ?>">
  <div class="entry-name"><?php echo $data->get_name(); ?></div>
  <div class="entry-date"><?php echo date('Y-m-d G:i:s', $data->get_date()); ?></div>
  <div class="entry-remove"><button class="remove-entry">remove</button></div>
</div>
