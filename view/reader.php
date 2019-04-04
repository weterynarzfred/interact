<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry
*/

$entry = get_entry($entry);
$files = reader_get_files($entry);
?>

<div
	class="container view view-reader"
	data-view="reader"
	data-entry="<?php echo $entry->get_id(); ?>"
  data-read="<?php echo $entry->get_prop('read'); ?>"
>

	<div class="column-double">
		<div class="text-strip">
			<div class="rmin"></div>
			<div class="return button">return</div>
			<div class="rmin"></div>

			<form class="ajax-form" data-form-action="update_entry">
				<input type="hidden" name="id" value="<?php echo $entry->get_id(); ?>">
				<div class="input-line">
					<div class="input-label">read:</div>
					<input type="text" name="read" value="<?php echo $entry->get_prop('read'); ?>">
				</div>
				<div class="text-right hidden">
					<input type="submit" value="save" class="button">
				</div>
			</form>

			<div class="rmin"></div>
			<div class="reader-entry-name"><?php echo $entry->get_name(); ?></div>
			<div class="rmin"></div>
		</div>
	</div>

	<?php display_view('part/reader_filelist', array(
		'entry'=>$entry,
		'files'=>$files,
	)); ?>

	<div class="column-double">
		<div class="text-strip">
			<?php display_view('part/madokami_filelist', array(
				'entry'	=>	$entry,
				'skip_check'	=>	true,
				'files'	=>	$files,
			)); ?>
		</div>
	</div>

</div>
