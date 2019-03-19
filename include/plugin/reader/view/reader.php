<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($data);
$url_base = get_option('manga_url');
$url = $url_base . $entry->get_prop('reader_folder');
$files = scandir($url);
$files = array_map(function($f) use ($url) {return $url . '/' . $f;}, $files);
$files = array_filter($files, function($f) {return is_file($f);});
?>

<div class="container">
  <div class="button navigation-link return-button" data-target="home">return</div>
  <div class="rmin"></div>
  <form class="ajax-form" data-form-action="update_entry">
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
			$read = $entry->get_read();

			$files = array_map(function($file) use($read, $last_read) {
				$url = explode('/', $file);
				$filename = end($url);
				if($filename === $last_read) {
					$filename = '<span class="reader-manga-file-last-read">' . $filename . '</span>';
				}

				// $filename
				$filename = array($filename, 0);
				preg_replace_callback(
					'/^.*?(c|ch)[\. ]*?([0-9]*?-)?([0-9]+).*?$/i',
					function($m) use ($read, &$filename) {
						$c = intval($m[3]);
						$name = $m[0];
						if($c <= $read) {
							$name = '<span class="reader-manga-file-read">' . $name . '</span>';
						}
						$filename = array($name, $c);
					},
					$filename
				);
				$filename[] = $url;
				return $filename;
			}, $files);

			usort($files, function($a, $b) {
				return $a[1] > $b[1];
			});

			$last_downloaded = end($files)[1];
			$downloaded = intval($entry->get_prop('reader_downloaded'));
			if($last_downloaded > $downloaded) {
				$values = array(
					'reader_downloaded'	=>	$last_downloaded,
				);
				$entry->update($values);
			}

	    foreach ($files as $file) {
				?>
	      <div
	        class="navigation-link reader-manga-file"
	        data-target="reader_manga"
	        data-value='<?php
	        echo str_replace("'", "&#39;", json_encode(array(
	          'entry' =>  $entry->get_ID(),
	          'file'  =>  $file[2],
	        )));
	        ?>'
	      >
	        <?php echo $file[0]; ?>
	      </div>
	    <?php }
	  }
	  ?>
	</div>
</div>
