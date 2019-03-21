<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($data);
?>
<div class="container">
  <div class="button navigation-link return-button" data-target="home">return</div>
  <div class="rmin"></div>
  <form
		class="ajax-form"
		data-form-action="update_entry"
		data-details="reader_update_progress"
		data-target="#reader-manga-file-list"
	>
		<input type="hidden" name="id" value="<?php echo $entry->get_ID(); ?>">
		<div class="input-line">
      <div class="input-label">read:</div>
      <input type="text" name="read" value="<?php echo $entry->get_read(); ?>">
    </div>
		<div class="text-right">
      <input type="submit" value="save" class="button">
    </div>
	</form>
  <div class="rmik"></div>
	<?php display_view(
		'part/reader_filelist',
		array(
			'entry'	=>	$entry,
		)
	); ?>
</div>
