<?php
$accessToken = 'eA/51fwfAvW5dzxv8/TRF1To1ThZEBHQnyEXvMHapZTgSOwG9F2JCFz9mzeMb5H3QRN4YC/VdZX9bx1t8IMPpX/hR5+gl4pKR55CmfWX8+BpT+1WvwN3ny3Dz8oW0cYanOw3F/PJzT1iwaenUSGmcAdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v2/bot/message/push';

// データの受信(するものないので不要?)
$raw = file_get_contents('php://input');
$receive = json_decode($raw, true);
// イベントデータのパース(不要？)
$event = $receive['events'][0];

// ヘッダーの作成
$headers = array('Content-Type: application/json',
                 'Authorization: Bearer ' . $access_token);

// 送信するメッセージ作成
$message = array('type' => 'text',
                 'text' => "はろー");

$body = json_encode(array('to' => "6943081801809",
                          'messages'   => array($message)));  // 複数送る場合は、array($mesg1,$mesg2) とする。


// 送り出し用
$options = array(CURLOPT_URL            => $url,
                 CURLOPT_CUSTOMREQUEST  => 'POST',
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_HTTPHEADER     => $headers,
                 CURLOPT_POSTFIELDS     => $body);
$curl = curl_init();
curl_setopt_array($curl, $options);
curl_exec($curl);
curl_close($curl);
