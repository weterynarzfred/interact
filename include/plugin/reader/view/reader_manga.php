<?php if(!defined('CONNECTION_TYPE')) die();

function delTree($dir) {
 $files = array_diff(scandir($dir), array('.','..'));
  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
  }
  return rmdir($dir);
}

$entry = get_entry($data['entry']);
$current_manga_folder = get_option('manga_url') . $entry->get_prop('reader_folder');
$current_manga_folder_array = explode('/', $current_manga_folder);
$filename = end($data['file']);
$temp_folder = HOME_DIR . '/include/plugin/reader/temp/';
$folder_name = explode('.', $filename);
$extension = array_pop($folder_name);
$folder_name = implode('.', $folder_name);

if(!is_dir($temp_folder . '/' . $folder_name)) {
	if(is_dir($temp_folder)) {
		delTree($temp_folder);
	}
	if($extension === 'zip' || $extension === 'cbz') {
		$zip = new ZipArchive;
		if($zip->open($current_manga_folder . '/' . $filename) === TRUE) {
	    $zip->extractTo($temp_folder . '/' . $folder_name);
	    $zip->close();
	  }
	}
	else if($extension === 'rar' || $extension === 'cbr') {
		$rar = rar_open($current_manga_folder . '/' . $filename);
		if($rar) {
			$rar_entries = rar_list($rar);
			foreach ($rar_entries as $rar_entry) {
		    $rar_entry->extract($temp_folder . '/' . $folder_name);
			}
	    rar_close($rar);
	  }
	}
}


?>

<div
	class="button navigation-link reader-return return-button"
	data-target="reader"
	data-value="<?php echo $entry->get_ID(); ?>"
>return</div>

<div
	class="reader-manga-pages-container"
	data-id="<?php echo $entry->get_ID(); ?>"
	data-read="<?php echo $entry->get_read(); ?>"
	data-filename="<?php echo $filename; ?>"
	data-last-read-page="<?php echo $entry->get_prop('last_read_page'); ?>"
>
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
				$file_exploded = explode('.', $file);
				$file_extension = end($file_exploded);
				if(in_array($file_extension, array('jpg', 'png', 'gif', 'bmp', 'tiff', 'webp'))) {
					$new_filelist[] = $file;
				}
			}
			$i++;
		}
		return $new_filelist;
	}

	$pages = load_folder_tree($temp_folder, array($folder_name));
	$i = 0;
	foreach($pages as $page) {
		$page = str_replace("'", "%27", ($page)); ?>
	<div
		style="background-image:url('/teste/interact/include/plugin/reader/temp/<?php echo $page; ?>');"
		class="reader-page"
		data-id="<?php echo $i++; ?>"
	></div>
	<?php } ?>
</div>
