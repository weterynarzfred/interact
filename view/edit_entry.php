<?php if(!defined('CONNECTION_TYPE')) die();
$entry = (($entry == -1) ? new Entry() : get_entry($entry));
?>

<div class="container view view-edit_entry">
	<div class="column-double">
		<div class="rmin"></div>
		<div class="return button">return</div>
	  <div class="rmin"></div>
	  <form action="" class="ajax-form" data-form-action="update_entry">

			<input type="hidden" name="id" value="<?php echo $entry->get_id(); ?>">
			<div class="input-line">
	      <div class="input-label">name:</div>
	      <input type="text" name="name" value="<?php echo $entry->get_name(); ?>">
	    </div>
			<div class="input-line">
	      <div class="input-label">type:</div>
	      <input type="text" name="type" value="<?php echo $entry->get_type(); ?>">
	    </div>
	    <div class="input-line">
	      <div class="input-label">state:</div>
	      <input type="text" name="state" value="<?php echo $entry->get_state(); ?>">
	    </div>

			<?php // entry properties
		  $entry_properties = get_option('entry_properties');
		  for ($i=0; $i < count($entry_properties); $i++) { ?>
		    <div class="input-line">
		      <div class="input-label"><?php echo $entry_properties[$i]; ?></div>
		      <input
		        type="text"
		        name="<?php echo $entry_properties[$i]; ?>"
		        value="<?php echo $entry->get_prop($entry_properties[$i]); ?>"
		      >
		    </div>
		  <?php } ?>

			<div class="rmin"></div>
			<div class="text-right">
				<input type="submit" value="save" class="button">
			</div>

		</form>
		<div class="rmin"></div>
	</div>
</div>






<?php if(false) { ?>
<div class="container">

	<div class="rmin"></div>
	<div class="text-center">
		<svg class="show-more" data-target="#edit-entry-danger" viewBox="0 0 100 50">
			<path d="M10 10L50 40L90 10" />
		</svg>
	</div>
	<div class="text-right hidden" id="edit-entry-danger">
		<div class="rmik"></div>
		<div class="entry-remove navigation-link" data-target="home">
			<div class="button remove-entry" data-id="<?php echo $entry->get_ID(); ?>">remove entry</div>
		</div>
	</div>

</div>

<?php } ?>
