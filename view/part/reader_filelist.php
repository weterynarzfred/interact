<?php if(!defined('CONNECTION_TYPE')) die();
/*
used variables:
int|Entry	$entry
array			$files
*/

if(!isset($files)) {
	$entry = get_entry($entry);
	$files = reader_get_folder($entry);
}
?>

<div class="view view-part-reader_filelist">
	<div class="column-double">
		<div class="title">downloaded</div>
	</div>

	<div class="reader-filelist column">
	<!-- <div class="reader-filelist flex flex-wrap flex-justify-space-between column"> -->
		<?php
		if($files) {
			foreach ($files as $file) {
				$classes = array();
				// if($file['chapter'] <= $entry->get_prop('read')) $classes[] = 'read';
				// if($file['chapter'] <= $entry->get_prop('downloaded')) $classes[] = 'downloaded';
		?>
		<div class="column">
			<div
				class="reader-file <?php echo implode(' ', $classes); ?> get-view"
				data-view="reader_chapter"
				data-details='<?php echo json_encode(array(
					'entry'	=>	$entry->get_id(),
					'filename'	=>	$file['name'],
				)); ?>'
				data-target=".next-container"
			>
				<div class="reader-filename">
					<?php echo $file['name']; ?>
				</div>
				<?php
				$url = reader_get_folder_url($entry) . '/' . $file['name'];
				$pages = scandir(HOME_DIR . $url);
				$page = isset($pages[2]) ? '.' . $url . '/' . rawurlencode($pages[2]) : '';
				?>
				<div
					class="reader-cover<?php echo $page ? ' lazy-cake' : ''; ?>"
					data-bg="<?php echo $page; ?>"
				>
					<div class="cake cake-3-4"></div>
					<svg class="loading-icon" viewBox="-10 -10 120 120">
						<circle cx="50" cy="50" r="40" />
					</svg>
				</div>
			</div>
		</div>
		<?php
			}
		}
		else {
		?>
		<p>no files found</p>
		<?php
		}
		?>
	</div>

	<div class="rmin"></div>
</div>
