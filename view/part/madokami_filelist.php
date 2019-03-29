<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($entry);
if(!isset($skip_check) || !$skip_check) {
	$madokami_files = reader_get_madokami_files($entry);
}
?>

<div class="view view-madokami_filelist">
	madokami
	<?php
	if(!isset($skip_check) || !$skip_check) {
		if($madokami_files) {
			foreach($madokami_files as $file) {
	?>
	<div
		class="madokami-file"
		data-id="<?php echo $entry->get_id(); ?>"
		data-url="https://manga.madokami.al<?php echo $file['url']; ?>"
	><?php echo $file['name']; ?></div>
	<?php
			}
		}
		else { ?>
	<p>no files found</p>
		<?php }
	}
	?>

	<div class="rmin"></div>

	<div
		class="button get-view"
		data-view="part/madokami_filelist"
		data-details='<?php echo json_encode(array('entry'=>$entry->get_id())); ?>'
		data-target=".view-madokami_filelist"
	>check madokami</div>

	<div class="rmin"></div>

</div>
