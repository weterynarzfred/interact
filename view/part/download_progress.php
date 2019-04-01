<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry
string		$url
*/

$entry = get_entry($entry);
$reader_folder_url = HOME_DIR . reader_get_folder_url($entry);

$filename = explode('/', urldecode($url));
$filename = end($filename);
$progress_url = $reader_folder_url . '/progress - ' . urldecode($filename) . '.txt';
$progress = is_file($progress_url) ? file_get_contents($progress_url) : '';
$progress = explode('/', $progress);

$percentage = (count($progress) >= 2 && $progress[1] > 0) ?
	$progress[0] / $progress[1] * 100 : 0;
?>
<div
	class="view view-part-download_progress hidden"
	id="progress-<?php echo create_slug($filename); ?>"
	data-percentage="<?php echo $percentage; ?>"
></div>
