<?php
require("smf_2_api.php");

if ($_REQUEST['login'] == 1) {
  $token = $_REQUEST['token'];
  $signature = $_REQUEST['signature'];
  if ($signature != base64url_encode(hash_hmac("sha512", $token, base64_decode(file_get_contents("forum.key"))))) {
    echo "Bad token\n"; exit;
  }

  $data = json_decode(base64url_decode($token), true);
  if (!$data['nlt'] || $data['nlt'] < time()) {
    echo "Expired token\n"; exit;
  }

  smfapi_login($data['cid']);
  
  header("Location: " . $data['return']);
} elseif ($_REQUEST['logout'] == 1) {
  smfapi_logout();
  header("Location: " . $_REQUEST['return']);
} elseif ($_REQUEST['register'] == 1) {
  $data = $_REQUEST['data'];
  $signature = $_REQUEST['signature'];

  if ($signature != base64url_encode(hash_hmac("sha512", $data, base64_decode(file_get_contents("forum.key"))))) {
    echo "Bad data\n"; exit;
  }

  $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
  $pass = [];
  $alphaLength = strlen($alphabet) - 1;
  for ($i = 0; $i < 32; $i++) {
    $n = rand(0, $alphaLength);
    $pass[] = $alphabet[$n];
  }
  $pass = implode($pass);

  $data = json_decode(base64url_decode($data), true);
  $data['password'] = $pass;
  $data['password_check'] = $pass;

  $r = smfapi_registerMember($data);
  if (is_array($r) || $r == false) {
    echo "Failed to create new user: " . base64_encode(serialize($r)) . ", " . base64_encode(serialize($data));
    exit;
  }

  echo "OK"; exit;
} else {
  echo "Unknown request.\n";
}

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}
function base64url_encode($data, $use_padding = false) {
    $encoded = strtr(base64_encode($data), '+/', '-_');
    return true === $use_padding ? $encoded : rtrim($encoded, '=');
}
