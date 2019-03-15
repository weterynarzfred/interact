<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($data);
$url_base = get_option('manga_url');
$url = $url_base . $entry->get_prop('reader_folder');
$files = scandir($url);
$files = array_map(function($f) use ($url) {return $url . '/' . $f;}, $files);
$files = array_filter($files, function($f) {return is_file($f);});
?>

<div class="container">
  <div class="button navigation-link" data-target="home">return</div>
  <div class="rmin"></div>
  <form action="" class="ajax-form" data-form-action="update_entry">
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
  <?php
  if($files) {
    foreach ($files as $file) {
      $url = explode('/', $file); ?>
      <div
        class="navigation-link reader-manga-file"
        data-target="reader_manga"
        data-value='<?php
        echo json_encode(array(
          'entry' =>  $entry->get_ID(),
          'file'  =>  $url,
        ));
        ?>'
      >
        <?php echo end($url); ?>
      </div>
    <?php }
  }
  ?>
</div>
