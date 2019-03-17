<div class="entry" id="entry-<?php echo $data->get_ID(); ?>" data-id="<?php echo $data->get_ID(); ?>">

	<div class="flex flex-align-center">

		<div class="entry-main-column">
			 <div class="text-center">
				<div class="flex flex-justify-space-between flex-wrap entry-main-line">
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
