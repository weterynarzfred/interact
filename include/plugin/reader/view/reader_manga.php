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
function load_folder_tree($url_base, $files) {
	$new_filelist = array();
	$i = 0;
	foreach($files as $file) {
		if(is_dir($url_base . $file)) {
			$sub_files = array_diff(scandir($url_base . $file), array('.','..'));
			$sub_files = array_map(function($sub_file) use($file) {
				return $file . '/' . $sub_file;
			}, $sub_files);
			$sub_files = load_folder_tree($url_base, $sub_files);
			$new_filelist = array_merge($new_filelist, $sub_files);
		}
		else {
			$new_filelist[] = $file;
		}
		$i++;
	}
	return $new_filelist;
}

$pages = load_folder_tree($temp_folder, array($folder_name));
foreach($pages as $page) { ?>
<div
	style="background-image:url('/teste/interact/include/plugin/reader/temp/<?php echo $page; ?>');"
	class="reader-page"
></div>
<?php } ?>
