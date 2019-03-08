<div class="entries">
  <?php
  if(count($data)) {
    foreach ($data as $entry) {
      SN()->display_view('single_entry', $entry);
    }
  }
  ?>
</div>
<button class="add-entry">add</button>
