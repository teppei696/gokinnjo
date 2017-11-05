<?php

$accessToken = 'eA/51fwfAvW5dzxv8/TRF1To1ThZEBHQnyEXvMHapZTgSOwG9F2JCFz9mzeMb5H3QRN4YC/VdZX9bx1t8IMPpX/hR5+gl4pKR55CmfWX8+BpT+1WvwN3ny3Dz8oW0cYanOw3F/PJzT1iwaenUSGmcAdB04t89/1O/w1cDnyilFU=';

$jsonString = file_get_contents('php://input');
error_log($jsonString);
$jsonObj = json_decode($jsonString);

$message = $jsonObj->{"events"}[0]->{"message"};
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};
$userid = $jsonObj->{"events"}[0]->{"source"}->{"userId"};
$chounaikai = $message->{"text"};
error_log("userId: " . $userid);
error_log("chounaikai: " . $chounaikai);

// 送られてきたメッセージの中身からレスポンスのタイプを選択
if (strpos($message->{"text"},'町内会') !== false) {
	// 確認ダイアログタイプ
	$messageData = array(
		'type' => 'text',
		'text' => '町内会の登録ありがとうございます！');
	// kintoneに登録
	define("SUB_DOMAIN", "oas7b.cybozu.com");
	define("APP_NO", "18");
	define("API_TOKEN", "Ok3io8SZAXGWa6CXjd4q1z08Y50FDuZIak7AkhD2");
	// 既存データの検索
	$options = array(
		'http'=>array(
			'method'=>'GET',
			'header'=> "X-Cybozu-API-Token:". API_TOKEN ."\r\n"
		)
	);
	$context = stream_context_create( $options );
	// サーバに接続してデータを貰う。条件式の部分だけURLエンコードを行う(イコールを使っているので）
	$query = urlencode("userid=" . $userid);
	$url = 'https://'. SUB_DOMAIN .'/k/v1/records.json?app='. APP_NO  ."&query=". $query;
	$contents = file_get_contents($url, FALSE, $context );
	//JSON形式からArrayに変換
	$data = json_decode($contents, true);
	error_log($contents);
	//レコードがあったらUPDATE
	if(!empty($data['records'])){
		error_log("update");
		$options = array(
			'http'=>array(
				'method'=> 'PUT',
				"header"  => "X-Cybozu-API-Token: ". API_TOKEN ."\r\n" . 'Content-Type: application/json',
				'content' => json_encode(
					array(
						'app' => APP_NO,
						'id' => $data['records'][0]['$id']['value'],
						'record' => array(
							"chounaikai" => array("value" =>$chounaikai)
						)
					)
				)
			)
		);
		$context = stream_context_create( $options );
		$url = "https://" . SUB_DOMAIN . "/k/v1/record.json";
		$contents = file_get_contents($url, FALSE, $context );
		error_log($contents);
	}else{
		error_log("insert");
		//HTTPヘッダ(新規レコードはPOST)
		$options = array(
			'http'=>array(
				'method'=> 'POST',
				"header"  => "X-Cybozu-API-Token: ". API_TOKEN ."\r\n" . 'Content-Type: application/json',
				'content' => json_encode(
					array(
						'app' => APP_NO,
						'record' => array(
							"userid" => array("value" => $userid),    //このフィールドが主キー扱い
							"chounaikai" => array("value" =>$chounaikai)
						)
					)
				)
			)
		);
		$context = stream_context_create( $options );
		$url = "https://" . SUB_DOMAIN . "/k/v1/record.json";
		$contents = file_get_contents($url, FALSE, $context );
		error_log($contents);
	}
} else {
	// 確認ダイアログタイプ
	$messageData = array(
		'type' => 'text',
		'text' => '町内会を登録してください。'
	);

}

$response = [
    'replyToken' => $replyToken,
    'messages' => [$messageData]
];
error_log(json_encode($response));

$ch = curl_init('https://api.line.me/v2/bot/message/reply');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
));
$result = curl_exec($ch);
error_log($result);
curl_close($ch);
