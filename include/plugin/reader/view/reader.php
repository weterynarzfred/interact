<?php if(!defined('CONNECTION_TYPE')) die(); ?>

<div class="container">
  <div class="button navigation-link" data-target="home">return</div>
  <div class="rmin"></div>
  <p>here you will be able to read your manga</p>
  <?php
  $entry = get_entry($data);
  $url_base = get_option('manga_url');
  $url = $url_base . $entry->get_prop('reader_folder');
  $files = scandir($url);
  $files = array_map(function($f) use ($url) {return $url . '/' . $f;}, $files);
  $files = array_filter($files, function($f) {return is_file($f);});
  if($files) {
    foreach ($files as $file) {
      $url = explode('/', $file); ?>
      <div
        class="navigation-link reader-manga-file"
        data-target="reader_manga"
        data-value='<?php
        echo json_encode(array(
          'entry' =>  $entry->get_ID(),
          'file'  =>  $url,
        ));
        ?>'
      >
        <?php echo end($url); ?>
      </div>
    <?php }
  }
  ?>
</div>
