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
//if (empty($key)) $key="e8e6345ff301a92842beebff298541a18ffdbff7";
$template = $_GET["Template"];
$recipients = $_GET["Recipients"];
$now = $_GET["now"];
$datetime = $_GET["datetime"];
$campaign = $_GET["campaign"];
$open = $_GET["open"];
$click = $_GET["click"];
$email = $_GET["email"];
//$tag1 = trim($_GET['tag1'], " ");
//$tag2 = trim($_GET['tag2'], " ");
//$tag3 = trim($_GET['tag3'], " ");
//$tag4 = trim($_GET['tag4'], " ");
//$tag5 = trim($_GET['tag5'], " ");
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
if ($now == "T") $builditandtheywillcome .= '"now"}, '; else $builditandtheywillcome .= '"$datetime"}, ';
$builditandtheywillcome .= '"content" : {"template_id" : "' . $template . '","use_draft_template" : false  },';
$builditandtheywillcome .= '"recipients" : {"list_id" : "' . $recipients . '"},';
$builditandtheywillcome .= '"campaign_id" : "' . $campaign . '",';
//if (($tag1 != "") or ($tag2 != "") or ($tag3 != "") or ($tag4 != "") or ($tag5 != ""))
//{
//   $builditandtheywillcome .= '"tags" : [';
//   if ($tag1 != "") {$builditandtheywillcome .= '"' . $tag1 . '",';}
//   if ($tag2 != "") {$builditandtheywillcome .= '"' . $tag2 . '",';}
//   if ($tag3 != "") {$builditandtheywillcome .= '"' . $tag3 . '",';}
//   if ($tag4 != "") {$builditandtheywillcome .= '"' . $tag4 . '",';}
//   if ($tag5 != "") {$builditandtheywillcome .= '"' . $tag5 . '"';}
//   $builditandtheywillcome = trim($builditandtheywillcome, ",");
//   $builditandtheywillcome .= "],";
// }
 if (($meta1 != "") or ($meta2 != "") or ($meta3 != "") or ($meta4 != "") or ($meta5 != ""))
{
   $builditandtheywillcome .= '"metadata" : {';
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
?>
<br><br>
<table><tr><td>
<center><h4>Output from SparkPost Server</h4></center>
<h3><?php echo "$response"; ?></h3></td></tr></table>

<p>
<a href="http://geekswithapersonality.com/cgi-bin/SparkPostSubmit.php?apikey=<?php echo $key ?>">Another Campaign?</a>

</body>
</html>
