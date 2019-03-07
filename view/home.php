you are inside<br>
<br>
here are your entries:<br>
<?php
$entries = get_entries();
SN()->display_view('entry_list', $entries);
?>
