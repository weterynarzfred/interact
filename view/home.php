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

add_to_hook('get_option_entry_attributes', function($a) {
  $a[] = 'a';
  return $a;
});

function add_b($a) {
  $a[] = 'b';
  return $a;
}
add_to_hook('get_option_entry_attributes', 'add_b');

print_r(SN()->options);

print_r(get_option('entry_attributes'));

?>
</pre>
