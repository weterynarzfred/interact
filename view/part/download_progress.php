<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($entry);
$reader_folder_url = reader_get_folder_url($entry);
$progress_url = $reader_folder_url . '/progress.txt';
$progress = is_file($progress_url) ? file_get_contents($progress_url) : '';
$progress = explode('/', $progress);
?>
<div class="view view-part-download_progress">
	<?php if(isset($url)) {
		$filename = explode('/', $url);
		$filename = urldecode($url);
	?>
	<pre><?php var_dump($filename); ?></pre>
	<pre><?php print_r($progress); ?></pre>
		<?php if(count($progress) >= 2 && $progress[1] > 0) { ?>
	<p><?php echo $progress[0] / $progress[1] * 100 ?>%</p>
		<?php } ?>
	<?php } ?>
</div>
