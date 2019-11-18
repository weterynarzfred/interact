<?php if (!defined('CONNECTION_TYPE')) die();

function create_slug($name) {
  $name = mb_strtolower($name);
  return preg_replace('/(^[^a-z]|[^a-z0-9-_])/', '_', $name);
}

function delete_dir($dirPath) {
  $files = array_diff(scandir($dirPath), array('.', '..'));
  foreach($files as $file) {
    $file = $dirPath . '/' . $file;
    if(is_dir($file)) {
      delete_dir($file);
    }
    else {
      unlink($file);
    }
  }
  rmdir($dirPath);
}

function get_chapter_number($string) {
  $chapter;
  if (preg_match(
    '/^.*?(c|ch)[\. ]*?([0-9]*?-)?([0-9]+)([\.x ]+([0-9]+))?.*?$/i',
    $string,
    $chapter
  )) {
    if (isset($chapter[5])) return floatval($chapter[3] . '.' . $chapter[5]);
    return floatval($chapter[3]);
  }
  return -1;
}

function get_chapter_span($string) {
  $chapter;
  if (preg_match(
    '/^.*?(c|ch)[\. ]*?([0-9]*?-)?([0-9]+)([\.x ]+([0-9]+))?.*?$/i',
    $string,
    $chapter
  )) {
    if (isset($chapter[5])) $chapter[3] = $chapter[3] . '.' . $chapter[5];
    if ($chapter[2] === '') $chapter[2] = $chapter[3];
    $result = array(
      'first'	=>  floatval($chapter[2]),
      'last'  =>  floatval($chapter[3]),
    );
    return $result;
  }
  return array(
    'first'	=>  -1,
    'last'  =>  -1,
  );
}
