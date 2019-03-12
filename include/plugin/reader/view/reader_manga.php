<pre><?php

function delTree($dir) {
 $files = array_diff(scandir($dir), array('.','..'));
  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
  }
  return rmdir($dir);
}

$zip = new ZipArchive;
$entry = get_entry($data['entry']);
$url = get_option('manga_url') . $entry->get_prop('reader_folder');
$url_array = explode('/', $url);
$filename = end($data['file']);
$foldername = explode('.', $filename)[0];

// todo: move temp folder to server so it can be accessed by a browser
if ($zip->open($url . '/' . $filename) === TRUE) {
  if(!is_dir($url . '/temp/' . $foldername)) {
    if(is_dir($url . '/temp/')) {
      delTree($url . '/temp/');
    }
    $zip->extractTo($url . '/temp/' . $foldername);
    $zip->close();
  }
}

$pages = array_diff(scandir($url . '/temp/' . $foldername), array('.','..'));
print_r($pages);
?></pre>
