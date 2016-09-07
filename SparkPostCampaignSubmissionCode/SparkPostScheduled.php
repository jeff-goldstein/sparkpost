<!DOCTYPE html>
<html><head>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<style>
#bkgnd {
    background-image: url(https://dl.dropboxusercontent.com/u/4387255/Color-Flame-BKGR_Transparent.png), url(https://dl.dropboxusercontent.com/u/4387255/Color-Flame-BKGR_Transparent.png);
    background-position: right bottom, left top;
    background-repeat: no-repeat, repeat;
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

table, th, td {
   border: 1px solid black;
   width: 600px;
   padding: 2px 2px 2px 4px;
}

tbody tr:nth-child(odd) {
   background-color: #ccc;
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
$key = $_GET["apikey"];
if (empty($key)) $key="e8e6345ff301a92842beebff298541a18ffdbff7";
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
    "authorization: $key",
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
<center><table>
<tr>
<th>Campaign Name</th>
<th>Scheduled Time for Launch</th>
</tr>
<?php
  foreach ($someArray as $key => $value) 
  {foreach ($value as $key2 => $value2) 
   {if ($value2['state']=="submitted") echo "<tr><td><h3>" . $value2['campaign_id'] . "</h3></td><td><h3>" . $value2['start_time'] . "</h3></td></tr>";}}
?>
</table></center>
</body>
</html>
