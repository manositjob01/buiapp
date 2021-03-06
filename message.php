<?php 
	/*Get Data From POST Http Request*/
	$datas = file_get_contents('php://input');
	/*Decode Json From LINE Data Body*/
	$deCode = json_decode($datas,true);

	file_put_contents('log.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);
	$messageText = $deCode["events"][0]["message"];
	$replyToken = $deCode['events'][0]['source']['userId'];
	$accessKeyTokenUser = $deCode['events'][0]['replyToken'];

	$messages = [];
	$messages['replyToken'] = $accessKeyTokenUser;
	$messageInput = $deCode['events'][0]["message"]["text"];
	$pos = strpos($messageInput,":");
	$flage_status["doAPI"] = false;	
	$flage_status["status"] = false;
	$flage_status["message"] ="";
	if($pos!=false){

	
		$sourceInput = explode(":",$messageInput);
		if(strtoupper($sourceInput[0])=="REGISTER"){
			$data = explode("@",$sourceInput[1]);
			$flage_status["doAPI"] = true;
			$cmd = SendAPI($data[1],$data[0],$replyToken);
			$cmd = substr($cmd,1,strlen($cmd)-2);
			$flage_status["message"] = $cmd;
			if($cmd=="done"){
				$flage_status["status"] = true;
				
			}
		}

	}
	

	$LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
  	$LINEDatas['token'] = "oPv+uZJTdZLoNa+edPtGTj0bjhaoA3/6KaHl3BZ4THohXrD8MMtnDLgVzb5SCopNp8PbNF9RlIAn664eMDnwvhafX3pwFjeks35MMRxw/9NErEY1UOyQ/Qhj1pRMV5GFbQq/3XtRfNk9T0oF2H3hPAdB04t89/1O/w1cDnyilFU=";
	if($flage_status["doAPI"]==true){
		if($flage_status["status"]==true){
			
	$messages['messages'][0] = getFormatTextMessage("Register Complete กรุณาตรวจสอบอีเมล์เพื่อยืนยันการสมัครสมาชิกและเปิดดำเนินการ");
	$encodeJson = json_encode($messages);
  	$results = sentMessage($encodeJson,$LINEDatas);
		}else{
		$messages['messages'][0] = getFormatTextMessage("User ID Not Invalite");
	$encodeJson = json_encode($messages);	
	$results = sentMessage($encodeJson,$LINEDatas);
		}
	}else{


		$messages['messages'][0] = getFormatTextMessage(ApiRead($deCode["events"][0]["message"]["text"]));
	$encodeJson = json_encode($messages);	
	$results = sentMessage($encodeJson,$LINEDatas);
	}
	/*Return HTTP Request 200*/
	http_response_code(200);


	function getFormatTextMessage($text)
	{
		$datas = [];
		$datas['type'] = 'text';
		$datas['text'] = $text;

		return $datas;
	}

	function ApiRead($detail){
		$urlAPI="http://58.181.144.100:9081/api/";	
	$url = $urlAPI."api/Message";
	$param ="Detail=$detail";
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	$result = curl_exec($ch);
	curl_close ($ch);
	$result = substr($result,1,strlen($result)-2);
	return $result;
	}

	function SendAPI($server,$username,$accessKey){
	$urlAPI="http://58.181.144.100:9081/api/";	
	$url = $urlAPI."api/RegisterToken?server=$server";
	$param ="username=$username&accessToken=$accessKey";
	$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	$result = curl_exec($ch);
	curl_close ($ch);
	return $result;
	}

	function sentMessage($encodeJson,$datas)
	{
		$datasReturn = [];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $datas['url'],
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $encodeJson,
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer ".$datas['token'],
		    "cache-control: no-cache",
		    "content-type: application/json; charset=UTF-8",
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		    $datasReturn['result'] = 'E';
		    $datasReturn['message'] = $err;
		} else {
		    if($response == "{}"){
			$datasReturn['result'] = 'S';
			$datasReturn['message'] = 'Success';
		    }else{
			$datasReturn['result'] = 'E';
			$datasReturn['message'] = $response;
		    }
		}

		return $datasReturn;
	}
?>

