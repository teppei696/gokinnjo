<?php
// リクエストパラメーター取得
$kairannbann = $_REQUEST['kairannbann'];
$chounaikai = $_REQUEST['chounaikai'];
error_log("kairannbann: " . $kairannbann);
error_log("chounaikai: " . $chounaikai);

$accessToken = 'eA/51fwfAvW5dzxv8/TRF1To1ThZEBHQnyEXvMHapZTgSOwG9F2JCFz9mzeMb5H3QRN4YC/VdZX9bx1t8IMPpX/hR5+gl4pKR55CmfWX8+BpT+1WvwN3ny3Dz8oW0cYanOw3F/PJzT1iwaenUSGmcAdB04t89/1O/w1cDnyilFU=';
$url = 'https://api.line.me/v2/bot/message/push';
// receive json data from line webhook
$raw = file_get_contents('php://input');
$receive = json_decode($raw, true);
// parse received events
$event = $receive['events'][0];
// build request headers
$headers = array('Content-Type: application/json',
                 'Authorization: Bearer ' . $accessToken);
// build request body
$message = array('type' => 'text',
                 'text' => $kairannbann);
$body = json_encode(array(
  'to'       => "U1de78326330dc1ad99d3208ead146f73",
  'messages' => array($message)
));
$options = array(CURLOPT_URL            => $url,
                 CURLOPT_CUSTOMREQUEST  => 'POST',
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_HTTPHEADER     => $headers,
                 CURLOPT_POSTFIELDS     => $body);
$curl = curl_init();
curl_setopt_array($curl, $options);
$result = curl_exec($curl);
error_log('result: ' . $result);
curl_close($curl);
?>
