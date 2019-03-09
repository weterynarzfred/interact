<div class="title">you are inside</div><br>
<div class="rmin"></div>
<p>here are your entries:</p>
<div class="rmin"></div>
<?php
$entries = get_entries();
display_view('part/entry_list', $entries);
?>
