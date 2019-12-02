<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables: none
*/

$entries = get_entries();
?>

<div class="view view-part-entry_list">
  <?php apply_hook('before_entry_list', $entries); ?>
  <div class="entry-list column">
    <?php
    if ($entries) {
      foreach ($entries as $entry) {
        display_view('part/single_entry', array('entry' => $entry));
      }
    }
    ?>
  </div>
</div>
