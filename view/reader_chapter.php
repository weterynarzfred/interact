<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry
string		$filename
*/

$entry = get_entry($entry);
?>

<div class="container view view-reader_chapter" data-view="reader_chapter">
	<div class="return return-absolute button">return</div>
	<?php
	$url = reader_get_folder_url($entry) . '/' . $filename;
	$pages = array_diff(scandir(HOME_DIR . $url), array('.', '..'));
	if($pages) {
	foreach ($pages as $page) {
	?>
	<div
		class="reader-page lazy-cake rel"
		data-bg="<?php echo '.' . $url . '/' . rawurlencode($page); ?>"
	>
		<div class="cake abs"></div>
		<svg class="loading-icon" viewBox="-10 -10 120 120">
			<circle cx="50" cy="50" r="40" />
		</svg>
	</div>
	<?php
		}
	}
	?>
</div>
