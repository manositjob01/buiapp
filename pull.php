<?php
include"function.php";
	if(isset($_POST["message"])){
	$dataItem = json_decode($_POST["message"],true);
	$messages = [];
	$messages['replyToken'] =$dataItem["accessToken"];
	$messages['messages'][0] = $dataItem["message"];
	$encodeJson = json_encode($messages);

	$LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
  	$LINEDatas['token'] = "oPv+uZJTdZLoNa+edPtGTj0bjhaoA3/6KaHl3BZ4THohXrD8MMtnDLgVzb5SCopNp8PbNF9RlIAn664eMDnwvhafX3pwFjeks35MMRxw/9NErEY1UOyQ/Qhj1pRMV5GFbQq/3XtRfNk9T0oF2H3hPAdB04t89/1O/w1cDnyilFU=";

  	$results = sentMessage($encodeJson,$LINEDatas);
	/*Return HTTP Request 200*/
	http_response_code(200);
	}

?>