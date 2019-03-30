<?php if(!defined('CONNECTION_TYPE')) die();

function create_slug($name) {
	$name = mb_strtolower($name);
	return preg_replace('/(^[^a-z]|[^a-z0-9-_])/', '_', $name);
}

function get_chapter_number($string) {
	$chapter;
	if(preg_match(
		'/^.*?(c|ch)[\. ]*?([0-9]*?-)?([0-9]+).*?$/i',
		$string,
		$chapter
	)) {
		return intval($chapter[3]);
	}
	return -1;
}
