<?php

require("./smf_2_api.php");

if ($_REQUEST['login'] == 1) {
  $token = $_REQUEST['token'];
  $signature = $_REQUEST['signature'];
  if ($signature != base64url_encode(hash_hmac("sha512", $signature, base64_decode(file_get_contents("/home/vatusa/forum.key"))))) {
    echo "Bad token\n"; exit;
  }

  $data = json_decode(base64url_decode($token), true);
  if (!$data['nlt'] || $data['nlt'] > time()) {
    echo "Expired token\n"; exit;
  }

  smfapi_login($data['cid']);

  header("Location: " . $data['return']);
}

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}
function base64url_encode($data, $use_padding = false) {
    $encoded = strtr(base64_encode($data), '+/', '-_');
    return true === $use_padding ? $encoded : rtrim($encoded, '=');
}
