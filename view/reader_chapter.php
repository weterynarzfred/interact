<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry
string		$filename
*/

$entry = get_entry($entry);
?>

<div
	class="container view view-reader_chapter"
	data-view="reader_chapter"
	data-entry="<?php echo $entry->get_id(); ?>"
	data-chapter="<?php echo $filename; ?>"
  data-last-read-page="<?php echo $entry->get_prop('last_read_page'); ?>"
>
	<div class="return return-absolute button">return</div>
	<div class="reader-chapter-pages"><?php
		$url = reader_get_folder_url($entry) . '/' . $filename;
		$pages = array_diff(scandir(HOME_DIR . $url), array('.', '..'));
		if($pages) {
      $i = 0;
  		foreach ($pages as $page) {
  	?>
		<div
			class="reader-page lazy-cake rel"
      id="reader-page-<?php echo $i; ?>"
			data-bg="<?php echo '.' . $url . '/' . rawurlencode($page); ?>"
		>
			<div class="cake abs"></div>
			<svg class="loading-icon" viewBox="-10 -10 120 120">
				<circle cx="50" cy="50" r="40" />
			</svg>
		</div>
  	<?php
        $i++;
			}
		}
		?></div>
</div>
