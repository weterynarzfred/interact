<?php if(!defined('CONNECTION_TYPE')) die();

set_time_limit(86400);

$values = $_POST['values'];
$success = false;

function downloadLink($link, $destination) {
  $ctx = stream_context_create();
  stream_context_set_params($ctx, array("notification" => "stream_notification_callback"));
	$link = str_replace('/manga.madokami.al', '/me123:12345@manga.madokami.al', $link);
	$filename = explode('/', $link);
	$filename = urldecode(end($filename));
  $mb_download = file_put_contents($destination.'/'.$filename, fopen($link, 'r', null, $ctx));
  return $mb_download;
}

function stream_notification_callback($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max) {
	static $filesize = null;
	switch($notification_code) {
	case STREAM_NOTIFY_FILE_SIZE_IS:
		$filesize = $bytes_max;
		break;
	case STREAM_NOTIFY_PROGRESS:
		global $reader_folder_url;
	  $fp = fopen($reader_folder_url . '/progress.txt', 'w');
	  fputs($fp, $bytes_transferred . '/' . $filesize);
	  fclose($fp);
		break;
	}
}

try {
  if(
		!isset($values['id']) ||
		!isset($values['url'])
	) {
    throw new Exception('correct data not provided');
  }

  $entry = get_entry($values['id']);
	global $reader_folder_url;
	$reader_folder_url = reader_get_folder_url($entry);
	downloadLink($values['url'], $reader_folder_url);
	unlink($reader_folder_url . '/progress.txt');




  $success = true;
}
catch(Exception $e) {
  SN()->create_error('ajax failed performing action "download"; ' . $e);
}

$response = array(
  'success'  =>  $success,
  'errors'  =>  SN()->get_errors(),
	'type'	=>	'download',
);

echo json_encode($response);
