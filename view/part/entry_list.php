<div class="entries">
  <?php
  if($data) {
    foreach ($data as $entry) {
      display_view('part/single_entry', $entry); ?>
      <div class="rmik"></div>
    <?php }
  }
  ?>
</div>
<div class="rmin"></div>
<div class="button add-entry">add a new entry</div>
