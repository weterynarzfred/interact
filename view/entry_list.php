<div class="entries">
  <?php
  if(count($data)) {
    foreach ($data as $entry) {
      SN()->display_view('single_entry', $entry); ?>
      <div class="rmik"></div>
    <?php }
  }
  ?>
</div>
<div class="rmin"></div>
<div class="button add-entry">add a new entry</div>
