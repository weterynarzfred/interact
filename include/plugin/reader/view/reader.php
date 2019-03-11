here you will be able to read your manga

<pre>
<?php
$entry = get_entry($data);
$url_base = 'F:/interact_reader/';
$url = $url_base . $entry->get_prop('reader_folder');
$files = scandir($url);
$files = array_map(function($f) use ($url) {return $url . '/' . $f;}, $files);
$files = array_filter($files, function($f) {return is_file($f);});
var_dump($files);
?>
</pre>
