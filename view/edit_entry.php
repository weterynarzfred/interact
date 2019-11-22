<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry // creates a new Entry if empty
*/
$entry = (($entry == -1) ? new Entry() : get_entry($entry));
?>

<div
  class="container view view-edit_entry"
  data-view="edit_entry"
  data-entry="<?php echo $entry->get_id(); ?>"
>
  <div class="column-double text-strip">
    <div class="rmin"></div>
    <div class="return button">return</div>
    <div class="rmin"></div>
    <form class="ajax-form" data-form-action="update_entry">

      <input type="hidden" name="id" value="<?php echo $entry -> get_id(); ?>">
      <div class="input-line">
        <div class="input-label">name</div>
        <input type="text" name="name" value="<?php echo $entry -> get_name(); ?>">
      </div>
      <div class="input-line">
        <div class="input-label">type</div>
        <input type="text" name="type" value="<?php echo $entry -> get_type(); ?>">
      </div>
      <div class="input-line">
        <div class="input-label">state</div>
        <input type="text" name="state" value="<?php echo $entry -> get_state(); ?>">
      </div>

      <?php // entry properties
      $entry_properties = get_option('entry_properties');
      for($i = 0; $i < count($entry_properties); $i++) {
        if($entry_properties[$i][1]) {
      ?>
        <div class="input-line">
          <div class="input-label"><?php echo $entry_properties[$i][0]; ?></div>
          <input
            type="text"
            name="<?php echo $entry_properties[$i][0]; ?>"
            value="<?php echo $entry->get_prop($entry_properties[$i][0]); ?>"
          >
        </div>
      <?php }} ?>

      <div class="rmin"></div>
      <div class="text-right">
        <input type="submit" value="save" class="button">
      </div>

      <div class="text-center">
        <div class="show-more" data-target="#danger-zone">more</div>
      </div>
      <div id="danger-zone" class="hidden text-right">
        <div class="button delete-button">delete</div>
      </div>

    </form>
    <div class="rmin"></div>
  </div>
</div>
