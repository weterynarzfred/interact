<div class="entry" id="entry-<?php echo $data->get_ID(); ?>" data-id="<?php echo $data->get_ID(); ?>">
  <div class="flex flex-justify-space-between flex-wrap entry-main-line">
    <div class="entry-name"><?php echo $data->get_name(); ?></div>
    <?php SN()->display_view('single_entry_progress', $data); ?>
  </div>
  <div class="flex flex-justify-space-between flex-wrap">
    <div class="entry-type"><?php echo $data->get_type(); ?></div>
    <div class="entry-date"><?php echo date('Y-m-d G:i:s', $data->get_date()); ?></div>
  </div>
  <div class="flex flex-justify-end flex-wrap">
    <div class="entry-remove"><div class="button remove-entry">remove</div></div>
    <div class="entry-edit"><div class="button edit-entry">edit</div></div>
  </div>
</div>
