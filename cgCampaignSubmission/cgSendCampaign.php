<!-- Copyright 2016 Jeff Goldstein

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License. 

File: cgSendCampaign.php
Purpose: This program sends/creates the actual campaign by calling the 'Transmission' API.
		 It gets input from cgConfirmSubmission.php after the user presses the confirmation button.
		 Once the campaign is created; output is generated from the server response text and 'echo'ed back
		 to the calling application for display.  An email may be sent with the confirmation info if 
		 requested during the submission.  
-->
<?php
{

//
// Get values entered by user
//
$key = $_POST["apikey"];
$apiroot = $_POST["apiroot"];
$template = $_POST["template"];
$recipients = $_POST["recipients"];
$now = $_POST["now"];
$date = $_POST["date"];
$hour = $_POST["hour"];
$minutes = $_POST["minutes"];
$tz = $_POST["tz"];
$campaign = $_POST["campaign"];
$returnpath = $_POST["returnpath"];
$open = $_POST["open"];
$click = $_POST["click"];
$email = $_POST["email"];
$meta1 = trim($_POST["meta1"], " ");
$data1 = trim($_POST["data1"], " ");
$meta2 = trim($_POST["meta2"], " ");
$data2 = trim($_POST["data2"], " ");
$meta3 = trim($_POST["meta3"], " ");
$data3 = trim($_POST["data3"], " ");
$meta4 = trim($_POST["meta4"], " ");
$data4 = trim($_POST["data4"], " ");
$meta5 = trim($_POST["meta5"], " ");
$data5 = trim($_POST["data5"], " ");
$jsonLoad  = $_POST["jsonLoad"];
$globalsub  = $_POST["globalsub"];
$validationText = $_POST["validationText"];
$recipientCount = $_POST["recipientCount"];


function confirmedSend($validationText)
{
	$payload = buildPayload();
	$serverResponse = sendScheduleCampaign($payload);
	$responseText = "<br><br><table><td rowspan='20' width='20'></td>";
	$responseText .= "<tr><td><table width='990' border=1 bgcolor=lightgrey cellpadding='30'><tr><td>";
	$responseText .= "<center><h4>Output from SparkPost Server</h4></center>";
	$responseText .= "<h5 style='font-family:courier'>" . $serverResponse . "</h5></td></tr></table></td></tr></table>";
	echo $responseText;
	sendConfirmationEmail($validationText, $serverResponse);
}

function buildPayload()
{
	global $key, $template, $recipients, $now, $date, $hour, $minutes, $tz, $campaign, $returnpath, $open, $click, $email; 
	global $meta1, $data1, $meta2, $data2, $meta3, $data3, $meta4, $data4, $meta5, $data5, $jsonLoad, $globalsub, $recipientCount;

	//
	//Build the payload for the Transmission API call
	//
	$transmissionLoad = '{"options": { "open_tracking" :';
	if ($open == "T") $transmissionLoad .= 'true, "click_tracking" : '; else $transmissionLoad .= 'false, "click_tracking" : ';
	if ($click == "T") $transmissionLoad .= 'true, "start_time" : '; else $transmissionLoad .= 'false, "start_time" : ';
	if (!empty($date)) $transmissionLoad .= '"' . $date . 'T' . $hour . ':' . $minutes . ':00' . $tz . '"}, '; else $transmissionLoad .= '"now"}, ';
	$transmissionLoad .= '"content" : {"template_id" : "' . $template . '","use_draft_template" : false  },';
	$transmissionLoad .= '"campaign_id" : "' . $campaign . '", ';
	if ($returnpath != "") $transmissionLoad .= '"return_path" : "' . $returnpath . '", ';
	$transmissionLoad .= '"description" : "' . $recipientCount . ' recipients targeted",';
	if (($meta1 != "") or ($meta2 != "") or ($meta3 != "") or ($meta4 != "") or ($meta5 != ""))
	{
   		$transmissionLoad .= '"metadata" : {';
   		if ($meta1 != "") {$transmissionLoad .= '"' . $meta1 . '":"' . $data1 . '",';}
   		if ($meta2 != "") {$transmissionLoad .= '"' . $meta2 . '":"' . $data2 . '",';}
   		if ($meta3 != "") {$transmissionLoad .= '"' . $meta3 . '":"' . $data3 . '",';}
   		if ($meta4 != "") {$transmissionLoad .= '"' . $meta4 . '":"' . $data4 . '",';}
   		if ($meta5 != "") {$transmissionLoad .= '"' . $meta5 . '":"' . $data5 . '"';}
   		$transmissionLoad = trim($transmissionLoad, ",");
   		$transmissionLoad .= "},";
	}
	if (strlen($globalsub) > 2) $transmissionLoad .= $globalsub . ","; //if there is more than 2 characters there is something..hey put in any number :-)
	if ($recipients == "cgJson")
	{
		$jsonLoad = substr($jsonLoad, 1);
		$jsonLoad = substr($jsonLoad, 0, -1);
		$transmissionLoad .= $jsonLoad;
	}
	else
	{
		$transmissionLoad .= '"recipients" : {"list_id" : "' . $recipients . '"}';
	}
	$transmissionLoad .= "}";
	return $transmissionLoad;
	
}

function sendScheduleCampaign($transmissionLoad)
{
	//
	// Schedule/Send the campaign
	//
	global $key, $apiroot;
	
	$curl = curl_init();
	$url = $apiroot . "/transmissions";
	curl_setopt_array($curl, array(
  	CURLOPT_URL => $url,
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "POST",
  	CURLOPT_POSTFIELDS => "$transmissionLoad",
  	CURLOPT_HTTPHEADER => array(
    "authorization: $key",
    "cache-control: no-cache",
    "content-type: application/json",
  	),));
	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) 
	{
  		echo "cURL Error #:" . $err;
	} 
	else 
	{
  		// nada
	}
	
	return $response;
}


function sendConfirmationEmail($validationResponse, $response)
{
	//
	// Now build the text that will be sent to the user via email
	//
	
	global $key, $email, $campaign, $apiroot;
	$singlequoteResponse = str_replace('"',"'",$response);
	$singlequoteResponse = str_replace(array('{', '}'), array(""),$singlequoteResponse);
	//Build the payload
	$emailReceipt = '{"content":{"from": {"name": "SparkPost Campaign Admin","email": "SparkPost@geekwithapersonality.com"},';
	$emailReceipt .= '"subject" : "Your Campaign receipt for {{campaign_name}}", ';
	$emailReceipt .= '"reply_to" : "NoReply <no-reply@geekwithapersonality.com>", ';
	$emailReceipt .= '"html" : "<p>Your Campaign has been launched as requested.  Your input was: <br><br><br>' . $validationResponse;
	$serverReplyBox	   = '<br><table><td rowspan=\"20\" width=\"20\"></td><tr><td><table border=1 bgcolor=lightgrey cellpadding=\"30\" width=\"1000\"><tr><td><center><h2>Output from SparkPost Server</h2><h3> ';
	$serverReplyBox    .= $singlequoteResponse . '</h3></center></td></tr></table></td></tr></table>';
	$emailReceipt .= $serverReplyBox . '"},"recipients": [{"address": {"email": "' . $email . '"},';
	$emailReceipt .= '"substitution_data": {"validationText": "' . $validationResponse . '",';
	$emailReceipt .= '"campaign_name": "' . $campaign . '"}';
	$emailReceipt .= "}]}";
	
	$curl = curl_init();
	$url = $apiroot . "/transmissions";
	curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "POST",
  	CURLOPT_POSTFIELDS => "$emailReceipt",
  	CURLOPT_HTTPHEADER => array(
    "authorization: $key",
    "cache-control: no-cache",
    "content-type: application/json",
  	),));
	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) 
	{
  	echo "cURL Error #:" . $err;
	} 
	else 
	{
  		//echo "admin email response: " . $response;
	}
}

confirmedSend($validationText);

}
?>
