<?php
error_reporting(E_ALL);
/*ini_set("allow_url_fopen", 1);
$var = json_decode(file_get_contents("http://api.fixer.io/latest?symbols=MYR,INR&base=USD"), true);
print_r($var);*/
$url = "http://api.fixer.io/latest?symbols=MYR,INR&base=USD";
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
$contents = curl_exec($ch);
if (curl_errno($ch)) {
  echo curl_error($ch);
  echo "\n<br />";
  $contents = '';
} else {
  curl_close($ch);
}

if (!is_string($contents) || !strlen($contents)) {
echo "Failed to get contents.";
$contents = '';
}

$var = json_decode($contents,true);
print_r($var);
?>