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
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>SparkPost Campaign Submittion</title>
   <link rel="shortcut icon" href="https://dl.dropboxusercontent.com/u/4387255/Tools.ico">
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
   <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
   <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
   <script src="ckeditor/ckeditor.js"></script>
   <script>
  /* Set Calendar format; Using jQuery calendar because it works better across different browsers than default form calendar */
  $( function() {
    $( "#datepicker" ).datepicker( { dateFormat: 'yy-mm-dd' });
  } );
  </script>

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

select {
    padding: 16px 20px;
    border: 12px;
    border-color: #298272;
    border-radius: 4px;
    background-color: #ffffff;
    font-size: 12px;
}

/* Can use this when I don't want an expanding input field */
input[type=textnormal] {
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    font-size: 12px;
    background-color: white;
    background-position: 10px 10px;
    background-repeat: no-repeat;
    padding: 2px 2px 2px 4px;
    width : 200px;
}

/* This expands the text for more room while typing */
input[type=text] {
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

input[type=text]:focus {
    width: 250px;
    border: 3px solid #555;
}

input[type=number] {
    width: 40px;
    height: 23px;
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

input[type=number]:focus {
    width: 65px;
    border: 3px solid #555;
}

input[type=date] {
    width: 150px;
    height: 20px;
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

input[type=date]:focus {
    width: 200px;
    border: 3px solid #555;
}

#iframe1 {
    border: solid 0 px;
    border-radius: 8px;
    padding-top: 1em;
    margin: 0 auto;
}

.alert {
    padding: 20px;
    background-color: #f44336;
    color: white;
}

.tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: left;
    border-radius: 6px;
    padding: 5px 5px 5px 5px;
    font-size: 12px;

    /* Position the tooltip */
    position: absolute;
    z-index: 1;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
}

/* This forces more consistent look and field across browsers for pulldown select fields */
@media screen and (-webkit-min-device-pixel-ratio:0) {  /*safari and chrome*/
    select {
        height:25px;
        line-height:25px;
        background:#f4f4f4;
    } 
}
select::-moz-focus-inner { /*Remove button padding in FF*/ 
    border: 0;
    padding: 0;
}
@-moz-document url-prefix() { /* targets Firefox only */
    select {
        padding: 5px 0!important;
    }
}        
@media screen\0 { /* IE Hacks: targets IE 8, 9 and 10 */        
    select {
        height:30px;
        line-height:30px;
    }     
}
</style>


</head> 

<body id="bkgnd">
<?php
$apikey = $_GET["apikey"];
?>
<script type="text/javascript">
function updateCall()
{
var selectList = document.getElementById("Template");
var divAnswer  = document.getElementById("editor1");
var selectList2 = document.getElementById("Recipients");
var apikey = "<?php echo $apikey; ?>";

$.ajax({
      url:'getPreview.php',
      data: {"apikey" : apikey, "template" : selectList.value, "recipients" : selectList2.value},
      complete: function (response) 
      {
          $('#iframe1').contents().find('html').html(response.responseText);
          xbutton = document.getElementById("submit");
          var strCheck = "Matching Problem";
          var location = response.responseText.search(strCheck);
          if (location > 0) 
          {
              xbutton.disabled = true;
              xbutton.value = "Submit";
              xbutton.style.backgroundColor = "red";
              xbutton.style.color = "black";
              alert("Warning!! Template & Recipient error detected; please see preview box - Submit Turned off!");
          }
          else
          {
              xbutton.disabled = false;
              xbutton.value = "Submit";
              xbutton.style.color = "white";
              xbutton.style.backgroundColor = "#72A4D2";
          }
      },
      error: function () {
          $('#output').html('Bummer: there was an error!');
      }
  });
  return false;
}

</script>
<?php
//
// Get The 'Published Only' Templates from the Account
//
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
        "authorization: $apikey",
        "cache-control: no-cache",
        "content-type: application/json"
    )
));

$response = curl_exec($curl);
$err      = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
}

// Convert JSON string to Array
$someArray    = json_decode($response, true);
$viewTemplate = $response;
if ((stripos($response, "Forbidden") == true) or (stripos($response, "Unauthorized") == true)) {
    echo "<h2>Alert Messages</h2><div class='alert'> WARNING: BAD API KEY, PLEASE RETURN TO <a href='http://geekswithapersonality.com/cgi-bin/SparkPostKey.php'>PREVIOUS PAGE</a> AND RE-ENTER</div>";
}
?>

<h1><center> SparkPostMail Simple UI Campaign Generator </center></h1>
<table>
<tr><td>
<form action="SparkPostTransmission.php" method="get">
<input type="hidden" name="apikey" value="<?php
echo $apikey;
?>">

<h3>Select a Template (Showing Published Templates Only):*</h3>
<select name="Template" id="Template" onchange="updateCall()">
  <?php
//
// Build the dropdown Selector from the Template API call
//
foreach ($someArray as $key => $value) {
    foreach ($value as $key2 => $value2) {
        foreach ($value2 as $key3 => $value3) {
            if ($key3 == "id")
                echo '<option value="' . $value3 . '">' . $value3 . '</option>';
        }
    }
}
?>
</select>

<?php
//
// Get The Stored Recipient-Lists Templates from the Account
//
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
        "authorization: $apikey",
        "cache-control: no-cache",
        "content-type: application/json"
    )
));

$response = curl_exec($curl);
$err      = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
}

// Convert JSON string to Array
$someArrayofRecipients = json_decode($response, true);
?>

<h3>Select a Recipient List:*</h3>
<select name="Recipients" id="Recipients" onchange="updateCall()">
  <?php
//
// Build the dropdown Selector from the Template API call
//
foreach ($someArrayofRecipients as $key => $value) {
    foreach ($value as $key2 => $value2) {
        foreach ($value2 as $key3 => $value3) {
            if ($key3 == "id")
                echo '<option value="' . $value3 . '">' . $value3 . '</option>';
        }
    }
}
?>
</select>

<h3>Launch now OR enter data & time of campaign launch (YYYY-MM-DD HH:mm)*

<div class="tooltip"><a> <img height=35 width=35 src="https://dl.dropboxusercontent.com/u/4387255/info.png"> </a>
<span class="tooltiptext">Note: <br>1) Any scheduled Campaign within 24 hours from running can not be cancelled. <p> 2) Campaigns can only be scheduled less than 32 days out.</span>
</div></h3>
<input type="checkbox" name="now" id=now value="T" checked> OR Enter Date/Time 
<input type="text" id="datepicker" data-format="YYYY-MM-DD" data-template="YYYY-MM-DD" name="date" placeholder="YYYY-MM-DD">
<input type="number"  size="6" min="0" max="23" data-format="HH" data-template="HH" name="hour" value="00">
<input type="number" size="6" min="0" max="59" data-format="MM" data-template="MM" name="minutes" value="00">
<select name="tz">
	<option timeZoneId="1" gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12:00">(GMT-12:00) International Date Line West</option>
	<option timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11:00">(GMT-11:00) Midway Island, Samoa</option>
	<option timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10:00">(GMT-10:00) Hawaii</option>
	<option timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9:00">(GMT-09:00) Alaska</option>
	<option selected="selected" timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8:00">(GMT-08:00) Pacific Time (US & Canada)</option>
	<option timeZoneId="6" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8:00">(GMT-08:00) Tijuana, Baja California</option>
	<option timeZoneId="7" gmtAdjustment="GMT-07:00" useDaylightTime="0" value="-7:00">(GMT-07:00) Arizona</option>
	<option timeZoneId="8" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7:00">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
	<option timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7:00">(GMT-07:00) Mountain Time (US & Canada)</option>
	<option timeZoneId="10" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6:00">(GMT-06:00) Central America</option>
	<option timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6:00">(GMT-06:00) Central Time (US & Canada)</option>
	<option timeZoneId="12" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6:00">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
	<option timeZoneId="13" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6:00">(GMT-06:00) Saskatchewan</option>
	<option timeZoneId="14" gmtAdjustment="GMT-05:00" useDaylightTime="0" value="-5:00">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
	<option timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5:00">(GMT-05:00) Eastern Time (US & Canada)</option>
	<option timeZoneId="16" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5:00">(GMT-05:00) Indiana (East)</option>
	<option timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4:00">(GMT-04:00) Atlantic Time (Canada)</option>
	<option timeZoneId="18" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4:00">(GMT-04:00) Caracas, La Paz</option>
	<option timeZoneId="19" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4:00">(GMT-04:00) Manaus</option>
	<option timeZoneId="20" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4:00">(GMT-04:00) Santiago</option>
	<option timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3:30">(GMT-03:30) Newfoundland</option>
	<option timeZoneId="22" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3:00">(GMT-03:00) Brasilia</option>
	<option timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3:00">(GMT-03:00) Buenos Aires, Georgetown</option>
	<option timeZoneId="24" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3:00">(GMT-03:00) Greenland</option>
	<option timeZoneId="25" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3:00">(GMT-03:00) Montevideo</option>
	<option timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2:00">(GMT-02:00) Mid-Atlantic</option>
	<option timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1:00">(GMT-01:00) Cape Verde Is.</option>
	<option timeZoneId="28" gmtAdjustment="GMT-01:00" useDaylightTime="1" value="-1:00">(GMT-01:00) Azores</option>
	<option timeZoneId="29" gmtAdjustment="GMT+00:00" useDaylightTime="0" value="+0:00">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
	<option timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="+0:00">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
	<option timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="+1:00">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
	<option timeZoneId="32" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="+1:00">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
	<option timeZoneId="33" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="+1:00">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
	<option timeZoneId="34" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="+1:00">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
	<option timeZoneId="35" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="+1:00">(GMT+01:00) West Central Africa</option>
	<option timeZoneId="36" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="+2:00">(GMT+02:00) Amman</option>
	<option timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="+2:00">(GMT+02:00) Athens, Bucharest, Istanbul</option>
	<option timeZoneId="38" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="+2:00">(GMT+02:00) Beirut</option>
	<option timeZoneId="39" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="+2:00">(GMT+02:00) Cairo</option>
	<option timeZoneId="40" gmtAdjustment="GMT+02:00" useDaylightTime="0" value="+2:00">(GMT+02:00) Harare, Pretoria</option>
	<option timeZoneId="41" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="+2:00">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
	<option timeZoneId="42" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="+2:00">(GMT+02:00) Jerusalem</option>
	<option timeZoneId="43" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="+2:00">(GMT+02:00) Minsk</option>
	<option timeZoneId="44" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="+2:00">(GMT+02:00) Windhoek</option>
	<option timeZoneId="45" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="+3:00">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
	<option timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="+3:00">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
	<option timeZoneId="47" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="+3:00">(GMT+03:00) Nairobi</option>
	<option timeZoneId="48" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="+3:00">(GMT+03:00) Tbilisi</option>
	<option timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="+3:30">(GMT+03:30) Tehran</option>
	<option timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="+4:00">(GMT+04:00) Abu Dhabi, Muscat</option>
	<option timeZoneId="51" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="+4:00">(GMT+04:00) Baku</option>
	<option timeZoneId="52" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="+4:00">(GMT+04:00) Yerevan</option>
	<option timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="+4:30">(GMT+04:30) Kabul</option>
	<option timeZoneId="54" gmtAdjustment="GMT+05:00" useDaylightTime="1" value="+5:00">(GMT+05:00) Yekaterinburg</option>
	<option timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="+5:00">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
	<option timeZoneId="56" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="+5:30">(GMT+05:30) Sri Jayawardenapura</option>
	<option timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="+5:30">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
	<option timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="+5:45">(GMT+05:45) Kathmandu</option>
	<option timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="+6:00">(GMT+06:00) Almaty, Novosibirsk</option>
	<option timeZoneId="60" gmtAdjustment="GMT+06:00" useDaylightTime="0" value="+6:00">(GMT+06:00) Astana, Dhaka</option>
	<option timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="+6:30">(GMT+06:30) Yangon (Rangoon)</option>
	<option timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="+7:00">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
	<option timeZoneId="63" gmtAdjustment="GMT+07:00" useDaylightTime="1" value="+7:00">(GMT+07:00) Krasnoyarsk</option>
	<option timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="+8:00">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
	<option timeZoneId="65" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="+8:00">(GMT+08:00) Kuala Lumpur, Singapore</option>
	<option timeZoneId="66" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="+8:00">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
	<option timeZoneId="67" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="+8:00">(GMT+08:00) Perth</option>
	<option timeZoneId="68" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="+8:00">(GMT+08:00) Taipei</option>
	<option timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="+9:00">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
	<option timeZoneId="70" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="+9:00">(GMT+09:00) Seoul</option>
	<option timeZoneId="71" gmtAdjustment="GMT+09:00" useDaylightTime="1" value="+9:00">(GMT+09:00) Yakutsk</option>
	<option timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="+9:30">(GMT+09:30) Adelaide</option>
	<option timeZoneId="73" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="+9:30">(GMT+09:30) Darwin</option>
	<option timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="+10:00">(GMT+10:00) Brisbane</option>
	<option timeZoneId="75" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="+10:00">(GMT+10:00) Canberra, Melbourne, Sydney</option>
	<option timeZoneId="76" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="+10:00">(GMT+10:00) Hobart</option>
	<option timeZoneId="77" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="+10:00">(GMT+10:00) Guam, Port Moresby</option>
	<option timeZoneId="78" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="+10:00">(GMT+10:00) Vladivostok</option>
	<option timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="+11:00">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
	<option timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="+12:00">(GMT+12:00) Auckland, Wellington</option>
	<option timeZoneId="81" gmtAdjustment="GMT+12:00" useDaylightTime="0" value="+12:00">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
	<option timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="+13:00">(GMT+13:00) Nuku'alofa</option>
</select>											

<h3>Campaign Name:*</h3>

  <input name="campaign" type="text" required><br><br>
  <input type="checkbox" name="open" value="T" checked> Turn on Open Tracking<br>
  <input type="checkbox" name="click" value="T" checked> Turn on Click Tracking<br>
<h3>Optional Items (leave blank if you don't want to use them)...</h3>
<h4>Want Proof, Enter Your Email Address Here</h4>
<input type="text" name="email" value="">
<h4>Enter Meta Data: first column Is the Metadata Field Name, the second column is the data:</h4>
Metadata Field Name: <input type="textnormal" name="meta1" value=""> &nbsp;&nbsp;&nbsp;Data: <input type="textnormal" name="data1" value=""><br><br>
Metadata Field Name: <input type="textnormal" name="meta2" value=""> &nbsp;&nbsp;&nbsp;Data: <input type="textnormal" name="data2" value=""><br><br>
Metadata Field Name: <input type="textnormal" name="meta3" value=""> &nbsp;&nbsp;&nbsp;Data: <input type="textnormal" name="data3" value=""><br><br>
Metadata Field Name: <input type="textnormal" name="meta4" value=""> &nbsp;&nbsp;&nbsp;Data: <input type="textnormal" name="data4" value=""><br><br>
Metadata Field Name: <input type="textnormal" name="meta5" value=""> &nbsp;&nbsp;&nbsp;Data: <input type="textnormal" name="data5" value=""><br><br>

<br><br><br><input type="submit" value="Submit" id="submit" STYLE="color: #FFFFFF; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #72A4D2;" size="10" >
<input type="reset" value="Reset" STYLE="color: #FFFFFF; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #72A4D2;" size="10" >
</form>
</td>

<td style width="25"></td>

<?php
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
        "content-type: application/json"
    )
));

$response = curl_exec($curl);
$err      = curl_error($curl);
curl_close($curl);
//echo $response;
if ($err) {
    echo "cURL Error #:" . $err;
}
$someArray = json_decode($response, true);
?>

<td valign="top" style="padding-top: 30px;" >
<table border="8" bgcolor="#F5870A">
<tr><td><h3><center> Already Scheduled Campaigns </center></h3></td></tr>
<tr><td><table>
<tr>
<th><u>Campaign Name</u></th>
<th></th>
<th><u>Scheduled Time for Launch</u></th>
</tr>
<?php
foreach ($someArray as $key => $value) {
    foreach ($value as $key2 => $value2) {
        if ($value2['state'] == "submitted")
            echo "<tr><td><h4>" . $value2['campaign_id'] . "</h4></td><td style width='5'></td><td><h4>" . $value2['start_time'] . "</h4></td></tr>";
    }
}
?>
</table></td></tr></table></td></table>

<p>* Mandatory fields.</p>


<form action="SparkPostScheduled.php" method="get">
   <input type="hidden" name="apikey" value="<?php
echo $apikey;
?>">
   <input type="submit" name="select" value="Click On this Button to See Full Details of Current Scheduled Campaigns and/or Cancel Them." STYLE="color: #FFFFFF; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #72A4D2;" size="10" onclick="select()" />
</form>

<h3>Preview Using Selected Template and First Member of Recipient List</h3>
<br><i>**This feature is still in beta...Still working on error messaging...Large Recipient Lists may cause the Preview to malfunction</i>
<div class="main">
    <iframe id="iframe1" width="1200" height="600">
    <iframe>
</div>

</body>
</html>