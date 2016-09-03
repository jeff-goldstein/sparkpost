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
</style>

<script type="text/javascript">
function myFunction()
{
    var myForm = document.getElementById('form-id');
    var allInputs = myForm.getElementsByTagName('input');
    var input, i;

    for(i = 0; input = allInputs[i]; i++) {
        if(input.getAttribute('name') && !input.value) {
            input.setAttribute('name', '');
        }
    }
}
</script>

<body id="bkgnd">
<?php
$key = $_GET["apikey"];
// seteam apikey 2ad1d234cb3274b8390eba0b3062f8bc4cd0e73e
if (empty($key)) $key="e8e6345ff301a92842beebff298541a18ffdbff7";
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sparkpost.com/api/v1/templates/?draft=false",
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

if ($err) {
  echo "cURL Error #:" . $err;
}

  // Convert JSON string to Array
  $someArray = json_decode($response, true);
?>
<form action="SparkPostTransmission.php" method="get" onsubmit="myFunction()">
<input type="hidden" name="apikey" value="<?php echo $key ?>">
<h1><center> SparkPostMail Simple UI Campaign Generator </center></h1>
<h3>Select a Template (Showing Published Templates Only):*</h3>
<select name="Template">
<?php
  foreach ($someArray as $key => $value) 
  {foreach ($value as $key2 => $value2) 
 {foreach ($value2 as $key3 => $value3) 
   {if ($key3 == "id") echo '<option value="'.$value3.'">'.$value3.'</option>';}}}
?>
</select>

<?php
$key = $_GET["apikey"];
if (empty($key)) $key="e8e6345ff301a92842beebff298541a18ffdbff7";
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.sparkpost.com/api/v1/recipient-lists",
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

if ($err) {
  echo "cURL Error #:" . $err;
}

  // Convert JSON string to Array
  $someArrayofRecipients = json_decode($response, true);
?>

<h3>Select a Recipient List:*</h3>
<select name="Recipients">
<?php
  foreach ($someArrayofRecipients as $key => $value) 
  {foreach ($value as $key2 => $value2) 
  {foreach ($value2 as $key3 => $value3) 
  {if ($key3 == "id") echo '<option value="'.$value3.'">'.$value3.'</option>';}}}
?>
</select>

<h3>Launch now OR enter data & time of campaign launch (DD-MM-YYYY HH:mm)*</h3>
<input type="checkbox" name="now" id=now value="T" checked> OR Enter Date/Time 
<input type="text" id="datetime24" data-format="DD-MM-YYYY HH:mm" data-template="DD / MM / YYYY     HH : mm" name="datetime" value="DD-MM-YYYY HH:MM">
<script>
$(function(){
    $('#datetime24').combodate();  
});
</script>

<h3>Campaign Name:*</h3>

  <input name="campaign" type="text" required><br><br>
  <input type="checkbox" name="open" value="T" checked> Turn on Open Tracking<br>
  <input type="checkbox" name="click" value="T" checked> Turn on Click Tracking<br>
<h2>Optional Items (leave blank if you don't want to use them)...</h2>
<h4>Want Proof, Enter Your Email Address Here</h4>
<input type="text" name="email" value="">
<!-- <h4>Enter One Tag Per Box</h4>
//<input type="text" name="tag1" value=" ">&nbsp;&nbsp;&nbsp;
//<input type="text" name="tag2" value=" ">&nbsp;&nbsp;&nbsp;
//<input type="text" name="tag3" value=" ">&nbsp;&nbsp;&nbsp;
//<input type="text" name="tag4" value=" ">&nbsp;&nbsp;&nbsp;
//<input type="text" name="tag5" value=" ">&nbsp;&nbsp;&nbsp; -->
<h4>Enter Meta Data: first column Is the Metadata Field Name, the second column is the data:</h4>
Field Name: <input type="text" name="meta1" value=""> &nbsp;&nbsp;&nbsp;Data: <input type="text" name="data1" value=""><br>
Field Name: <input type="text" name="meta2" value=""> &nbsp;&nbsp;&nbsp;Data: <input type="text" name="data2" value=""><br>
Field Name: <input type="text" name="meta3" value=""> &nbsp;&nbsp;&nbsp;Data: <input type="text" name="data3" value=""><br>
Field Name: <input type="text" name="meta4" value=""> &nbsp;&nbsp;&nbsp;Data: <input type="text" name="data4" value=""><br>
Field Name: <input type="text" name="meta5" value=""> &nbsp;&nbsp;&nbsp;Data: <input type="text" name="data5" value=""><br>

<br><br><br><input type="submit" value="Submit" STYLE="color: #FFFFFF; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #72A4D2;" size="10" >
</form>
<p>* Mandatory fields.</p>
</body>
</html>
