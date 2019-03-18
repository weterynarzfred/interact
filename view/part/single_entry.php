<?php if(!defined('CONNECTION_TYPE')) die();
$left_downloaded = intval($data->get_prop('left_downloaded')) > 0;
$needs_download = $data->get_ready() > $data->get_read();
?>
<div
	class="
		entry
		<?php echo $left_downloaded ? 'entry-left-downloaded' : ''; ?>
		<?php echo $needs_download ? 'entry-needs-download' : ''; ?>
	"
	id="entry-<?php echo $data->get_ID(); ?>"
	data-id="<?php echo $data->get_ID(); ?>"
>

	<div class="flex flex-align-center">

		<div class="entry-main-column">
			 <div class="text-center">
				<div class="flex flex-justify-space-between flex-align-end flex-wrap entry-main-line">
				  <div class="entry-name"><?php echo $data->get_name(); ?></div>
				  <?php display_view('part/single_entry_progress', $data); ?>
				</div>

				<div class="flex flex-justify-space-between flex-wrap">
				  <div class="entry-type"><?php echo $data->get_type(); ?></div>
				  <div class="entry-date"><?php echo date('Y-m-d G:i:s', $data->get_date()); ?></div>
				</div>
			</div>
		</div>

	  <div class="flex flex-justify-end entry-icons-column">
	    <div
	      class="entry-edit navigation-link"
	      data-target="edit_entry"
	      data-value="<?php echo $data->get_ID(); ?>"
	    >
	      <div class="edit-entry icon link">&#x270e;</div>
	    </div>
			<?php apply_hook('after_single_entry_buttons', $data); ?>
	  </div>

	</div>

  <?php apply_hook('after_single_entry', $data); ?>

</div>
