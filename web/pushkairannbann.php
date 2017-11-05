<?php
$accessToken = 'eA/51fwfAvW5dzxv8/TRF1To1ThZEBHQnyEXvMHapZTgSOwG9F2JCFz9mzeMb5H3QRN4YC/VdZX9bx1t8IMPpX/hR5+gl4pKR55CmfWX8+BpT+1WvwN3ny3Dz8oW0cYanOw3F/PJzT1iwaenUSGmcAdB04t89/1O/w1cDnyilFU=';
error_log("===== 1 =====");
$url = 'https://api.line.me/v2/bot/message/push';
// receive json data from line webhook
error_log("===== 2 =====");
$raw = file_get_contents('php://input');
error_log("===== 3 =====");
$receive = json_decode($raw, true);
error_log("===== 4 =====");
// parse received events
$event = $receive['events'][0];
error_log("===== 5 =====");
// build request headers
$headers = array('Content-Type: application/json',
                 'Authorization: Bearer ' . $access_token);
error_log("===== 6 =====");
// build request body
$message = array('type' => 'text',
                 'text' => 'Hello, world1');
error_log("===== 7 =====");
$body = json_encode(array('to' => "U1de78326330dc1ad99d3208ead146f73",
                          'messages'   => $message));
error_log("===== 8 =====");
error_log("body: " + $body);
// post json with curl
$options = array(CURLOPT_URL            => $url,
                 CURLOPT_CUSTOMREQUEST  => 'POST',
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_HTTPHEADER     => $headers,
                 CURLOPT_POSTFIELDS     => $body);
error_log("===== 9 =====");
$curl = curl_init();
error_log("===== 10 =====");
curl_setopt_array($curl, $options);
error_log("===== 11 =====");
curl_exec($curl);
error_log("===== 12 =====");
curl_close($curl);
error_log("===== 13 =====");
?>
