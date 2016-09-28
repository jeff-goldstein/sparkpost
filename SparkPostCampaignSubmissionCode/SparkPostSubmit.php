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
<html>
<head>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js">
    </script>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>SparkPost Campaign Submittion</title>
    <link href="https://dl.dropboxusercontent.com/u/4387255/Tools.ico" rel="shortcut icon">
    <link href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.12.4.js">
    </script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js">
    </script>
    <script src="ckeditor/ckeditor.js">
    </script>
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
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif
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
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif
    }

    h4 { 
    display: block;
    margin-top: 1.33em;
    margin-bottom: 1.33em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
    color: #298272;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif
    }
    
    select {
    padding: 16px 20px;
    border: 1px;
    border-color: #298272;
    background-color: #ffffff;
    font-size: 12px;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif
    }

    /* Can use this when I don't want an expanding input field */
    input[type=textnormal] {
    box-sizing: border-box;
    border: 1px solid #ccc;
    font-size: 12px;
    background-color: white;
    padding: 2px 2px 2px 4px;
    width : 200px;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif
    }

    /* This expands the text for more room while typing */
    input[type=text] {
    width: 130px;
    box-sizing: border-box;
    border: 1px solid #black;
    font-size: 12px;
    background-color: white;
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
    border: 1px solid #black;
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
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif
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
</head>
<body id="bkgnd">
    <?php
    $hash = $_GET["apikey"];
    $apikey = hex2bin($hash);
    ?>
<ul class="topnav" id="myTopnav">
  <li><a class="active" href="SparkPostKey.php">Home</a></li>
  <li><a class="active" href="SparkPostScheduled.php<?php echo '?apikey=' . $hash ?>">Scheduled Campaigns</a></li>
  <li><a href="SparkPostHelp.php">Help</a></li>
  <li><a href="mailto:email.goldstein@gmail.com?subject=SparkPostMail">Contact</a></li>
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
    <script type="text/javascript">

    function updateCall()
    {
    var selectList = document.getElementById("Template");
    //var divAnswer  = document.getElementById("editor1");
    var selectList2 = document.getElementById("Recipients");
    var apikey = "<?php echo $apikey; ?>";

    $.ajax({
      url:'getPreview.php',
      data: {"apikey" : apikey, "template" : selectList.value, "recipients" : selectList2.value},
      complete: function (response) 
      {
          $('#iframe1').contents().find('html').html(response.responseText);
          xbutton = document.getElementById("submit");
          var strCheck1 = "attempt to call non-existent macro";
          var strCheck2 = "crash";
          var location1 = response.responseText.search(strCheck1);
          var location2 = response.responseText.search(strCheck2);
          if (location1 > 0  && location2 > 0)
          {
              xbutton.disabled = true;
              xbutton.value = "Submit";
              xbutton.style.backgroundColor = "red";
              xbutton.style.color = "black";
              alert("Warning!! Your data protection check was triggered, bad Recipient List selected - Submit Turned off!");
          }
          else
          {  
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
          }
      },
      error: function () {
          $('#output').html('Bummer: there was an error!');
      }
    });
    return false;
    }

    </script> <?php
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
    <table width="1300">
    <tr width="1300"><center><h1>SparkPostMail Campaign Generator</h1></center></tr>
            <td><table border=1 bgcolor="#FFFFFF">
            <td style="padding-left: 8px; padding-right: 8px;">
                <form action="SparkPostTransmission.php" method="get">
                    <input name="apikey" type="hidden" value="<?php echo $hash; ?>">
                    <h3>Select a Template (Showing Published Templates Only):*</h3><select id="Template" name="Template" onchange="updateCall()">
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
                    </select> <?php
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
                    <h3>Select a Recipient List:*</h3><select id="Recipients" name="Recipients" onchange="updateCall()">
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
                    <div class="tooltip"><a><img height="35" src="https://dl.dropboxusercontent.com/u/4387255/info.png" width="35"></a> 
                    <span class="tooltiptext">Note:<br>1) Any scheduled Campaign within 24 hours from running can not be cancelled.<p>2) Campaigns can only be scheduled less than 32 days out.</span></div></h3>
                    <input checked id="now" name="now" type="checkbox" value="T"> OR
                    Enter Date/Time <input data-format="YYYY-MM-DD" data-template="YYYY-MM-DD" id="datepicker" name="date" placeholder="YYYY-MM-DD" type="text">
                    <input data-format="HH" data-template="HH" max="23" min="0" name="hour" size="6" type="number" value="00"> <input data-format="MM" data-template="MM" max="59"
                    min="0" name="minutes" size="6" type="number" value="00"> <select name="tz">
                        <option value="-12:00">
                            (GMT-12:00) International Date Line West
                        </option>
                        <option value="-11:00">
                            (GMT-11:00) Midway Island, Samoa
                        </option>
                        <option value="-10:00">
                            (GMT-10:00) Hawaii
                        </option>
                        <option value="-9:00">
                            (GMT-09:00) Alaska
                        </option>
                        <option selected="selected" value="-8:00">
                            (GMT-08:00) Pacific Time (US & Canada)
                        </option>
                        <option value="-8:00">
                            (GMT-08:00) Tijuana, Baja California
                        </option>
                        <option value="-7:00">
                            (GMT-07:00) Arizona
                        </option>
                        <option value="-7:00">
                            (GMT-07:00) Chihuahua, La Paz, Mazatlan
                        </option>
                        <option value="-7:00">
                            (GMT-07:00) Mountain Time (US & Canada)
                        </option>
                        <option value="-6:00">
                            (GMT-06:00) Central America
                        </option>
                        <option value="-6:00">
                            (GMT-06:00) Central Time (US & Canada)
                        </option>
                        <option value="-6:00">
                            (GMT-06:00) Guadalajara, Mexico City, Monterrey
                        </option>
                        <option value="-6:00">
                            (GMT-06:00) Saskatchewan
                        </option>
                        <option value="-5:00">
                            (GMT-05:00) Bogota, Lima, Quito, Rio Branco
                        </option>
                        <option value="-5:00">
                            (GMT-05:00) Eastern Time (US & Canada)
                        </option>
                        <option value="-5:00">
                            (GMT-05:00) Indiana (East)
                        </option>
                        <option value="-4:00">
                            (GMT-04:00) Atlantic Time (Canada)
                        </option>
                        <option value="-4:00">
                            (GMT-04:00) Caracas, La Paz
                        </option>
                        <option value="-4:00">
                            (GMT-04:00) Manaus
                        </option>
                        <option value="-4:00">
                            (GMT-04:00) Santiago
                        </option>
                        <option value="-3:30">
                            (GMT-03:30) Newfoundland
                        </option>
                        <option value="-3:00">
                            (GMT-03:00) Brasilia
                        </option>
                        <option value="-3:00">
                            (GMT-03:00) Buenos Aires, Georgetown
                        </option>
                        <option value="-3:00">
                            (GMT-03:00) Greenland
                        </option>
                        <option value="-3:00">
                            (GMT-03:00) Montevideo
                        </option>
                        <option value="-2:00">
                            (GMT-02:00) Mid-Atlantic
                        </option>
                        <option value="-1:00">
                            (GMT-01:00) Cape Verde Is.
                        </option>
                        <option value="-1:00">
                            (GMT-01:00) Azores
                        </option>
                        <option value="+0:00">
                            (GMT+00:00) Casablanca, Monrovia, Reykjavik
                        </option>
                        <option value="+0:00">
                            (GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London
                        </option>
                        <option value="+1:00">
                            (GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna
                        </option>
                        <option value="+1:00">
                            (GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague
                        </option>
                        <option value="+1:00">
                            (GMT+01:00) Brussels, Copenhagen, Madrid, Paris
                        </option>
                        <option value="+1:00">
                            (GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb
                        </option>
                        <option value="+1:00">
                            (GMT+01:00) West Central Africa
                        </option>
                        <option value="+2:00">
                            (GMT+02:00) Amman
                        </option>
                        <option value="+2:00">
                            (GMT+02:00) Athens, Bucharest, Istanbul
                        </option>
                        <option value="+2:00">
                            (GMT+02:00) Beirut
                        </option>
                        <option value="+2:00">
                            (GMT+02:00) Cairo
                        </option>
                        <option value="+2:00">
                            (GMT+02:00) Harare, Pretoria
                        </option>
                        <option value="+2:00">
                            (GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius
                        </option>
                        <option value="+2:00">
                            (GMT+02:00) Jerusalem
                        </option>
                        <option value="+2:00">
                            (GMT+02:00) Minsk
                        </option>
                        <option value="+2:00">
                            (GMT+02:00) Windhoek
                        </option>
                        <option value="+3:00">
                            (GMT+03:00) Kuwait, Riyadh, Baghdad
                        </option>
                        <option value="+3:00">
                            (GMT+03:00) Moscow, St. Petersburg, Volgograd
                        </option>
                        <option value="+3:00">
                            (GMT+03:00) Nairobi
                        </option>
                        <option value="+3:00">
                            (GMT+03:00) Tbilisi
                        </option>
                        <option value="+3:30">
                            (GMT+03:30) Tehran
                        </option>
                        <option value="+4:00">
                            (GMT+04:00) Abu Dhabi, Muscat
                        </option>
                        <option value="+4:00">
                            (GMT+04:00) Baku
                        </option>
                        <option value="+4:00">
                            (GMT+04:00) Yerevan
                        </option>
                        <option value="+4:30">
                            (GMT+04:30) Kabul
                        </option>
                        <option value="+5:00">
                            (GMT+05:00) Yekaterinburg
                        </option>
                        <option value="+5:00">
                            (GMT+05:00) Islamabad, Karachi, Tashkent
                        </option>
                        <option value="+5:30">
                            (GMT+05:30) Sri Jayawardenapura
                        </option>
                        <option value="+5:30">
                            (GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi
                        </option>
                        <option value="+5:45">
                            (GMT+05:45) Kathmandu
                        </option>
                        <option value="+6:00">
                            (GMT+06:00) Almaty, Novosibirsk
                        </option>
                        <option value="+6:00">
                            (GMT+06:00) Astana, Dhaka
                        </option>
                        <option value="+6:30">
                            (GMT+06:30) Yangon (Rangoon)
                        </option>
                        <option value="+7:00">
                            (GMT+07:00) Bangkok, Hanoi, Jakarta
                        </option>
                        <option value="+7:00">
                            (GMT+07:00) Krasnoyarsk
                        </option>
                        <option value="+8:00">
                            (GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi
                        </option>
                        <option value="+8:00">
                            (GMT+08:00) Kuala Lumpur, Singapore
                        </option>
                        <option value="+8:00">
                            (GMT+08:00) Irkutsk, Ulaan Bataar
                        </option>
                        <option value="+8:00">
                            (GMT+08:00) Perth
                        </option>
                        <option value="+8:00">
                            (GMT+08:00) Taipei
                        </option>
                        <option value="+9:00">
                            (GMT+09:00) Osaka, Sapporo, Tokyo
                        </option>
                        <option value="+9:00">
                            (GMT+09:00) Seoul
                        </option>
                        <option value="+9:00">
                            (GMT+09:00) Yakutsk
                        </option>
                        <option value="+9:30">
                            (GMT+09:30) Adelaide
                        </option>
                        <option value="+9:30">
                            (GMT+09:30) Darwin
                        </option>
                        <option value="+10:00">
                            (GMT+10:00) Brisbane
                        </option>
                        <option value="+10:00">
                            (GMT+10:00) Canberra, Melbourne, Sydney
                        </option>
                        <option value="+10:00">
                            (GMT+10:00) Hobart
                        </option>
                        <option value="+10:00">
                            (GMT+10:00) Guam, Port Moresby
                        </option>
                        <option value="+10:00">
                            (GMT+10:00) Vladivostok
                        </option>
                        <option value="+11:00">
                            (GMT+11:00) Magadan, Solomon Is., New Caledonia
                        </option>
                        <option value="+12:00">
                            (GMT+12:00) Auckland, Wellington
                        </option>
                        <option value="+12:00">
                            (GMT+12:00) Fiji, Kamchatka, Marshall Is.
                        </option>
                        <option value="+13:00">
                            (GMT+13:00) Nuku'alofa
                        </option>
                    </select></p>
                    <h3>Campaign Name:*</h3><input name="campaign" required="" type="text"><br>
                    <br>
                    <input checked name="open" type="checkbox" value="T"> Turn on Open Tracking<br>
                    <input checked name="click" type="checkbox" value="T"> Turn on Click Tracking<br>
                    <h3>Optional Items (leave blank if you don't want to use them)...</h3>
                    <h4>Want Proof, Enter Your Email Address Here</h4><input name="email" type="text" value="">
                    <h4>Enter Meta Data: first column Is the Metadata Field Name, the second column is the data:</h4>Metadata Field Name: <input name="meta1" type="textnormal"
                    value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data1" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input name="meta2" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data2" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input name="meta3" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data3" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input name="meta4" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data4" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input name="meta5" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data5" type="textnormal" value=""><br>
                    <br>
                    <br>
                    <input id="submit" size="10" style="color: #FFFFFF; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #72A4D2;" type="submit" value=
                    "Submit"> <input size="10" style="color: #FFFFFF; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #72A4D2;" type="reset" value=
                    "Reset">
                </form></td><tfoot>* Mandatory fields.</tfoot></table></td>
                <td style="" width="25"></td>
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
                        <td  valign="top" style="padding-top: 20px;">
                            <table bgcolor="#F5870A" border="1">
                                <tr>
                                    <td>
                                        <center>
                                            <h3>Already Scheduled Campaigns</h3>
                                        </center>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <th><u>Campaign Name</u></th>
                                                <th></th>
                                                <th><u>Scheduled Time for Launch</u></th>
                                            </tr><?php
                                            foreach ($someArray as $key => $value) {
                                                foreach ($value as $key2 => $value2) {
                                                    if ($value2['state'] == "submitted")
                                                    {
                                                        echo "<tr><td><h4>" . $value2['campaign_id'] . "</h4></td><td style width='5'></td><td><h4>" . $value2['start_time'] . "</h4></td></tr>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </table>
                                        <center><form action="SparkPostScheduled.php" method="get">
        									<input name="apikey" type="hidden" value="<?php echo $hash; ?>">
        									<input name="select" onclick="select()" size="10" style=
        										   "color: #000000; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #F5870A;" type="submit" value=
        											"Click to See Full Details and/or Cancel Campaigns">
    								   </form></center>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <h3>Preview Using Selected Template and First Member of Recipient List</h3><br>
    <i>**This feature is still in beta...Still working on error messaging...Large Recipient Lists may cause the Preview to malfunction</i>
    <div class="main">
        <iframe height="600" id="iframe1" name="iframe1" width="1300" style="background: #FFFFFF;"><iframe></iframe></iframe>
    </div>
</body>
</html>
