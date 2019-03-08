<?php
$entry = get_entry($data['id']);
?>
<div class="button navigation-link" data-target="home">return</div>
<div class="rmin"></div>
<form action="" class="ajax-form" data-form-action="update_entry">
  <input type="hidden" name="id" value="<?php echo $entry->get_ID(); ?>">
  <div class="input-line">
    <input type="text" name="name" value="<?php echo $entry->get_name(); ?>">
  </div>
  <div class="text-right">
    <input type="submit" value="save" class="button">
  </div>
</form>
