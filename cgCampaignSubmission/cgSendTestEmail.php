<!-- Copyright 2016 Jeff Goldstein

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License. -->
<?php 
{

function isJson($string) 
{
	global $json_check_result;
	json_decode($string);
	switch (json_last_error()) 
	{
        case JSON_ERROR_NONE:
            $json_check_result = ' No errors';
        break;
        case JSON_ERROR_DEPTH:
            $json_check_result =  ' Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            $json_check_result = ' Underflow or the modes mismatch, check brackets?';
        break;
        case JSON_ERROR_CTRL_CHAR:
            $json_check_result = ' Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            $json_check_result = ' Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            $json_check_result = ' Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            $json_check_result = ' Unknown error';
        break;
	}
	return (json_last_error() == JSON_ERROR_NONE);
}

function getSubData (&$subString, $string)
{
	foreach ($string as $key => $value)
	{
		if ($key==="substitution_data")
		{
			$encodedValue = json_encode($value);
			$subString .= $encodedValue;	
		}
		else
		{	
    		//if ($key != NULL)
    		{
    			getSubData($subString, $value);
    		}
    	}
    }
    return $subString;
}	

$curl       		= curl_init();
$apikey     		= $_POST["apikey"];
$apiroot     		= $_POST["apiroot"];
$template   		= $_POST["template"];
$recipients 		= $_POST["recipients"];
$emailaddresses     = $_POST["emailaddresses"];

$campaign = $_POST["campaign"];
$open = $_POST["open"];
$click = $_POST["click"];
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

if ($campaign === "") 
{
	$campaign = "Preview/Test Email";
}
    

$url = $apiroot . "/recipient-lists/" . $recipients . "?show_recipients=true";
curl_setopt_array($curl, array(
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array(
    "authorization: $apikey",
    "cache-control: no-cache",
    "content-type: application/json"
)));
    
$response = curl_exec($curl);
$err      = curl_error($curl);
curl_close($curl);
if ($err) 
{
    echo "cURL Error #:" . $err;
}

//    
// decode so we can get the first users substitution data
// encode the single users substitution data
// format correctly for transmission call
$someArrayofRecipients = json_decode($response, true);
$rec_sub               = $someArrayofRecipients['results']['recipients'][0]['substitution_data'];
$rec_sub  = json_encode($rec_sub);
$subEntry = '"substitution_data":' . $rec_sub . '}';

$previewTestRecipientList = explode(',', $emailaddresses);
//
//Build the payload for the Transmission API call
//

foreach ($previewTestRecipientList as $emailaddress)
{
	$transmissionLoad = '{"options": { "open_tracking" :';
	if ($open == "T") $transmissionLoad .= 'true, "click_tracking" : '; else $transmissionLoad .= 'false, "click_tracking" : ';
	if ($click == "T") $transmissionLoad .= 'true, '; else $transmissionLoad .= 'false, ';
	$transmissionLoad .=  '"start_time" : "now"}, ';
	$transmissionLoad .= '"content" : {"template_id" : "' . $template . '","use_draft_template" : false  },';
	$transmissionLoad .= '"campaign_id" : "' . $campaign . '", ';
	//$transmissionLoad .= '"return_path" : "' . "steve@smt-demo-spe.trymsys.net" . '", ';
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
	$transmissionLoad .= '"recipients" : [{"address" : "' . $emailaddress . '", ' . $subEntry . ']}';
	//
	// Schedule the campaign
	//
	$url = $apiroot . "/transmissions";
	$curl = curl_init();
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
    "authorization: $apikey",
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
  		//echo $response;
	}
}
}
?>
