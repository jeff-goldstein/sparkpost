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
<h1><center>Campaign Submission Output</center></h1>
<?php
$key = $_GET["apikey"];
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

//Build the payload
$builditandtheywillcome = '{"options": { "open_tracking" :';
if ($open == "T") $builditandtheywillcome .= 'true, "click_tracking" : '; else $builditandtheywillcome .= 'false, "click_tracking" : ';
if ($click == "T") $builditandtheywillcome .= 'true, "start_time" : '; else $builditandtheywillcome .= 'false, "start_time" : ';
if ($now == "T") $builditandtheywillcome .= '"now"}, '; else $builditandtheywillcome .= '"' . $date . 'T' . $hour . ':' . $minutes . ':00' . $tz . '"}, ';
$builditandtheywillcome .= '"content" : {"template_id" : "' . $template . '","use_draft_template" : false  },';
$builditandtheywillcome .= '"recipients" : {"list_id" : "' . $recipients . '"},';
$builditandtheywillcome .= '"campaign_id" : "' . $campaign . '" ';

 if (($meta1 != "") or ($meta2 != "") or ($meta3 != "") or ($meta4 != "") or ($meta5 != ""))
{
   $builditandtheywillcome .= ', "metadata" : {';
   if ($meta1 != "") {$builditandtheywillcome .= '"' . $meta1 . '":"' . $data1 . '",';}
   if ($meta2 != "") {$builditandtheywillcome .= '"' . $meta2 . '":"' . $data2 . '",';}
   if ($meta3 != "") {$builditandtheywillcome .= '"' . $meta3 . '":"' . $data3 . '",';}
   if ($meta4 != "") {$builditandtheywillcome .= '"' . $meta4 . '":"' . $data4 . '",';}
   if ($meta5 != "") {$builditandtheywillcome .= '"' . $meta5 . '":"' . $data5 . '"';}
   $builditandtheywillcome = trim($builditandtheywillcome, ",");
   $builditandtheywillcome .= "}";
 }    

$builditandtheywillcome .= "}";
//echo $builditandtheywillcome;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sparkpost.com/api/v1/transmissions",
 CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "$builditandtheywillcome",
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

$validationText = "<br><br><h1>Form input used for Campaign</h1><table>";
$validationText .= "<tr><td>APIKey: </td><td>" . $key . "</td></tr>";
$validationText .= "<tr><td>Template: </td><td>" . $template . "</td></tr>";
$validationText .= "<tr><td>Recipients: </td><td>" . $recipients . "</td></tr>";
$validationText .= "<tr><td>Send Now Flag: </td><td>" . $now . "</td></tr>";
$validationText .= "<tr><td>Send Date: </td><td>" . $date . "</td></tr>";
$validationText .= "<tr><td>Send Hour: </td><td>" . $hour . "</td></tr>";
$validationText .= "<tr><td>Send Minutes: </td><td>" . $minutes . "</td></tr>";
$validationText .= "<tr><td>TimeZone Offset from GMT: </td><td>" . $tz . "</td></tr>";
$validationText .= "<tr><td>Track Open Flag: </td><td>" . $open . "</td></tr>";
$validationText .= "<tr><td>Track Clicks Flag: </td><td>" . $click . "</td></tr></table>";
$validationText .= "<p><table><tr><th colspan='2'><center>Meta Data Entries</center><th></tr>";
$validationText .= "<tr><td width=300 height=30>" . $meta1 . "</td><td width=300 height=30>" . $data1 . "</td></tr>";
$validationText .= "<tr><td width=300 height=30>" . $meta2 . "</td><td width=300 height=30>" . $data2 . "</td></tr>";
$validationText .= "<tr><td width=300 height=30>" . $meta3 . "</td><td width=300 height=30>" . $data3 . "</td></tr>";
$validationText .= "<tr><td width=300 height=30>" . $meta4 . "</td><td width=300 height=30>" . $data4 . "</td></tr>";
$validationText .= "<tr><td width=300 height=30>" . $meta5 . "</td><td width=300 height=30>" . $data5 . "</td></tr></table>";

echo $validationText;
?>
<h4>Email Confirmation Address: <?php echo $email ?></h4>
<table><tr><td>
<center><h4>Output from SparkPost Server</h4></center>
<h3><?php echo "$response"; ?></h3></td></tr></table>

<p>
<a href="http://geekswithapersonality.com/cgi-bin/SparkPostSubmit.php?apikey=<?php echo $key ?>">Another Campaign?</a>


<?php 
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
