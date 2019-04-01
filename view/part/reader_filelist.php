<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry // omitted if $files is set
array			$files
*/

if(!isset($files)) {
	$entry = get_entry($entry);
	$files = reader_get_folder($entry);
}
?>

<div class="view view-part-reader_filelist">
	<div class="title">downloaded</div>

	<?php
	if($files) {
		foreach ($files as $file) {
			$classes = array();
			// if($file['chapter'] <= $entry->get_prop('read')) $classes[] = 'read';
			// if($file['chapter'] <= $entry->get_prop('downloaded')) $classes[] = 'downloaded';
	?>
	<div
		class="reader-file file <?php echo implode(' ', $classes); ?>"
		data-url="<?php echo $file['url']; ?>"
	>
		<!-- <div class="reader-chapter-number chapter-number">
			<?php // echo $file['chapter']; ?>
		</div> -->
		<div class="reader-filename filename">
			<?php echo $file['name']; ?>
		</div>
	</div>
	<?php
		}
	}
	else {
	?>
	<p>no files found</p>
	<?php
	}
	?>

	<div class="rmin"></div>
</div>
