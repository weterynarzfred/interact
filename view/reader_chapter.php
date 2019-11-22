<?php if (!defined('CONNECTION_TYPE')) die();
/*
Displays pages of a single chapter to be read.

used variables:
int|Entry $entry
string    $filename
*/

$entry = get_entry($entry);
?>

<div
  class="container view view-reader_chapter"
  data-view="reader_chapter"
  data-entry="<?php echo $entry -> get_id(); ?>"
  data-chapter="<?php echo $filename; ?>"
  data-last-read-page="<?php echo $entry -> get_prop('last_read_page'); ?>"
>
  <div class="reader-chapter-buttons">
    <div class="return button">return</div>
    <div class="button reader-chapter-plus">+</div>
    <div class="button reader-chapter-minus">-</div>
  </div>
  <div class="reader-chapter-pages"><?php
    $url = reader_get_folder_url($entry) . '/' . $filename;
    $pages = array_diff(scandir(HOME_DIR . $url), array('.', '..'));
    if ($pages) {
      $i = 0;
      foreach ($pages as $page) {
    ?>
    <div
      class="cake reader-page"
      id="reader-page-<?php echo $i; ?>"
      style="
        background-image:url('<?php
          echo '.' . $url . '/' . ($page);
        ?>');
        background-size: contain;
        background-repeat: no-repeat;
        padding-bottom: 0;
        "
    ></div>
    <?php
        $i++;
      }
    }
    ?></div>
</div>
