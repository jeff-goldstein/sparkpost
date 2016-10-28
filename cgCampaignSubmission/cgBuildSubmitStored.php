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

File: cgBuildSubmitStored.php
Purpose: This is the ez-pezy campaign builder.  It uses stored templates and stored recipients.
The user can optionally add global substitution data if they want.

-->
<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta content="width=device-width, initial-scale=1" name="viewport">
<title>Campaign Generator for SparkPost</title>
<link href="http://bit.ly/2elb0Hw" rel="shortcut icon">
<link href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="cgCommonFormatting.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script type="text/javascript" src="cgCommonScripts.js"></script>
<script>
/* Set Calendar format; Using jQuery calendar because it works better across different browsers than default form calendar */
$( function() 
{
    $( "#datepicker" ).datepicker( { dateFormat: 'yy-mm-dd' });
} );
</script>
<style>
    /* This expands the text for more room while typing */
    input[type=previewEmailEntries] 
    {
    	width: 500px;
    	box-sizing: border-box;
    	border: 1px solid #black;
    	font-size: 12px;
    	background-color: white;
    	-webkit-transition: width 0.4s ease-in-out;
    	transition: width 0.4s ease-in-out;
    }

    input[type=previewEmailEntries]:focus 
    {
    	width: 700px;
    	border: 3px solid #555;
    }
    
    input[type=email] 
    {
    	width: 130px;
    	box-sizing: border-box;
    	border: 1px solid #black;
    	font-size: 12px;
    	background-color: white;
    	-webkit-transition: width 0.4s ease-in-out;
    	transition: width 0.4s ease-in-out;
    }

    input[type=email]:focus 
    {
    	width: 300px;
    	border: 3px solid #555;
    }

	#scrollable_table tr:last-child td
	{
    	border-bottom:0;
    }
    
</style>
   
</head>

<body id="bkgnd">
<?php
	ini_set('post_max_size', '200000');
    $hash = $_GET["apikey"];
    $apikey = hex2bin($hash);
    $apiroot = $_GET["apiroot"];
    $apiroot = trim ($apiroot); //remove any white space
	if (substr($apiroot, -1) == "/") $apiroot = substr($apiroot, 0, -1); //get rid of trailing slash
    include 'cgPHPLibraries.php';
?>

<ul class="topnav" id="generatorTopNav">
  <li><a class="active" href="cgKey.php">Home</a></li>
  <li><a class="active" href="cgBuildSubmitCSVJSON.php<?php echo '?apikey=' . $hash .'&apiroot=' . $apiroot ?>">Generate Campaign Using CSV or JSON</a></li>
  <li><a class="active" href="cgScheduled.php<?php echo '?apikey=' . $hash .'&apiroot=' . $apiroot ?>">Manage Scheduled Campaigns</a></li>
  <li><a href="cgHelp.php">Help</a></li>
  <li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
  <li><a href="mailto:email.goldstein@gmail.com?subject=cgMail">Contact</a></li>
  <li class="icon">
    <a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
  </li>
</ul>

<script type="text/javascript">

function recipientcount() 
{
    var recipientCount = document.getElementById("recipientCount");
    var recipientlist = document.getElementById("Recipients");
    var apikey = "<?php echo $apikey; ?>";
    var apiroot = "<?php echo $apiroot; ?>";

    $.ajax({
      url:'cgGetRecipientListCount.php',
      type: "POST",
      data: {"apikey" : apikey, "apiroot" : apiroot, "recipients" : recipientlist.value},
      complete: function (response) 
      {
          recipientCount.value=response.responseText;
      },
      error: function () {
          $('#output').html('0');
      }
    }); 
    return false;
}

function showhide() {
    var e = document.getElementById("substitutionTable");
    var f = document.getElementById("templateTable");
    if (e.style.display == 'none') {e.style.display = "block"} else {e.style.display = 'none'};
    if (f.style.display == 'none') {f.style.display = "block"} else {f.style.display = 'none'};
 }
 
function showGlobalSubField() {
    var d = document.getElementById("globalButton");
    var e = document.getElementById("globalsub");
    var f = document.getElementById("globaltext");
    if (e.style.display == 'none') {e.style.display = "block"} else {e.style.display = 'none'};
    if (f.style.display == 'none') {f.style.display = "block"} else {f.style.display = 'none'};
    if (d.value == 'Show Global Sub') {d.value = "Hide Global Sub"} else {d.value = 'Show Global Sub'};
 }

function generatePreview()
{
	var selectList = document.getElementById("Template");
    var selectList2 = document.getElementById("Recipients");
    var globalsub = document.getElementById("globalsub").value;
    var apikey = "<?php echo $apikey; ?>";
    var apiroot = "<?php echo $apiroot; ?>";

    $.ajax({
      url:'cgBuildPreview.php',
      type: "POST",
      data: {"apikey" : apikey, "apiroot" : apiroot, "template" : selectList.value, "recipients" : selectList2.value, "globalsub" : globalsub},
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

function match()
{
	var selectList = document.getElementById("Template");
    var selectList2 = document.getElementById("Recipients");
    var globalsub = document.getElementById("globalsub").value;
    var apikey = "<?php echo $apikey; ?>";
    var apiroot = "<?php echo $apiroot; ?>";
    
    $.ajax({
      url:'cgTemplateSubdataComp.php',
      type: "POST",
      data: {"apikey" : apikey, "apiroot" : apiroot, "template" : selectList.value, "recipients" : selectList2.value, "type" : "substitution", "globalsub" : globalsub},
      complete: function (response) 
      {
          $('#substitution').contents().find('html').html(response.responseText);
          $.ajax({
      		url:'cgTemplateSubdataComp.php',
      		type: "POST",
      		data: {"apikey" : apikey, "apiroot" : apiroot, "template" : selectList.value, "recipients" : selectList2.value, "type" : "template", "globalsub" : globalsub},
      		complete: function (response) 
      		{
          		$('#template').contents().find('html').html(response.responseText);
      		},
      		error: function () {
          		$('#output').html('Bummer: there was an error!');
      		}
    		});
      },
      error: function () {
          $('#output').html('Bummer: there was an error!');
      }
    });
    
    return false;
}

function sendTestEmail()
{
	var templateList = document.getElementById("Template");
    var recipientList = document.getElementById("Recipients");
    var emailaddresses = document.getElementById("previewTestEmails").value;
    var campaign = document.getElementById("campaign").value;
    var open = document.getElementById("open").value;
    var click = document.getElementById("click").value;
    var meta1 = document.getElementById("meta1").value;
    var data1 = document.getElementById("data1").value;
    var meta2 = document.getElementById("meta2").value;
    var data2 = document.getElementById("data2").value;
    var meta3 = document.getElementById("meta3").value;
    var data3 = document.getElementById("data3").value;
    var meta4 = document.getElementById("meta4").value;
    var data4 = document.getElementById("data4").value;
    var meta5 = document.getElementById("meta5").value;
    var data5 = document.getElementById("data5").value;
    var apikey = "<?php echo $apikey; ?>";
    var apiroot = "<?php echo $apiroot; ?>";
    
    $.ajax({
      url:'cgSendTestEmail.php',
      type: "POST",
      data: {"apikey" : apikey, "template" : templateList.value, "recipients" : recipientList.value, "emailaddresses" : emailaddresses, 
      		 "apiroot" : apiroot, "campaign" : campaign, "open" : open, "click" : click, 
      		 "meta1" :  meta1, "data1" : data1,   "meta2" :  meta2, "data2" : data2, "meta3" :  meta3, "data3" : data3, "meta4" :  meta4, "data4" : data4, "meta5" :  meta5, "data5" : data5 },
      complete: function (response) 
      {
          // This is for error checking  in order to see echo'ed items...
          //$('#iframe1').contents().find('html').html(response.responseText);
      },
      error: function () 
      {
          $('#iframe1').contents().find('html').html(response.responseText);
      }
    });
    
    return false;
}

function resetpreview()
{
	$('#iframe1').contents().find('html').html("<p>Please select your Template and Recipient List</p>");
	xbutton = document.getElementById("submit");
	xbutton.disabled = false;
    xbutton.value = "Submit";
    xbutton.style.color = "white";
    xbutton.style.backgroundColor = "#72A4D2";
}

function resetsummary()
{
	$('#template').contents().find('html').html("<p>Please select your Template and Recipient List</p>");
	$('#substitution').contents().find('html').html("<p>Please select your Template and Recipient List</p>");
}

</script> 
    <?php
    //
    // Check the APIKey by calling one of the REST API's.  If I call this from the cgPHPLibraries.php the
    // Server returns and Unauthorized.  Will look into that later.
    //
        $curl = curl_init();
        $url = $apiroot . "/recipient-lists/";
    	curl_setopt_array($curl, array(
    	CURLOPT_URL => $url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => "",
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 30,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => "GET",
    	CURLOPT_HTTPHEADER => array("authorization: $apikey","cache-control: no-cache","content-type: application/json")
    	));

    	$response = curl_exec($curl);
    	$err      = curl_error($curl);
    	curl_close($curl);

    	if ($err) 
    	{
        	echo "cURL Error #:" . $err;
    	}
    	if ((stripos($response, "Forbidden") == true) or (stripos($response, "Unauthorized") == true)) 
    	{
        	echo "<h2>Alert Messages</h2><div class='alert'> WARNING: BAD API KEY, PLEASE RETURN TO <a href='cgKey.php'>PREVIOUS PAGE</a> AND RE-ENTER</div>";
    	}
    ?>
    <br>
    <table width="1300" cellpadding="20" height=900>
    <tr width="1300"><center><h1>Campaign Generator</h1></center></tr>
            <td><table border=1 bgcolor="#FFFFFF" width="850" height="900">
            <td style="padding-left: 8px; padding-right: 8px;">
                <form action="cgConfirmSubmission.php" method="POST" height="900">
                    <input name="apikey" type="hidden" value="<?php echo $hash; ?>"/>
                    <input name="apiroot" type="hidden" value="<?php echo $apiroot; ?>"/>
                    <input id="recipientCount" name="recipientCount" type="hidden" value=""/>
                    <h3>Select a Template (Showing Published Templates Only):*</h3><select id="Template" name="Template">
                    <?php
                        buildTemplateList ($apikey, $apiroot);
                    ?>
                    </select> 
                    <h3>Select a Recipient List or Manually Enter Data:*</h3>
                    <select id="Recipients" name="Recipients" onchange="recipientcount()">
                    <?php
                    	global $apikey, $apiroot;
                        buildRecipientList ($apikey, $apiroot);
                    ?>
                    </select>
                    <br><br>
                    <input type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="generatePreview(), match(), recipientcount()" value="Preview & Validate">
					&nbsp;&nbsp;
					<input type="button" id="globalButton" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="showGlobalSubField()" value="Show Global Sub">
					&nbsp;&nbsp;
					<input type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="generatePreview(), match(), sendTestEmail()" value="Send Test Email">
					<br><br>
                    <input id="previewTestEmails" name="previewTestEmails" type="previewEmailEntries" placeholder="Comma Separated Email Addresses to Use for Test Emails">
                    <input id="globaltext" name="globaltext" readonly type="textnormal" style="display:none; border:none; width: 725px;" value="Input Global Substitution Data in JSON Format up to 70k of data">
    				<textarea id="globalsub" name="globalsub" class="text" maxlength="70000" cols="120" style="display:none;"placeholder=
    			'"substitution_data": {
"subject" : "More Wonderful Items Picked for You",
"link_format": "style= \"font-family: arial, helvetica, sans-serif; color: rgb(85,85, 90); font-size: 12px; text-decoration: none;\"",
"dynamic_html": {
	"member_level" : "<strong>GOLD</strong>",
	"job1" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"https://www.messagesystems.com/careers\">Inside Sales Representative, San Francisco, CA</a>",
	"job2" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"https://www.messagesystems.com/careers\">Sales Development Representative, San Francisco, CA</a>",
	"job3" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"https://www.messagesystems.com/careers\">Social Media Marketing, San Francisco, CA</a>",
	"job4" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"http://page.messagesystems.com/careers\">Platform Developer, Columbia, MD</a>",
	"job5" : "<a data-msys-linkname=\"jobs\" {{{link_format}}} href=\"http://page.messagesystems.com/careers\">Rain Catcher & Beer Drinker, Seattle, WA</a>"
},
"default_jobs": ["job1", "job3"],
"backgroundColor" : "#ffffff",
"company_home_url" : "www.sparkpost.com",
"company_logo" : "https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTVYSp0xUPD8yNMYOyTS20VZBwbzt4J-pjta3FtjcT_0rM-cj2o"
}'></textarea>
                    <h3>Launch now OR enter data & time of campaign launch (YYYY-MM-DD HH:mm)*
                    <div class="tooltip"><a><img height="35" src="https://dl.dropboxusercontent.com/u/4387255/info.png" width="35"></a> 
                    <span class="tooltiptext">Note:<br>1) Campaigns scheduled within 10 minutes of running cannot be cancelled.<p>2) Campaigns can only be scheduled less than 32 days out.</span></div></h3>
                    <input checked id="now" name="now" type="checkbox" value="T"> OR
                    Enter Date/Time <input data-format="YYYY-MM-DD" data-template="YYYY-MM-DD" id="datepicker" name="date" placeholder="YYYY-MM-DD" type="text">
                    <input data-format="HH" data-template="HH" max="23" min="0" name="hour" size="6" type="number" value="00"> <input data-format="MM" data-template="MM" max="59"
                    min="0" name="minutes" size="6" type="number" value="00"> 
                    <?php
                    	$tzSelect = buildTimeZoneList ();
                    	echo $tzSelect;
                    ?>
                    <h3>Campaign Name:*</h3><input id="campaign" name="campaign" required="" type="text" placeholder="Please Enter a Campaign Name"><br>
                    <br>
                    <input checked id="open" name="open" type="checkbox" value="T"> Turn on Open Tracking<br>
                    <input checked id="click" name="click" type="checkbox" value="T"> Turn on Click Tracking<br>
                    <h3>Optional Items (leave blank if you don't want to use them)...</h3>
                    <h4>Want Proof, Enter Your Email Address Here</h4><input name="email" type="email" placeholder="Email Address">
                    <h4>Enter Meta Data: first column Is the Metadata Field Name, the second column is the data:</h4>
                    Metadata Field Name: <input id="meta1" name="meta1" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input id="data1" name="data1" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input id="meta2" name="meta2" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input id="data2" name="data2" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input id="meta3" name="meta3" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input id="data3" name="data3" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input id="meta4" name="meta4" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input id="data4" name="data4" type="textnormal" value=""><br>
                    <br>
                    Metadata Field Name: <input id="meta5" name="meta5" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input id="data5" name="data5" type="textnormal" value=""><br>
                    <br>
                    <br>
                    <input id="submit" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" type="submit" value="Submit"> 
                    <input size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" type="reset" value="Reset" onclick="resetpreview(), resetsummary()"><p><p>
                </form></td></table></td>
                <td style="" width="5"></td>
                <?php
                    $curl = curl_init();
                    $url = $apiroot . "/transmissions/";
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
                    <table id="scrollable_table" border="1" cellpadding="10" bgcolor="FFFFFF" width="450"  height="480">
                        <tr>
                            <td>
                                <center>
                                    <h3>Already Scheduled Campaigns</h3>
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="430">
                                    <tr>
                                        <th><u>Campaign Name</u></th>
                                        <th></th>
                                        <th><u>Scheduled Time for Launch</u></th>
                                    </tr>
                                    <?php
                                    foreach ($someArray as $key => $value) 
                                    {
                                        foreach ($value as $key2 => $value2) 
                                        {
                                            if ($value2['state'] == "submitted")
                                            {
                                                echo "<tr><td width=215><h4>" . $value2['campaign_id'] . "</h4></td><td width='5'></td><td width=210><h4>" . $value2['start_time'] . "</h4></td></tr>";
                                            }
                                        }
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <br><br>
                    <button id="toggle" onclick="showhide()">Substitution or Template Validation</button>
                    <table id="substitutionTable" border="0" bgcolor="FFFFFF" width="450"  height="390" style="display:block">
                        <tr>
                        	<td>
                                <div class="main">
                                <iframe id="substitution" height="390" width="450" style="background: #FFFFFF;" cellpadding="10" srcdoc="<p>Please select your Template and Recipient List</p>"></iframe></div>
                            </td>
                        </tr>
                    </table>
                    <table id="templateTable" border="0" bgcolor="FFFFFF" width="450"  height="390" style="display:none">
                        <tr>
                            <td>
                                <div class="main">
                                <iframe id="template" height="390" width="450" style="background: #FFFFFF;" cellpadding="10" srcdoc="<p>Please select your Template and Recipient List</p>"></iframe></div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* Mandatory fields
    <table cellpadding="25" border=0><tr><td>
    <h3>Preview Using Selected Template and First Member of Recipient List</h3><br>
    <i>**This feature is still in beta...Still working on error messaging...Large Recipient Lists may cause the Preview to malfunction</i>
    <div class="main">
        <iframe height="600" id="iframe1" name="iframe1" width="1300" style="background: #FFFFFF;" srcdoc="<p>Please select your Template and Recipient List</p>"></iframe>
    </div></td></tr></table>
</body>
</html>

