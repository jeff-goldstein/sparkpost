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
<!DOCTYPE html>
<html><head>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <title>SparkPost Campaign Submittion</title>
   <link rel="shortcut icon" href="https://dl.dropboxusercontent.com/u/4387255/Tools.ico">
</head>

<style>
#bkgnd {
    background-image: url(https://dl.dropboxusercontent.com/u/4387255/bwback.jpg);
    background-position: right bottom, left top;
    background-repeat: no-repeat;
    padding: 15px;
}

h2 {
    display: block;
    font-size: 1.5em;
    margin-top: 0.83em;
    margin-bottom: 0.83em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
}

h3 {
    display: block;
    font-size: 1.17em;
    margin-top: 1em;
    margin-bottom: 1em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
    color: #298272;
}

h4 { 
    display: block;
    margin-top: 1.33em;
    margin-bottom: 1.33em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
    color: #298272;
}

h4.red { 
    display: block;
    margin-top: 1.33em;
    margin-bottom: 1.33em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
    color: #999999;
}

body {margin:0;}
ul.topnav {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
}

ul.topnav li {float: left;}

ul.topnav li a {
  display: inline-block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  transition: 0.3s;
  font-size: 17px;
}

ul.topnav li a:hover {background-color: #555;}

ul.topnav li.icon {display: none;}

@media screen and (max-width:680px) {
  ul.topnav li:not(:first-child) {display: none;}
  ul.topnav li.icon {
    float: right;
    display: inline-block;
  }
}

@media screen and (max-width:680px) {
  ul.topnav.responsive {position: relative;}
  ul.topnav.responsive li.icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  ul.topnav.responsive li {
    float: none;
    display: inline;
  }
  ul.topnav.responsive li a {
    display: block;
    text-align: left;
  }
}

</style>

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

<body id="bkgnd">
<?php
//
// get hash
//
$hash = $_GET["apikey"];
?>
<ul class="topnav" id="myTopnav">
  <li><a class="active" href="SparkPostKey.php">Home</a></li>
  <li><a class="active" href="SparkPostSubmit.php<?php echo '?apikey=' . $hash ?>">Campaign Generation</a></li>
  <li><a class="active" href="SparkPostScheduled.php<?php echo '?apikey=' . $hash ?>">Scheduled Campaigns</a></li>
  <li><a href="SparkPostHelp.php">Help</a></li>
  <li><a href="#contact">Contact</a></li>
  <li><a href="https://developers.sparkpost.com/">SparkPost Documentation</a></li>
  <li class="icon">
    <a href="javascript:void(0);" style="font-size:15px;" onclick="myNav()">☰</a>
  </li>
</ul>
<script>
function myNav() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>
<table width = 1000 border=0><tr><td><h1><center>Campaign Submission Receipt</center></h1></td></tr></table>
<?php
//
// Get values entered by user
//
$key = hex2bin($hash);
$template = $_GET["Template"];
$recipients = $_GET["Recipients"];
$now = $_GET["now"];
$date = $_GET["date"];
$hour = $_GET["hour"];
$minutes = $_GET["minutes"];
$tz = $_GET["tz"];
$campaign = $_GET["campaign"];
$open = $_GET["open"];
$click = $_GET["click"];
$email = $_GET["email"];
$meta1 = trim($_GET["meta1"], " ");
$data1 = trim($_GET["data1"], " ");
$meta2 = trim($_GET["meta2"], " ");
$data2 = trim($_GET["data2"], " ");
$meta3 = trim($_GET["meta3"], " ");
$data3 = trim($_GET["data3"], " ");
$meta4 = trim($_GET["meta4"], " ");
$data4 = trim($_GET["data4"], " ");
$meta5 = trim($_GET["meta5"], " ");
$data5 = trim($_GET["data5"], " ");

//
//Build the payload for the Transmission API call
//
$transmissionLoad = '{"options": { "open_tracking" :';
if ($open == "T") $transmissionLoad .= 'true, "click_tracking" : '; else $transmissionLoad .= 'false, "click_tracking" : ';
if ($click == "T") $transmissionLoad .= 'true, "start_time" : '; else $transmissionLoad .= 'false, "start_time" : ';
if (!empty($date)) $transmissionLoad .= '"' . $date . 'T' . $hour . ':' . $minutes . ':00' . $tz . '"}, '; else $transmissionLoad .= '"now"}, ';
$transmissionLoad .= '"content" : {"template_id" : "' . $template . '","use_draft_template" : false  },';
$transmissionLoad .= '"recipients" : {"list_id" : "' . $recipients . '"},';
$transmissionLoad .= '"campaign_id" : "' . $campaign . '" ';

 if (($meta1 != "") or ($meta2 != "") or ($meta3 != "") or ($meta4 != "") or ($meta5 != ""))
{
   $transmissionLoad .= ', "metadata" : {';
   if ($meta1 != "") {$transmissionLoad .= '"' . $meta1 . '":"' . $data1 . '",';}
   if ($meta2 != "") {$transmissionLoad .= '"' . $meta2 . '":"' . $data2 . '",';}
   if ($meta3 != "") {$transmissionLoad .= '"' . $meta3 . '":"' . $data3 . '",';}
   if ($meta4 != "") {$transmissionLoad .= '"' . $meta4 . '":"' . $data4 . '",';}
   if ($meta5 != "") {$transmissionLoad .= '"' . $meta5 . '":"' . $data5 . '"';}
   $transmissionLoad = trim($transmissionLoad, ",");
   $transmissionLoad .= "}";
 }    

$transmissionLoad .= "}";
//echo $transmissionLoad;


//
// Schedule the campaign
//
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sparkpost.com/api/v1/transmissions",
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
  ),
));
$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  //echo $response;
}

//
//Build a table to echo what the user entered; this will be used in the email 
//
//$validationText  = "<table width = 1000 border=0><tr><h1>Form input used for Campaign</h1></tr></table>";
$validationText .= "<h4 id=red>Email Confirmation Sent To: " . $email . "</h4>";
$validationText .= "<form><table width='1000' border=1><tr><td>";
$validationText .= "<h5>Campaign Name: " . $campaign . "</h5>";
$validationText .= "<h5>Template: " . $template . "</h5>";
$validationText .= "<h5>Recipients: " . $recipients . "</h5>";
$validationText .= "<h5>Send Now Flag: " . $now . "</h5>";
$validationText .= "<h5>";
if ($date && $now) {$validationText .= "</h5><h4><strong><i>**Notice** Send Date/Time was entered and will override Send Now Flag, Campaign Scheduled</i></strong><br>";}
$validationText .= "   Scheduled Date/Time: " . $date . " at: " . $hour . ":" . $minutes . " with a timezone offset from GMT: " . $tz;
if ($date && $now) {$validationText .= "</h4>";} else {$validationText .= "</h5>";}
$validationText .= "<h5>Track Open Flag: " . $open . "</h5>";
$validationText .= "<h5>Track Clicks Flag: " . $click . "</h5>";
$validationText .= "<p><table width='400'>";
//$validationText .= "<tr><th colspan='2'><center>Meta Data Entries</center></th></tr>";
$validationText .= "<tr><th align=left>Metadata Names</th><th align=left>Metadata Values</th></tr>";
$validationText .= "<tr><td width=200 height=30>" . $meta1 . "</td><td width=200 height=30>" . $data1 . "</td></tr>";
$validationText .= "<tr><td width=200 height=30>" . $meta2 . "</td><td width=200 height=30>" . $data2 . "</td></tr>";
$validationText .= "<tr><td width=200 height=30>" . $meta3 . "</td><td width=200 height=30>" . $data3 . "</td></tr>";
$validationText .= "<tr><td width=200 height=30>" . $meta4 . "</td><td width=200 height=30>" . $data4 . "</td></tr>";
$validationText .= "<tr><td width=200 height=30>" . $meta5 . "</td><td width=200 height=30>" . $data5 . "</td></tr></table></table>";
$validationText .= "</td></table></tr></form>";

echo $validationText;
?>

<table width="1000"><tr><td>
<center><h4>Output from SparkPost Server</h4></center>
<h3><?php echo "$response"; ?></h3></td></tr></table>

<?php 
//
// Now build the text that will be sent to the user via email
//
$singlequoteResponse = str_replace('"',"'",$response);
$singlequoteResponse = str_replace(array('{', '}'), array(""),$singlequoteResponse);
//Build the payload
$emailReceipt = '{"content":{"from": {"name": "SparkPost Campaign Admin","email": "SparkPost@geekwithapersonality.com"},';
$emailReceipt .= '"subject" : "Your Campaign receipt for {{campaign_name}}", ';
$emailReceipt .= '"reply_to" : "NoReply <no-reply@geekwithapersonality.com>", ';
$emailReceipt .= '"html" : "<p>Your Campaign has been launched as requested.  Your input was: <br>' . $validationText . '<br> and the server response was: ' . $singlequoteResponse . '"},';
$emailReceipt .= '"recipients": [{"address": {"email": "' . $email . '"},';
$emailReceipt .= '"substitution_data": {"validationText": "' . $validationText . '",';
$emailReceipt .= '"campaign_name": "' . $campaign . '"}';
$emailReceipt .= "}]}";
//echo htmlspecialchars($emailReceipt);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sparkpost.com/api/v1/transmissions",
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
  ),
));
$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  //echo "admin email response: " . $response;
}
?>

</body>
</html>
