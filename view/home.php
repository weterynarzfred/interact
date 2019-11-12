<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables: none
*/?>

<div class="container view view-home" data-view="home">

  <?php display_view('part/entry_list'); ?>

  <div class="brick">
    <button
      class="button get-view"
      data-view="edit_entry"
      data-details='<?php echo json_encode(array('entry' => -1)); ?>'
      data-target=".next-container"
    >add new</button>
  </div>

</div>
