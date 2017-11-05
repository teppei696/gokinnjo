<?php
// リクエストパラメーター取得
$kairannbann = $_REQUEST['kairannbann'];
$chounaikai = $_REQUEST['chounaikai'];
error_log("kairannbann: " . $kairannbann);
error_log("chounaikai: " . $chounaikai);

// 同じ町内会に所属する人を取得
// kintoneに登録
define("SUB_DOMAIN", "oas7b.cybozu.com");
define("APP_NO", "18");
define("API_TOKEN", "Ok3io8SZAXGWa6CXjd4q1z08Y50FDuZIak7AkhD2");
// 既存データの検索
$options = array(
  'http'=>array(
    'method'=>'GET',
    "header"  => "X-Cybozu-API-Token: ". API_TOKEN
  )
);
$context = stream_context_create($options);
// サーバに接続してデータを貰う。条件式の部分だけURLエンコードを行う(イコールを使っているので）
$query = urlencode('chounaikai = "' . $chounaikai . '"');
$url = 'https://'. SUB_DOMAIN .'/k/v1/records.json?app='. APP_NO  ."&query=". $query;
error_log($url);
$contents = file_get_contents($url,FALSE,$context);
$data = json_decode($contents, true);
error_log($contents);
//レコードがあったらUPDATE

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
