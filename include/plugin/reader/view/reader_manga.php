<?php if(!defined('CONNECTION_TYPE')) die();

function delTree($dir) {
 $files = array_diff(scandir($dir), array('.','..'));
  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
  }
  return rmdir($dir);
}

$zip = new ZipArchive;
$entry = get_entry($data['entry']);
$current_manga_folder = get_option('manga_url') . $entry->get_prop('reader_folder');
$current_manga_folder_array = explode('/', $current_manga_folder);
$filename = end($data['file']);
$temp_folder = HOME_DIR . '/include/plugin/reader/temp/';
$folder_name = explode('.', $filename)[0];

if ($zip->open($current_manga_folder . '/' . $filename) === TRUE) {
  if(!is_dir($temp_folder . '/' . $folder_name)) {
    if(is_dir($temp_folder)) {
      delTree($temp_folder);
    }
    $zip->extractTo($temp_folder . '/' . $folder_name);
    $zip->close();
  }
} ?>

<div class="button navigation-link reader-return" data-target="home">return</div>

<?php
$pages = array_diff(scandir($temp_folder . '/' . $folder_name), array('.','..'));
foreach($pages as $page) { ?>
<div
  style="background-image:url('/teste/interact/include/plugin/reader/temp/<?php echo $folder_name . '/' . $page; ?>');"
  class="reader-page"
></div>
<?php } ?>
