<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($entry);
$files = reader_get_folder($entry);
$madokami_files = reader_get_madokami_files($entry);
?>

<div class="container view view-reader">
	<div class="column-double">
		<div class="rmin"></div>
		<div class="return button">return</div>
		<div class="rmin"></div>

		<?php echo $entry->get_name(); ?>

		<div class="rmin"></div>

		<pre><?php print_r($files); ?></pre>
		<pre><?php print_r($madokami_files); ?></pre>

		<div class="rmin"></div>
	</div>
</div>
