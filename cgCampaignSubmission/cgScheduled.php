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

File: cgScheduled
Purpose: Show currently scheduled campaigns and allow user to cancel them

 -->
<!DOCTYPE html>
<html><head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Campaign Generator for SparkPost</title>
<link rel="shortcut icon" href="http://bit.ly/2elb0Hw" type="image/vnd.microsoft.icon" />
<link rel="stylesheet" type="text/css" href="cgCommonFormatting.css">
<style>
#tableformat 
{
    font-family: Helvetica, Arial;
    border-collapse: collapse;
    width: 100%;
}

#tableformat td, #customers th 
{
    border: 1px solid #ddd;
    padding: 8px;
}

#tableformat tr:nth-child(even){background-color: #fcf7f4;}

#tableformat tr:hover {background-color: #e5e4e3;}

#tableformat th 
{
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #a3e9f7;
    color: black;
}

table, th, td 
{
   border: 1px solid black;
   width: 600px;
   padding: 2px 2px 2px 4px;
   border-collapse: collapse;
}

tbody tr:nth-child(odd) 
{
   background-color: #e8f9f9;
}
</style>
</head>

<script>
$(function()
{
    $("form").submit(function()
    {
        $(this).children(':input[value=""]').attr("disabled", "disabled");
        return true; // ensure form still submits
    });
});
</script>

<?php
//
// Call this on 'form submit' to remove scheduled campaigns
//
if( isset($_GET['submit']) )
{
$id = htmlentities($_GET["id"]);
$hash = $_GET["apikey"];
$apiroot = $_GET["apiroot"];
$apikey = hex2bin($hash);
$url = $apiroot . "/transmissions/" . $id;

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "$url",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "DELETE",
  CURLOPT_HTTPHEADER => array(
    "authorization: $apikey",
    "cache-control: no-cache",
    "content-type: application/json",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
}

// Convert JSON string to Array
$someArray = json_decode($response, true);
header("Refresh:0");
}
?>

<body id="bkgnd">
<?php
//
// get hash
//
$hash = $_GET["apikey"];
$apiroot = $_GET["apiroot"];
?>
<ul class="topnav" id="generatorTopNav">
  <li><a class="active" href="cgKey.php">Home</a></li>
  <li><a class="active" href="cgBuildSubmitStored.php<?php echo '?apikey=' . $hash .'&apiroot=' . $apiroot ?>">Generate Campaign</a></li>
  <li><a class="active" href="cgBuildSubmitCSVJSON.php<?php echo '?apikey=' . $hash .'&apiroot=' . $apiroot ?>">Generate Campaign Using CSV or JSON</a></li>
  <li><a href="cgHelp.php">Help</a></li>
  <li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
  <li><a href="mailto:email.goldstein@gmail.com?subject=cgMail">Contact</a></li>
  <li class="icon">
    <a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
  </li>
</ul>

<script>
function generatorNav() {
    var x = document.getElementById("generatorTopNav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>
<?php
//
// get all transmission, then only grab the ones we want.  API can't search on 'state' yet
//
//$hash = $_GET["apikey"];
$apikey = hex2bin($hash);
$url = $apiroot . "/transmissions";
$curl = curl_init();
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
    "content-type: application/json",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
//echo $response;
if ($err) {
  echo "cURL Error #:" . $err;
}
$someArray = json_decode($response, true);
?>

<br><h1><center>Scheduled Campaigns</center></h1>
<br><br>
<table id="tableformat">
<tr>
<th><center>Campaign Name</center></th>
<th><center>Template Name</center></th>
<th><center>Recipient List</center></th>
<th><center>Number of Recipients</center></th>
<th><center>Scheduled Time for Launch*</center></th>
<th><center>Internal Campaign ID Number</center></th>
</tr>
<?php
  //
  // Display only records where the 'state' field is 'submitted'
  //
  foreach ($someArray as $key => $value) 
  {
  		foreach ($value as $key2 => $value2) 
		{
			if ($value2['state']=="submitted") 
			{
      			$row  = "<tr><td><h3 style='color:black'>" . $value2['campaign_id'];
      			$row .= "</h3></td><td><h3 style='color:black'>" . $value2['content']['template_id'];
      			$row .= "</h3></td><td><h3 style='color:black'>" . $value2['recipients']['list_id'];
      			$row .= "</h3></td><td><h3 style='color:black'>" . $value2['description'];
      			$startTimeFull = $value2['start_time'];
      			$endDatePos = strpos($startTimeFull, "T");
      			$schedDate = substr($startTimeFull, 0, $endDatePos);
      			$schedTime = substr($startTimeFull, $endDatePos + 1, 5);
      			$row .= "</h3></td><td><h3 style='color:black'>" . $schedDate . " at " . $schedTime;
      			$row .= "</h3></td><td><h3 style='color:black'>" . $value2['id'] . "</h3></td></tr>";
      			echo $row;
      		}
		}
    }	
//echo "<tr><td><h3 style='color:black'>" . $value2['campaign_id'] . "</h3></td><td><h3 style='color:black'>" . $value2['content']['template_id'] . "</h3></td><td><h3 style='color:black'>" . $value2['recipients']['list_id'] . "</h3></td><td><h3 style='color:black'>" . $value2['start_time'] . "</h3></td><td><h3 style='color:black'>" . $value2['id'] . "</h3></td></tr>"; 
//$submittedOnly[] = array('state' => $value2['state'], 'campaign_id' => $value2['campaign_id'], 'id' => $value2['id'], 'start_time' => $value2['start_time'], 'template_id' => $value2['content']['template_id'], 'recipients' => $value2['recipients']['list_id']);}}}
?>
</table></center>
<form name=getid action="" method="get">
<p><h3>Enter The Internal Campaign ID You Wish to Cancel:</h3>
<input type="hidden" name="apikey" value="<?php echo $hash ?>">
<input type="hidden" name="apiroot" value="<?php echo $apiroot ?>">
<input name="id" type="text" required placeholder="Campaign ID Number.."><br><br>
<input type="submit" name="submit" value="Cancel Campaign" STYLE="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" size="10" >
</form>
<p>* Your Campaign Time has been converted to (and showing) GMT Time
</body>
</html>