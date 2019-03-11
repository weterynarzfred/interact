<div class="title">you are inside</div><br>
<div class="rmin"></div>
<p>here are your entries:</p>
<div class="rmin"></div>
<?php
$entries = get_entries();
display_view('part/entry_list', $entries);
?>

<div class="r"></div>
<pre>
<?php

print_r(SN()->options);

print_r(get_option('entry_properties'));

print_r($entries);

?>
</pre>
