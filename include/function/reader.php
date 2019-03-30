<?php if(!defined('CONNECTION_TYPE')) die();

function reader_get_folder_url($entry) {
	$entry = get_entry($entry);
	$url_base = get_option('manga_url');
	$url = $url_base . $entry->get_id();
	if(!is_dir($url)) mkdir($url);
	return $url;
}

function reader_get_folder($entry) {
	$url = reader_get_folder_url($entry);
	$files = array_diff(scandir($url), array('.', '..'));
	$files = array_map(function($f) use ($url) {return $url . '/' . $f;}, $files);
	// $files = array_filter($files, function($f) {return is_dir($f);});
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
			'/<tr data-record="[0-9]*">[^<]*<td>[^<]*<a href="([^"]*)"[^>]*>([^<]*)<\/a>/is',
			function($matches) use (&$results) {
				$results[] = array(
					'url'	=>	$matches[1],
					'name'	=>	$matches[2],
					'chapter'	=>	get_chapter_number($matches[2]),
				);
			},
			$table[0]
		);

		return $results;
	}
	return false;
}
