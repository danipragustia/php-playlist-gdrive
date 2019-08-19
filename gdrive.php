<?php

  class GoogleDrive {

    private $GoogleAPIURL = 'https://www.googleapis.com/';
    private $GoogleAuthURL = 'https://accounts.google.com/o/oauth2/';

    protected $client_id, $client_secret, $scopes, $redirect, $state;

    private function ErrorOutputFormat($code,$description) {
      return array(
        'code' => $code,
        'description' => $description
      );
    }

    public function __construct($client_id, $client_secret, $scopes, $redirect = NULL, $state = NULL) {

      $array_temp = array();

      $this->client_id = $client_id;
      $this->client_secret = $client_secret;

      if ($redirect == NULL) {
        $this->redirect = 'http' . ($_SERVER['SERVER_PORT'] == 80 ? '' : 's') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
      } else {
        $this->redirect = $redirect;
      }

      foreach($scopes as $scope) {
        array_push($array_temp, $this->GoogleAPIURL . 'auth/' . $scope);
      }

      $this->scopes = $array_temp;
      $this->state = $state;
    }

    public function GetAccessToken($array_credentials) {

      $expire_date = new DateTime();
      $expire_date->setTimestamp($array_credentials['created']);
      $expire_date->add(new DateInterval('PT' . $array_credentials['expires_in'] . 'S'));
      $current_time = new DateTime();

      if ($current_time->getTimestamp() >= $expire_date->getTimestamp()) {
        return ErrorOutputFormat(1,'Access Token Expired!');
      } else {
        return $array_credentials['access_token'];
      }

    }

    public function RequestAccessToken($access_code) {

      $url = $this->GoogleAuthURL . 'token';
      $post_fields = 'code=' . $access_code . '&client_id=' . urlencode($this->client_id) . '&client_secret=' . urlencode($this->client_secret) . '&redirect_uri=' . urlencode($this->redirect) . '&grant_type=authorization_code';

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      $result = curl_exec($ch);
      curl_close($ch);

      return json_decode($result, true);

    }

    public function RequestAuthCode() {

      $url = sprintf($this->GoogleAuthURL . 'auth?scope=%s&redirect_uri=%s&response_type=code&client_id=%s&approval_prompt=force&access_type=offline&state=%s',urlencode(implode(' ', $this->scopes)), urlencode($this->redirect), urlencode($this->client_id), urlencode($this->state));

      return $url;

    }

    public function CopyFileByID($access_token, $file_id, $array_params = NULL) {

      $ch = curl_init('https://www.googleapis.com/drive/v3/files/'.$file_id.'/copy?alt=json');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-length: 0',
        'Authorization: Bearer ' . $access_token
      ));
      curl_setopt($ch, CURLOPT_POST, 1);

      $res = curl_exec($ch);
      curl_close($ch);
      echo $res;
      return json_decode($res, true);

    }

    public function GetFileList($access_token, $array_params = NULL) {

      if (is_array($array_params)) {
        $ch = curl_init('https://www.googleapis.com/drive/v3/files?' . http_build_query($array_params));
      } else {
        $ch = curl_init('https://www.googleapis.com/drive/v3/files');
      }

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $access_token
      ));
      $res = curl_exec($ch);
      curl_close($ch);
      return json_decode($res, true);

    }

  }
?>
