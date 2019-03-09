<div class="entry-progress">
  <?php $ready = $data->get_ready(); ?>
  <span class="entry-read"><?php echo $data->get_read(); ?></span>
  <?php if($ready !== 0) { ?>
   / <span class="entry-ready"><?php echo $ready ?></span>
  <?php } ?>
</div>
