<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<div class="container">
  <?php
  $entry = get_entry($data['id']);
  ?>
  <div class="button navigation-link" data-target="home">return</div>
  <div class="rmin"></div>
  <form action="" class="ajax-form" data-form-action="update_entry">
    <input type="hidden" name="id" value="<?php echo $entry->get_ID(); ?>">
    <div class="input-line">
      <div class="input-label">name:</div>
      <input type="text" name="name" value="<?php echo $entry->get_name(); ?>">
    </div>
    <div class="input-line">
      <div class="input-label">type:</div>
      <input type="text" name="type" value="<?php echo $entry->get_type(); ?>">
    </div>
    <div class="input-line">
      <div class="input-label">read:</div>
      <input type="text" name="read" value="<?php echo $entry->get_read(); ?>">
    </div>
    <div class="input-line">
      <div class="input-label">ready:</div>
      <input type="text" name="ready" value="<?php echo $entry->get_ready(); ?>">
    </div>

  <?php // entry properties

  $entry_properties = get_option('entry_properties');
  for ($i=0; $i < count($entry_properties); $i++) { ?>
    <div class="input-line">
      <div class="input-label"><?php echo $entry_properties[$i]; ?></div>
      <input
        type="text"
        name="<?php echo $entry_properties[$i]; ?>"
        value="<?php echo $entry->get_prop($entry_properties[$i]); ?>"
      >
    </div>
  <?php } ?>

    <div class="text-right">
      <input type="submit" value="save" class="button">
    </div>
  </form>
</div>
