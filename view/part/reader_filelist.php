<?php if (!defined('CONNECTION_TYPE')) die();
/*
Displays a list of available chapters.

used variables:
int|Entry	$entry
array	    $files = reader_get_files($entry)
*/

if (!isset($files)) {
  $entry = get_entry($entry);
  $files = reader_get_files($entry);
}
?>

<div class="view view-part-reader_filelist">
  <div class="column-double">
    <div class="text-strip">
      <div class="title">downloaded</div>
    </div>
  </div>

  <div class="reader-filelist column">
    <?php
    if ($files) {
      foreach ($files as $file) {
        $classes = array(
          'reader-file',
          'get-view',
        );
        if ($file['name'] <= $entry -> get_prop('read')) {
          $classes[] = 'read';
        }
        if ($file['name'] == $entry -> get_prop('last_read_chapter')) {
          $classes[] = 'last-read';
        }
    ?>
    <div class="column">
      <div
        class="<?php echo implode(' ', $classes); ?>"
        data-view="reader_chapter"
        data-details='<?php echo json_encode(array(
          'entry'     =>  $entry -> get_id(),
          'filename'  =>  $file['name'],
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
