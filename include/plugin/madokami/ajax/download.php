<?php if (!defined('CONNECTION_TYPE')) die();

set_time_limit(86400);

$values = $_POST['values'];
$success = false;

function download_file($link, $destination) {
  $ctx = stream_context_create();
  stream_context_set_params(
    $ctx,
    array("notification" => "stream_notification_callback")
  );
  $link = str_replace(
    '/manga.madokami.al',
    implode(array(
      '/',
      get_option('madokami_user'),
      ':',
      get_option('madokami_password'),
      '@manga.madokami.al'
    )),
    $link
  );
  $filename = explode('/', $link);
  $filename = urldecode(end($filename));
  $mb_download = file_put_contents(
    $destination . '/' . $filename,
    fopen($link, 'r', null, $ctx)
  );
  return $mb_download;
}

function stream_notification_callback(
  $notification_code,
  $severity,
  $message,
  $message_code,
  $bytes_transferred,
  $bytes_max
) {
  static $filesize = null;
  switch ($notification_code) {
    case STREAM_NOTIFY_FILE_SIZE_IS:
    $filesize = $bytes_max;
    break;
    case STREAM_NOTIFY_PROGRESS:
    global $reader_progress_log_url;
    $fp = fopen($reader_progress_log_url, 'w');
    fputs($fp, $bytes_transferred . '/' . $filesize);
    fclose($fp);
    break;
  }
}

try {
  if (
    !isset($values['id']) ||
    !isset($values['url'])
  ) {
    throw new Exception('correct data not provided');
  }

  $entry = get_entry($values['id']);
  $reader_folder_url = HOME_DIR . reader_get_folder_url($entry);
  $filename = explode('/', $values['url']);
  $filename = urldecode(end($filename));
  global $reader_progress_log_url;
  $reader_progress_log_url =
    $reader_folder_url . '/progress - ' . $filename . '.txt';

  download_file($values['url'], $reader_folder_url);

  $fp = fopen($reader_progress_log_url, 'w');
  fputs($fp, '1/1');
  fclose($fp);

  $name_array = pathinfo($filename);
  $name = $name_array['filename'];
  $filename = $name_array['basename'];
  $cmd = '"' . get_option('7z_path') . '" x -o"' . $reader_folder_url . '/' .
    $name . '" "' . $reader_folder_url . '/' . $filename . '"';
  $r = shell_exec($cmd);

  unlink($reader_progress_log_url);
  unlink($reader_folder_url . '/' . $filename);

  flatten_reader_folder($reader_folder_url, $name);

  $success = true;
}
catch(Exception $e) {
  SN() -> create_error('ajax failed performing action "download"; ' . $e);
}

$response = array(
  'success' =>  $success,
  'errors'  =>  SN() -> get_errors(),
  'type'    =>  'download',
);

echo json_encode($response);
