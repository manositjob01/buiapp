<?php 
include"function.php";
	/*Get Data From POST Http Request*/
	$datas = file_get_contents('php://input');
	/*Decode Json From LINE Data Body*/
	$deCode = json_decode($datas,true);

	file_put_contents('log.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);
	$messageText = $deCode["events"][0]["message"];
	$replyToken = $deCode['events'][0]['replyToken'];

	$messages = [];
	$messages['replyToken'] = $replyToken;
	$messageInput = $deCode['events'][0]["message"]["text"];
	$pos = strpos($messageInput,":");
	$flage_status = false;
	if($pos!=false){

	
		$sourceInput = explode(":",$messageInput);
		if(strtoupper($sourceInput[0])=="REGISTER"){
			$data = explode("@",sourceInput[1]);
			$flage_status = true;
			$messages['messages'][0] = getFormatTextMessage("ทำการสมัครสมาชิกเสร็จสิ้นแล้ว");
			SendAPI($data[1],$data[0],$replyToken);
		}

	}
	
	$encodeJson = json_encode($messages);

	$LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
  	$LINEDatas['token'] = "oPv+uZJTdZLoNa+edPtGTj0bjhaoA3/6KaHl3BZ4THohXrD8MMtnDLgVzb5SCopNp8PbNF9RlIAn664eMDnwvhafX3pwFjeks35MMRxw/9NErEY1UOyQ/Qhj1pRMV5GFbQq/3XtRfNk9T0oF2H3hPAdB04t89/1O/w1cDnyilFU=";
	if($flage_status){
  	$results = sentMessage($encodeJson,$LINEDatas);
	}
	/*Return HTTP Request 200*/
	http_response_code(200);

	


?>
