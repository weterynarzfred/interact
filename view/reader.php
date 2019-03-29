<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($entry);
$files = reader_get_folder($entry);
?>

<div class="container view view-reader">
	<div class="column-double">
		<div class="rmin"></div>
		<div class="return button">return</div>
		<div class="rmin"></div>

		<?php echo $entry->get_name(); ?>

		<div class="rmin"></div>

		downloaded
		<pre><?php print_r($files); ?></pre>

		<div class="rmin"></div>

		<?php display_view('part/madokami_filelist', array(
			'entry'	=>	$entry,
			'skip_check'	=>	true,
		)); ?>

	</div>
</div>
