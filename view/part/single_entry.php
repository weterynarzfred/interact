<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry
*/
$entry = get_entry($entry);
?>

<div
  class="view view-part-single_entry column"
  id="entry-<?php echo $entry -> get_id(); ?>"
>
  <div class="rmin"></div>
  <div
    class="single-entry get-view"
    data-view="reader"
    data-details='<?php
      echo json_encode(array('entry' => $entry -> get_id()));
    ?>'
    data-target=".next-container"
  >
    <div class="entry-name"><?php echo $entry -> get_name(); ?></div>
    <?php display_view(
      'part/single_entry_progress',
      array('entry' => $entry)
    ); ?>
    <?php $cover = $entry->get_prop('cover'); ?>
    <div
      class="entry-image<?php echo $cover ? ' lazy-cake' : ''; ?>"
      data-bg="<?php echo $cover; ?>"
    >
      <div class="cake cake-3-4"></div>
      <svg class="loading-icon" viewBox="-10 -10 120 120">
        <circle cx="50" cy="50" r="40" />
      </svg>
    </div>
    <div class="entry-buttons">
      <div
        class="button get-view"
        data-view="edit_entry"
        data-details='<?php
          echo json_encode(array('entry' => $entry -> get_id()));
        ?>'
        data-target=".next-container"
      >edit</div>
    </div>
  </div>
</div>
