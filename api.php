<?php

require("./smf_2_api.php");

if ($_REQUEST['login']) {
  $token = $_REQUEST['token'];
  $signature = $_REQUEST['signature'];
  if ($signature != hash_hmac("sha512", $signature, base64_decode(file_get_contents("/home/vatusa/forum.key"))) {
    echo "Bad token\n"; exit;
  }

  $data = json_decode($token, true);
  if (!$data['nlt'] || $data['nlt'] > time()) {
    echo "Expired token\n"; exit;
  }

  smfapi_login($data['cid']);
}
