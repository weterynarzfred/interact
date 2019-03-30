<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($entry);
$reader_folder_url = reader_get_folder_url($entry);

if(isset($url)) {
	$filename = explode('/', urldecode($url));
	$filename = end($filename);
	$progress_url = $reader_folder_url . '/progress - ' . urldecode($filename) . '.txt';
	$progress = is_file($progress_url) ? file_get_contents($progress_url) : '';
	$progress = explode('/', $progress);
}
?>
<div
	class="view view-part-download_progress"
	id="progress-<?php echo create_slug($filename); ?>"
>
	<?php if(isset($url)) { ?>
	<pre><?php var_dump($filename); ?></pre>
	<pre><?php print_r($progress); ?></pre>
		<?php if(count($progress) >= 2 && $progress[1] > 0) { ?>
	<p><?php echo $progress[0] / $progress[1] * 100 ?>%</p>
		<?php } ?>
	<?php } ?>
</div>
