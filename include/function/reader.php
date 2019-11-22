<?php if(!defined('CONNECTION_TYPE')) die();

function reader_get_folder_url($entry) {
  $entry = get_entry($entry);
  $url_base = get_option('manga_url');
  $url = $url_base . $entry -> get_id();
  if(!is_dir(HOME_DIR . $url)) mkdir(HOME_DIR . $url);
  return $url;
}

/**
 * returns an array of downloaded chapters for an entry
 */
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

// get all images from the directory tree
function get_all_files($parent_path, &$files) {
  $paths = array_diff(scandir(implode('/', $parent_path)), array('.', '..'));
  if ($paths) {
    foreach ($paths as $path) {
      $current_path = $parent_path;
      $current_path[] = $path;
      if (is_file(implode('/', $current_path))) {
        $extension = pathinfo(implode('/', $current_path), PATHINFO_EXTENSION);
        if (!in_array($extension, array('bmp', 'png', 'jpg', 'jpeg', 'gif', 'tiff')))
          continue;
        $files[] = $current_path;
      }
      elseif (is_dir(implode('/', $current_path))) {
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
      'path'  =>  $parts,
      'first'	=>  -1,
      'last'  =>  -1,
    );
    if ($info) {
      $span = PHP_INT_MAX;
      foreach ($info as $part) {
        if ($part['last'] - $part['first'] < $span) {
          $span = $part['last'] - $part['first'];
          $result['first'] = $part['first'];
          $result['last'] = $part['last'];
        }
        if ($span === 0) {
          break;
        }
      }
    }

    return $result;
  }, $files);

  $all_files_determined = true;
  foreach ($files as $file) {
    if ($file['last'] === -1) {
      $all_files_determined = false;
      break;
    }
  }

  if ($all_files_determined) {
    $created_dirs = array();
    for ($i = 0; $i < count($files); $i++) {
      $chapter_number = $files[$i]['first'];
      if ($files[$i]['last'] > $files[$i]['first'])
        $chapter_number . '-' . $files[$i]['last'];
      $files[$i]['new_path'] = $url . '/' . $chapter_number;
      if (!in_array($files[$i]['new_path'], $created_dirs)) {
        if (is_dir($files[$i]['new_path'])) {
          delete_dir($files[$i]['new_path']);
        }
        mkdir($files[$i]['new_path']);
        $created_dirs[] = $files[$i]['new_path'];
      }
      rename(
        implode('/', $files[$i]['path']),
        $files[$i]['new_path'] . '/' . end($files[$i]['path'])
      );
    }

    delete_dir($url . '/' . $name);
  }
}
