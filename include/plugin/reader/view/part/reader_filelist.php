<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($entry);
$url_base = get_option('manga_url');
$url = $url_base . $entry->get_prop('reader_folder');
$files = scandir($url);
$files = array_map(function($f) use ($url) {return $url . '/' . $f;}, $files);
$files = array_filter($files, function($f) {return is_file($f);});

function prepare_manga_filenames($args) {
	$replaced_count = 0;
	$args['files'] = array_map(function($file) use($args, &$replaced_count) {
		$url = explode('/', $file);
		$filename = end($url);
		if($filename === $args['last_read']) {
			$filename = '<span class="reader-manga-file-last-read">' . $filename . '</span>';
		}
		$filename = array(
			'html'	=>	$filename,
			'number'	=>	0,
			'url'	=>	$url,
		);
		return $filename;
	}, $args['files']);

	$args['files'] = mark_read_manga_files($args, '/^.*?(c|ch)[\. ]*?([0-9]*?-)?([0-9]+).*?$/i', $replaced_count);
	if(!$replaced_count) {
		$args['files'] = mark_read_manga_files($args, '/^.*?(v|vol)[\. ]*?([0-9]*?-)?([0-9]+).*?$/i', $replaced_count);
	}

	usort($args['files'], function($a, $b) {
		return $a['number'] > $b['number'];
	});

	return $args['files'];
}

function mark_read_manga_files($args, $regex, &$replaced_count) {
	$files = array_map(function($filename) use($args, $regex, &$replaced_count) {
		$count = 0;
		preg_replace_callback(
			$regex,
			function($m) use ($args, &$filename) {
				$c = intval($m[3]);
				$name = $m[0];
				if($c <= $args['read']) {
					$name = '<span class="reader-manga-file-read">' . $name . '</span>';
				}
				$filename['html'] = $name;
				$filename['number']	= $c;
			},
			$filename['html'], 1, $count
		);
		$replaced_count += $count;
		return $filename;
	}, $args['files']);
	return $files;
}

?>
<div id="reader-manga-file-list" data-id="<?php echo $entry->get_ID(); ?>">
	<?php
	if($files) {
		$last_read = $entry->get_prop('last_read_file');
		$read = $entry->get_read();

		$files = prepare_manga_filenames(array(
			'files'	=>	$files,
			'read'	=>	$read,
			'last_read'	=>	$last_read,
		));

		$last_downloaded = end($files)['number'];
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
					'file'  =>  $file['url'],
				)));
				?>'
			>
				<?php echo $file['html']; ?>
			</div>
		<?php }
	}
	?>
</div>
