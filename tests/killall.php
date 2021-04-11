<?php

require_once(__DIR__.'/../vendor/autoload.php');

shell_exec('sudo kill -9 `ps aux | grep run.php`');

$url = 'http://localhost:8000/api/streamers/killAll';
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
//So that curl_exec returns the contents of the cURL; rather than echoing it
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute post
$result = curl_exec($ch);
dump(json_decode($result));
