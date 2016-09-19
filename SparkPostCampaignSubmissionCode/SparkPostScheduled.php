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
<title>SparkPost Campaign Submission</title>
<link rel="shortcut icon" href="https://dl.dropboxusercontent.com/u/4387255/Tools.ico" type="image/vnd.microsoft.icon" />

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



#tableformat {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#tableformat td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

#tableformat tr:nth-child(even){background-color: #f2f2f2;}

#tableformat tr:hover {background-color: #ddd;}

#tableformat th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #F5870A;
    color: white;
}

table, th, td {
   border: 1px solid black;
   width: 600px;
   padding: 2px 2px 2px 4px;
}

tbody tr:nth-child(odd) {
   background-color: #ccc;
}

input[type=expandtext] {
    width: 130px;
    box-sizing: border-box;
    border: 8px solid #black;
    border-radius: 4px;
    font-size: 12px;
    background-color: white;
    background-position: 10px 10px;
    background-repeat: no-repeat;
    padding: 2px 2px 2px 4px;
    -webkit-transition: width 0.4s ease-in-out;
    transition: width 0.4s ease-in-out;
}

input[type=expandtext]:focus {
    width: 250px;
    border: 3px solid #555;
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
$apikey = htmlentities($_GET["apikey"]);
$url = "https://api.sparkpost.com/api/v1/transmissions/" . $id;
//echo $url;
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
// get all transmission, then only grab the ones we want.  API can't search on 'state' yet
//
$apikey = $_GET["apikey"];
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sparkpost.com/api/v1/transmissions/",
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

<h1><center> SparkPostMail Scheduled Campaigns </center></h1>
<br><br>
<center><table id="tableformat">
<tr>
<th>Campaign Name</th>
<th>Template Name</th>
<th>Recipient List</th>
<th>Scheduled Time for Launch*</th>
<th>Internal Campaign ID Number</th>
</tr>
<?php
  //
  // Display only records where the 'state' field is 'submitted'
  //
  foreach ($someArray as $key => $value) 
  {foreach ($value as $key2 => $value2) 
   {if ($value2['state']=="submitted") 
      {echo "<tr><td><h3>" . $value2['campaign_id'] . "</h3></td><td><h3>" . $value2['content']['template_id'] . "</h3></td><td><h3>" . $value2['recipients']['list_id'] . "</h3></td><td><h3>" . $value2['start_time'] . "</h3></td><td><h3>" . $value2['id'] . "</h3></td></tr>"; 
$submittedOnly[] = array('state' => $value2['state'], 'campaign_id' => $value2['campaign_id'], 'id' => $value2['id'], 'start_time' => $value2['start_time'], 'template_id' => $value2['content']['template_id'], 'recipients' => $value2['recipients']['list_id']);}}}
?>
</table></center>
<form name=getid action="" method="get">
<h3>Enter The Internal Campaign ID You Wish to Cancel:</h3>
<input type="hidden" name="apikey" value="<?php echo $apikey ?>">
  <input name="id" type="expandtext" required placeholder="Campaign ID Number.."><br><br>
<input type="submit" name="submit" value="Cancel Campaign" STYLE="color: #FFFFFF; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #72A4D2;" size="10" >
</form>
<p>OR
<form name=goback action="SparkPostSubmit.php">
<input type="hidden" name="apikey" value="<?php echo $apikey ?>">
<input type="submit" name="submit" value="Go Back To Campaign Creation Page" STYLE="color: #FFFFFF; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #72A4D2;" size="10" >
</form>
<p>* Your Campaign Time has been converted to (and showing) GMT Time
</body>
</html>