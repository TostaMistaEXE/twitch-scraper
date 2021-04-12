<?php

require_once(__DIR__.'/vendor/autoload.php');

$url = 'http://localhost:8000/api/streamers/changeOnline';
$fields = ['streamer' => $argv[1], 'is_online' => $argv[2]];
$fields_string = http_build_query($fields);
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//So that curl_exec returns the contents of the cURL; rather than echoing it
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute post
$result = curl_exec($ch);
dump($result);
