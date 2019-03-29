<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($entry);
$reader_folder_url = reader_get_folder_url($entry);
$url = $reader_folder_url . '/progress.txt';
$progress = is_file($url) ? file_get_contents($url) : '';
?>
<div class="view view-part-download_progress">
	<pre><?php var_dump($progress); ?></pre>
</div>
