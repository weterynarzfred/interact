<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry
bool			$skip_check = false
array			$files = reader_get_files($entry)
*/

$entry = get_entry($entry);
if(!isset($files)) {
	$files = reader_get_files($entry);
}
$filenames = array_map(function($e) {return $e['name'];}, $files);
?>

<div class="view view-part-madokami_filelist">
	<div class="title">madokami</div>

	<?php
	if(!isset($skip_check) || !$skip_check) {
		$madokami_files = reader_get_madokami_files($entry);
	}
	else {
		$madokami_files = $entry->get_prop('madokami_filelist');
	}
	if($madokami_files) {
		foreach($madokami_files as $madokami_file) {
			$file_slug = create_slug($madokami_file['filename']);
			$classes = array();
			if($madokami_file['chapter'] <= $entry->get_prop('read')) $classes[] = 'read';
			if(in_array($madokami_file['chapter'], $filenames)) $classes[] = 'downloaded';
	?>
	<div
		class="madokami-file file <?php echo implode(' ', $classes); ?>"
		data-id="<?php echo $entry->get_id(); ?>"
		data-url="https://manga.madokami.al<?php echo $madokami_file['url']; ?>"
		data-file-slug="<?php echo $file_slug; ?>"
		data-name="<?php echo $madokami_file['name']; ?>"
	>
		<span class="madokami-chapter-number chapter-number">
			<?php echo $madokami_file['chapter']; ?>
		</span>
		<span class="madokami-filename filename">
			<?php echo $madokami_file['filename']; ?>
		</span>
		<div class="madokami-download-progress-bar">
			<div class="progress"></div>
			<?php display_view('part/download_progress', array(
				'entry'	=>	$entry,
				'url'	=>	$madokami_file['url'],
        'filename' => $madokami_file['filename'],
			)); ?>
		</div>
	</div>
	<?php
		}
	}
	else { ?>
	<p>no files found</p>
	<?php } ?>

	<div class="rmin"></div>

	<div
		class="button get-view"
		data-view="part/madokami_filelist"
		data-details='<?php echo json_encode(array('entry'=>$entry->get_id())); ?>'
		data-target=".view-part-madokami_filelist"
	>check madokami</div>

	<div class="last-check-date">
		<time class="timeago" datetime="<?php echo date(DATE_ISO8601, $entry->get_prop('madokami_last_check')); ?>"></time>
	</div>

	<div class="rmin"></div>

</div>
