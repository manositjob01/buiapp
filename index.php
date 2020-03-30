<?php
	
	$messages = [];
	$messages['replyToken'] ="abfdf604669f42e1b9f090061c1c174e";
	$messages['messages'][0] = getFormatTextMessage("aaaa");
	$encodeJson = json_encode($messages);

	$LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
  	$LINEDatas['token'] = "oPv+uZJTdZLoNa+edPtGTj0bjhaoA3/6KaHl3BZ4THohXrD8MMtnDLgVzb5SCopNp8PbNF9RlIAn664eMDnwvhafX3pwFjeks35MMRxw/9NErEY1UOyQ/Qhj1pRMV5GFbQq/3XtRfNk9T0oF2H3hPAdB04t89/1O/w1cDnyilFU=";

  	$results = sentMessage($encodeJson,$LINEDatas);
	echo json_encode($results);
	/*Return HTTP Request 200*/
	http_response_code(200);

	function getFormatTextMessage($text)
	{
		//$datas = [];
		$datas['type'] = 'text';
		$datas['text'] = $text;

		return $datas;
	}

	
	

	function sentMessage($encodeJson,$datas)
	{
		//$datasReturn = [];
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
