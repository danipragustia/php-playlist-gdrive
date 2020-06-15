<?php
  require 'gdrive.php';
  set_time_limit(0);
  error_reporting(0);
  $GAPIS = 'https://www.googleapis.com/';
  $GAPIS_AUTH = $GAPIS . 'auth/';
  $WEB_URL = 'http' . ($_SERVER['SERVER_PORT'] == 80 ? '' : 's') . '://' . $_SERVER['SERVER_NAME'];

  // CONFIG HERE
  $client_id = '[CLIENT_ID]';
  $client_secret = '[CLIENT_SECRET]';

  function get_folder_files($token,$folderID) {
    $gd = new GoogleDrive($client_id,$client_secret,array('drive',  'drive.file', 'userinfo.email', 'userinfo.profile'),$WEB_URL . '/playlist.php');
    return $gd->GetFileList($token,array(
      'q' => '"'.$folderID.'" in parents',
      'fields' => 'files(id, name, mimeType)'
    ))['files'];
  }

  if (isset($_GET['code'],$_GET['state'])) {
    $gd = new GoogleDrive($client_id,$client_secret,array('drive',  'drive.file', 'userinfo.email', 'userinfo.profile'),$WEB_URL .'/playlist.php');
    $at = $gd->RequestAccessToken($_GET['code'])['access_token'];

    $xspf = '<?xml version="1.0" encoding="UTF-8"?><playlist version="1" xmlns="http://xspf.org/ns/0/"><trackList>';
    $temp = array();
    foreach(get_folder_files($at,$_GET['state']) as $file) {
      if ($file['mimeType'] == 'application/vnd.google-apps.folder') {
        array_push($temp, get_folder_files($at,$file['id']));
      } else {
        array_push($temp, $file['id']);
      }
    }

    foreach($temp as $source) {
      foreach($source as $x) {
        if (strpos($x['mimeType'], 'audio/') !== false) {
          $res_link = $WEB_URL . '/stream.php?id='.$x['id'];
          $xspf .= '<track><title>'.$x['name'].'</title><location>'.$res_link.'</location></track>';
        }
      }
    }



    $xspf .= '</trackList></playlist>';

    header("Content-Type: application/xspf+xml");
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"playlist.xspf\"");
    echo $xspf;

  } else {

    if (isset($_GET['id'])) {
      $gd = new GoogleDrive($client_id,$client_secret,array('drive',  'drive.file', 'userinfo.email', 'userinfo.profile'),$WEB_URL . '/playlist.php',$_GET['id']);
      header('Location:' . $gd->RequestAuthCode());
    } else {
      http_response_code(201);
      die('Invalid Request!');
    }

  }

?>
