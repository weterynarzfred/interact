<?php if(!defined('CONNECTION_TYPE')) die();

function reader_get_folder_url($entry) {
	$entry = get_entry($entry);
	$url_base = get_option('manga_url');
	$url = $url_base . $entry->get_id();
	if(!is_dir(HOME_DIR . $url)) mkdir(HOME_DIR . $url);
	return $url;
}

function reader_get_files($entry) {
	$url = HOME_DIR . reader_get_folder_url($entry);
	$files = array_diff(scandir($url), array('.', '..'));
	$files = array_map(function($f) use ($url) {
		return array(
			'url'	=>	$url . '/' . $f,
			'name'	=>	$f,
		);
	}, $files);
	$files = array_filter($files, function($f) {return is_dir($f['url']);});

	usort($files, function($a, $b) {
		return strnatcmp($b['name'], $a['name']);
	});

	return $files;
}

function reader_get_madokami_files($entry) {
	$entry = get_entry($entry);

	$output = array();
	$madokami_url = $entry->get_prop('madokami_url');
	if($madokami_url != '') {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $madokami_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt(
			$curl,
			CURLOPT_USERPWD,
			get_option('madokami_user') . ":" . get_option('madokami_password')
		);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$output = curl_exec($curl);
		curl_close($curl);

		$results = array();
		$table = preg_split('/<table id="index-table.*?>/is', $output, 2);
		$table = preg_split('/<\/table>/is', $table[1], 2);
		preg_replace_callback(
			'/<tr data-record="[0-9]*">[^<]*<td>[^<]*<a href="([^"]*)"[^>]*>(([^<]*)\.[^<.]+)<\/a>/is',
			function($matches) use (&$results) {
				$results[] = array(
					'url'	=>	$matches[1],
					'filename'	=>	$matches[2],
					'name'	=>	$matches[3],
					'chapter'	=>	get_chapter_number($matches[2]),
				);
			},
			$table[0]
		);

		usort($results, function($a, $b) {
			return $a['chapter'] < $b['chapter'];
		});

		$entry->update(array(
			'madokami_filelist'	=>	$results,
			'madokami_last_check'	=>	time(),
		));

		return $results;
	}
	return false;
}

function get_all_files($parent_path, &$files) {
	$paths = array_diff(scandir(implode('/', $parent_path)), array('.', '..'));
	if($paths) {
		foreach($paths as $path) {
			$current_path = $parent_path;
			$current_path[] = $path;
			if(is_file(implode('/', $current_path))) {
				$extension = pathinfo(implode('/', $current_path), PATHINFO_EXTENSION);
				if(!in_array($extension, array('bmp', 'png', 'jpg', 'gif', 'tiff'))) continue;
				$files[] = $current_path;
			}
			elseif(is_dir(implode('/', $current_path))) {
				get_all_files($current_path, $files);
			}
		}
	}
}

function flatten_reader_folder($url, $name) {
	$files = array();
	$path = array($url, $name);
	get_all_files($path, $files);

	$files = array_map(function($parts) {
		$info = array_map(function($part) {
			$arr = get_chapter_span($part);
			$arr['name'] = $part;
			return $arr;
		}, $parts);

		$info = array_filter($info, function($part) {
			return $part['last'] !== -1;
		});

		$result = array(
			'path'	=>	$parts,
			'first'	=>	-1,
			'last'	=>	-1,
		);
		if($info) {
			$span = PHP_INT_MAX;
			foreach ($info as $part) {
				if($part['last'] - $part['first'] < $span) {
					$span = $part['last'] - $part['first'];
					$result['first'] = $part['first'];
					$result['last'] = $part['last'];
				}
				if($span === 0) {
					break;
				}
			}
		}

		return $result;
	}, $files);

	$all_files_determined = true;
	foreach ($files as $file) {
		if($file['last'] === -1) {
			$all_files_determined = false;
			break;
		}
	}

	if($all_files_determined) {
		for($i = 0; $i < count($files); $i++) {
			$chapter_number = $files[$i]['first'];
			if($files[$i]['last'] > $files[$i]['first']) $chapter_number . '-' . $files[$i]['last'];
			$files[$i]['new_path'] = $url . '/' . $chapter_number;
			if(!file_exists($files[$i]['new_path'])) {
				mkdir($files[$i]['new_path']);
			}
			rename(implode('/', $files[$i]['path']), $files[$i]['new_path'] . '/' . end($files[$i]['path']));
		}

		delete_dir($url . '/' . $name);
	}
}
