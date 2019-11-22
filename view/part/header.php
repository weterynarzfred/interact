<?php if(!defined('CONNECTION_TYPE')) die(); ?>
<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="shortcut icon" type="image/png" href="./favicon.png"/>
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
    <link rel="stylesheet" type="text/css" href="./css/base.css" />
    <link rel="stylesheet" type="text/css" href="./css/edit-entry.css" />
    <link rel="stylesheet" type="text/css" href="./css/entry-list.css" />
    <link rel="stylesheet" type="text/css" href="./css/lazy-cakes.css" />
    <link rel="stylesheet" type="text/css" href="./css/loading.css" />
    <link rel="stylesheet" type="text/css" href="./css/madokami.css" />
    <link rel="stylesheet" type="text/css" href="./css/reader-chapter.css" />
    <link rel="stylesheet" type="text/css" href="./css/reader-filelist.css" />
  </head>
  <body data-ajax-url="<?php echo $_SERVER["REQUEST_URI"]; ?>ajax/index.php">
    <div id="content">
      <div id="content-bar">
        <div id="content-bar-offset"></div>
