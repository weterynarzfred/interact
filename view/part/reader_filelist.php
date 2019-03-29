<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($entry);
$files = reader_get_folder($entry);
?>

<div class="view view-part-reader_filelist">
	downloaded
	<pre><?php print_r($files); ?></pre>

	<div class="rmin"></div>
</div>
