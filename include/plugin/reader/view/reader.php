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
	<div class="reader-manga-file-list" data-id="<?php echo $entry->get_ID(); ?>">
	  <?php
	  if($files) {
			$last_read = $entry->get_prop('last_read_file');
	    foreach ($files as $file) {
	      $url = explode('/', $file);
				$filename = end($url);
				$read = $entry->get_read();
				if($filename === $last_read) {
					$filename = '<span class="reader-manga-file-last-read">' . $filename . '</span>';
				}
				$filename = preg_replace_callback(
					'/^.*?c([0-9]*?-)?([0-9]+).*?$/',
					function($m) use ($read) {
						if(intval($m[2]) <= $read) return '<span class="reader-manga-file-read">' . $m[0] . '</span>';
						return $m[0];
					},
					$filename
				);
				?>
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
	        <?php echo $filename; ?>
	      </div>
	    <?php }
	  }
	  ?>
	</div>
</div>
