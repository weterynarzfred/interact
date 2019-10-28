<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables: none
*/
?>

<div class="view view-part-entry_list">
  <div class="entry-list column">
    <?php
    $entries = get_entries();
    if ($entries) {
      foreach ($entries as $entry) {
        display_view('part/single_entry', array('entry' => $entry));
      }
    }
    ?>
  </div>
</div>
