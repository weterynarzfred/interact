<?php if(!defined('CONNECTION_TYPE')) die();

$entry = get_entry($entry);

echo $entry->get_name();
