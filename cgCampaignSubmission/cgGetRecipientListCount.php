<?php
{
//
// Get The Stored Recipient-Lists Templates from the Account
//

$apikey = $_POST["apikey"];
$apiroot = $_POST["apiroot"];
$recipients = $_POST["recipients"];

$url = $apiroot . "/recipient-lists/" . $recipients;
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array("authorization: $apikey","cache-control: no-cache","content-type: application/json")));

$response = curl_exec($curl);
$err      = curl_error($curl);
curl_close($curl);

if ($err) 
{
    echo "cURL Error #:" . $err;
}

// Convert JSON string to Array
$recipientArray = json_decode($response, true);
//echo "<pre>";
//print_r($recipientArray);
//echo "</pre>";
$count = $recipientArray["results"]["total_accepted_recipients"];
echo $count;
}
?>