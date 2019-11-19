<?php if (!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry
*/
$entry = get_entry($entry);
$classes = array(
  'single-entry',
  'get-view',
);
if ($entry -> get_prop('ready') > $entry -> get_prop('read')) {
  $classes[] = 'single-entry-unread';
}
?>

<div
  class="view view-part-single_entry"
  id="entry-<?php echo $entry -> get_id(); ?>"
>
  <div
    class="<?php echo implode(' ', $classes); ?>"
    data-view="reader"
    data-details='<?php
      echo json_encode(array('entry' => $entry -> get_id()));
    ?>'
    data-target=".next-container"
  >
    <?php $cover = $entry -> get_prop('cover'); ?>
    <div
      class="entry-image<?php echo $cover ? ' lazy-cake' : ''; ?>"
      data-bg="<?php echo $cover; ?>"
    >
      <div class="cake cake-3-4"></div>
      <svg class="loading-icon" viewBox="-10 -10 120 120">
        <circle cx="50" cy="50" r="40" />
      </svg>
    </div>
    <div class="single-entry-info">
      <div></div>
      <div class="entry-name"><?php echo $entry -> get_name(); ?></div>
      <?php display_view(
        'part/single_entry_progress',
        array('entry' => $entry)
      ); ?>
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
    <?php apply_hook('after_single_entry', $entry); ?>
  </div>
</div>
