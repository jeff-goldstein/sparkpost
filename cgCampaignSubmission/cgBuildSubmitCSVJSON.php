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
//
File: cgBuildSubmitCSVJSON.php 
Launch a campaign with entered data
//
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

<script>
//$('.cgform').on('submit', function () {
//    var e = document.getElementById("csv");
//    e.value='' ;
//});
</script>

<style>
#scrollable_table tr:last-child td
	{
    	border-bottom:0;
    }
</style>
   
</head>

<body id="bkgnd" onload="cleanup()">
<?php
	ini_set('post_max_size', '2000000');
    $hash = $_GET["apikey"];
    $apikey = hex2bin($hash);
    include 'cgPHPLibraries.php';
    $apiroot = $_GET["apiroot"];
?>

<ul class="topnav" id="generatorTopNav">
  <li><a class="active" href="cgKey.php">Home</a></li>
  <li><a class="active" href="cgBuildSubmitStored.php<?php echo '?apikey=' . $hash .'&apiroot=' . $apiroot ?>">Generate Using Stored Resources</a></li>
  <li><a class="active" href="cgScheduled.php<?php echo '?apikey=' . $hash .'&apiroot=' . $apiroot ?>">Manage Scheduled Campaigns</a></li>
  <li><a href="cgHelp.php">Help</a></li>
  <li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
  <li><a href="mailto:email.goldstein@gmail.com?subject=cgMail">Contact</a></li>
  <li class="icon">
    <a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
  </li>
</ul>

<script type="text/javascript">

//
// Clear Useless data before sending for transmission
//
function prepsubmit() 
{
    var csv = document.getElementById("csv");
    csv.value='' ;
    //var domain = document.getElementById("domain");
    //var selecteddomain = domain.options[domain.selectedIndex].value;
    //var returnpath = document.getElementById("returnpath");
    //returnpath.value = returnpath.value.concat('@');
    //returnpath.value = returnpath.value.concat(selecteddomain);
 }
 
function cleanup() 
{
// Need to clean up this field in case they did a backpage in the browser
// 
    var returnpath = document.getElementById("returnpath");
    var location = returnpath.value.search("@");
	if (location > 0) {returnpath.value = returnpath.value.substring(0, location)};
 }
 
function countaddresses() 
{
    var recipientCount = document.getElementById("recipientCount");
    var json = document.getElementById("json");
    var doubleqoute = (json.value.match(/"address"/g) || []).length;
    var singlequote = (json.value.match(/'address'/g) || []).length;
	recipientCount.value = singlequote + doubleqoute;
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
	var template = document.getElementById("Template");
    var recipient = "cgJson";
    var convertedJson = document.getElementById("json").value;
    var globalsub = document.getElementById("globalsub").value;
    var apiroot = "<?php echo $apiroot; ?>";
    var apikey = "<?php echo $apikey; ?>";

    $.ajax({
      url:'cgBuildPreview.php',
      type: "POST",
      data: {"apikey" : apikey, "template" : template.value, "recipients" : recipient, "entered" : convertedJson, "globalsub" : globalsub , "apiroot" : apiroot},
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
	var template = document.getElementById("Template");
    var recipient = "cgJson";
    var convertedJson = document.getElementById("json").value;
    var globalsub = document.getElementById("globalsub").value;
    var apiroot = "<?php echo $apiroot; ?>";
    var apikey = "<?php echo $apikey; ?>";
    
    $.ajax({
      url:'cgTemplateSubdataComp.php',
      type: "POST",
      data: {"apikey" : apikey, "template" : template.value, "recipients" : recipient, "type" : "substitution", "entered" : convertedJson, "globalsub" : globalsub, "apiroot" : apiroot},
      complete: function (response) 
      {
          $('#substitution').contents().find('html').html(response.responseText);
          $.ajax({
      		url:'cgTemplateSubdataComp.php',
      		type: "POST",
      		data: {"apikey" : apikey, "template" : template.value, "recipients" : recipient, "type" : "template", "entered" : convertedJson, "globalsub" : globalsub , "apiroot" : apiroot},
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

function CSVToArray(strData, strDelimiter) {
    // Check to see if the delimiter is defined. If not,
    // then default to comma.
    strDelimiter = (strDelimiter || ",");
    var count = 0;
    // Create a regular expression to parse the CSV values.
    var objPattern = new RegExp((
    // Delimiters.
    "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +
    // Quoted fields.
    "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +
    // Standard fields.
    "([^\"\\" + strDelimiter + "\\r\\n]*))"), "gi");
    // Create an array to hold our data. Give the array
    // a default empty first row.
    var arrData = [[]];
    // Create an array to hold our individual pattern
    // matching groups.
    var arrMatches = null;
    // Keep looping over the regular expression matches
    // until we can no longer find a match.
    while (arrMatches = objPattern.exec(strData)) {
        // Get the delimiter that was found.
        var strMatchedDelimiter = arrMatches[1];
        // Check to see if the given delimiter has a length
        // (is not the start of string) and if it matches
        // field delimiter. If id does not, then we know
        // that this delimiter is a row delimiter.
        // ** Finding end of line **
        if (strMatchedDelimiter.length && (strMatchedDelimiter != strDelimiter)) {
            // Since we have reached a new row of data,
            // add an empty row to our data array.
            arrData.push([]);
            count++;
        }
        // Now that we have our delimiter out of the way,
        // let's check to see which kind of value we
        // captured (quoted or unquoted).
        if (arrMatches[2]) {
            // We found a quoted value. When we capture
            // this value, unescape any double quotes.
            var strMatchedValue = arrMatches[2].replace(
            new RegExp("\"\"", "g"), "\"");
        } else {
            // We found a non-quoted value.
            var strMatchedValue = arrMatches[3];
        }
        // Now that we have our value string, let's add
        // it to the data array.
        arrData[arrData.length - 1].push(strMatchedValue);
    }
	var countElement = document.getElementById("recipientMessageText");
	var recipientCount = document.getElementById("recipientCount");
	recipientCount.value = count;
    countElement.style.display = "block";
    outText = "Number of Recipients Converted: ";
    outText = outText.concat(count);
    outText = outText.concat("  ...Is this the expected amount? CSV Paste can clip input.");
    countElement.value = outText;
    
    // Return the parsed data.
    return (arrData);
}

function isJson(str) 
{

    if( typeof( str ) !== 'string' ) { 
        return false;
    }
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}

function CSV2JSON(csv, jsonLimit, csvLimit) 
{
    var array = CSVToArray(csv);
    var objArray = [];
    for (var i = 1; i < array.length; i++) 
    {
        objArray[i - 1] = {};
        for (var k = 0; k < array[0].length && k < array[i].length; k++) 
        {
            var key = array[0][k];
            objArray[i - 1][key] = array[i][k]
        }
    }

    var json = JSON.stringify(objArray);
    var str = json.replace(/},/g, "},\r\n");
    var strBefore = '{"recipients":';
    var strAfter = "}";
    var strSub = "";
    var n = str.indexOf("substitution");
    if (n != -1) 
    {
	   var strWhole = str.replace(/,\"substitution\":\"_\",/g,",\"substitution_data\":{");
	   strWhole = strWhole.replace(/}/g,"}}");
	   strWhole = strBefore.concat(strWhole, strAfter);
	}
	else
	{
		var strWhole = strBefore.concat(str, strAfter);
	}
	var goodJson = isJson(strWhole);
	var countElement = document.getElementById("recipientMessageText");
	if (goodJson == false) 
	{
		outText = "Error, csv didnt convert properly!";
		csvLength = csv.length;
		if (csvLimit == csvLength)
		{
			outText = outText.concat("Check end of csv input. System cut off input at ");
			outText = outText.concat(csvLength);
			outText = outText.concat(" bytes.");
		}
		else
		{
			outText = outText.concat("  Check format of CSV. ");
		}	
		countElement.value = outText;
	}
	else
	{
		var jsonLength = strWhole.length;
		if (jsonLength >= jsonLimit)
		{
			outText = outText.concat("CSV converted to: ");
			outText = outText.concat(strLength);
			outText = outText.concat(" bytes of 675k");
			outText = outText.concat("  To many csv entries..will fail on Submit ");
			countElement.value = outText;
		}
	}
	
    return strWhole;
}

function convert() 
{
	jsonLimit = 675000;
	csvLimit = 200000;
    var csv = $("#csv").val();
    csv = csv.trim();
    if (csv.length > 30) //need something otherwise CSV2JSON goes into a tizzy
    {
    	var json = CSV2JSON(csv, jsonLimit, csvLimit);
    	$("#json").val(json);
    }
};

function download() 
{
    var csv = $("#csv").val();
    var json = CSV2JSON(csv);
    window.open("data:text/json;charset=utf-8," + escape(json))
};

</script> 
<?php
//
// Check the APIKey by calling one of the REST API's
//
    $templatetArray = gettemplateListFromServer($key, $apiroot);
    $viewTemplate = $response;
    if ((stripos($response, "Forbidden") == true) or (stripos($response, "Unauthorized") == true)) 
    {
        echo "<h2>Alert Messages</h2><div class='alert'> WARNING: BAD API KEY, PLEASE RETURN TO <a href='cgKey.php'>PREVIOUS PAGE</a> AND RE-ENTER</div>";
    }
?>
<br>
<table width="1300" cellpadding="20" height=900>
<tr width="1300">
	<center><h1>Campaign Generator</h1></center>
</tr>
<td>
	<table border=1 bgcolor="#FFFFFF" width="850" height="900">
        <td style="padding-left: 8px; padding-right: 8px;">
            <form class="cgform" action="cgConfirmSubmission.php" onsubmit="prepsubmit(), countaddresses()" method="POST" height="900">
                <input name="apikey" type="hidden" value="<?php echo $hash; ?>">
                <input name="apiroot" type="hidden" value="<?php echo $apiroot; ?>">
                <input id="recipientCount" name="recipientCount" type="hidden" value="">
                <h3>Select a Template (Showing Published Templates Only):*</h3>
                <select id="Template" name="Template">
                	<?php
                    	buildTemplateList ($apikey, $apiroot);
                	?>
                </select> 
                <input id="Recipients" name="Recipients" type="hidden" value="cgJson">
                <br><br>
                <textarea id="csv" maxlength="200000" cols="120" name="csv" type="textdataentry"  class="text" placeholder=
'"address","UserName","substitution","first","id","city"
"jeff.goldstein@sparkpost.com","Sam Smith","_","Sam",342,"Princeton"
"austein@hotmail.com","Fred Frankly","_","Fred",38232,"Nowhere"
"jeff@geekswithapersonality.com","Zachary Zupers","_","Zack",9,"Hidden Town"'></textarea>
				<br><br>
    			<input type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="convert()" value="Convert to JSON">
    			&nbsp;&nbsp;
				<input type="button" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="generatePreview(), match()" value="Preview & Validate">
				&nbsp;&nbsp;
				<input type="button" id="globalButton" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" onclick="showGlobalSubField()" value="Show Global Sub">
				<br><br>
				<input id="recipientMessageText" name="recipientMessageText" readonly type="textnormal" style="display:none; border:none; width: 725px;">
    			<textarea id="json" name="json" class="text" maxlength="675000" cols="120" placeholder=
'{"recipients":[
{"address":"jeff.goldstein@sparkpost.com","UserName":"Sam Smith","substitution_data":{"first":"Sam","id":"342","city":"Princeton"}},
{"address":"austein@hotmail.com","UserName":"Fred Frankly","substitution_data":{"first":"Fred","id":"38232","city":"Nowhere"}},
{"address":"jeff@geekswithapersonality.com","UserName":"Zachary Zupers","substitution_data":{"first":"Zack","id":"9","city":"Hidden Town"}}
]}'></textarea>
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
                		<span class="tooltiptext">Note:<br>1) Campaigns scheduled within 10 minutes of running cannot be cancelled.<p>2) Campaigns can only be scheduled less than 32 days out.</span>
                	</div>
                </h3>
                <input checked id="now" name="now" type="checkbox" value="T"> OR Enter Date/Time 
                <input data-format="YYYY-MM-DD" data-template="YYYY-MM-DD" id="datepicker" name="date" placeholder="YYYY-MM-DD" type="text">
                <input data-format="HH" data-template="HH" max="23" min="0" name="hour" size="6" type="number" value="00"> 
                <input data-format="MM" data-template="MM" max="59" min="0" name="minutes" size="6" type="number" value="00"> 
                <?php
                    $tzSelect = buildTimeZoneList ();
                    echo $tzSelect;
                ?>
                <h3>Campaign Name:*</h3>
                <input name="campaign" required="" type="text">
                <br>
                <h3>Global Return Path (Required for Elite/Enterprise SparkPost Users):*</h3>
                <input id="returnpath" name="returnpath" type="text">@
                <select id="domain" name="domain">
                <?php
                    buildDomainList ($apikey, $apiroot);
                ?>
                </select> 
                <br><br>
                <input checked name="open" type="checkbox" value="T"> Turn on Open Tracking
                <br>
                <input checked name="click" type="checkbox" value="T"> Turn on Click Tracking
                <br>
                <h3>Optional Items (leave blank if you don't want to use them)...</h3>
                <h4>Want Proof, Enter Your Email Address Here</h4><input name="email" type="email" value="">
                <h4>Enter Meta Data: first column Is the Metadata Field Name, the second column is the data:</h4>
                Metadata Field Name: <input name="meta1" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data1" type="textnormal" value="">
                <br><br>
                Metadata Field Name: <input name="meta2" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data2" type="textnormal" value="">
                <br><br>
                Metadata Field Name: <input name="meta3" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data3" type="textnormal" value="">
                <br><br>
                Metadata Field Name: <input name="meta4" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data4" type="textnormal" value="">
                <br><br>
                Metadata Field Name: <input name="meta5" type="textnormal" value=""> &nbsp;&nbsp;&nbsp;Data: <input name="data5" type="textnormal" value="">
                <br><br><br>
                <input id="submit" size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" type="submit" value="Submit"> 
                <input size="10" style="color: #FFFFFF; font-family: Helvetica, Arial; font-weight: bold; font-size: 12px; background-color: #72A4D2;" type="reset" value="Reset" onclick="resetpreview(), resetsummary()"><p><p>
            </form>
        </td>
    </table>
</td>
<td style="" width="5"></td>
<?php
    $curl = curl_init();
    $url = $apiroot . "/transmissions/";
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
        "content-type: application/json"
    )));

    $response = curl_exec($curl);
    $err      = curl_error($curl);
    curl_close($curl);
    //echo $response;
    if ($err) 
    {
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
    <table id="substitutionTable" border="0" bgcolor="FFFFFF" width="450"  height="450" style="display:block">
        <tr>
            <td>
                <div class="main">
                    <iframe id="substitution" height="450" width="450" style="background: #FFFFFF;" cellpadding="10" srcdoc="<p>Please select your Template and Recipient List</p>"></iframe>
                </div>
            </td>
        </tr>
    </table>
    <table id="templateTable" border="0" bgcolor="FFFFFF" width="450"  height="450" style="display:none">
        <tr>
            <td>
                <div class="main">
                    <iframe id="template" height="450" width="450" style="background: #FFFFFF;" cellpadding="10" srcdoc="<p>Please select your Template and Recipient List</p>"></iframe>
                 </div>
            </td>
        </tr>
    </table>
</td>
</tr>
</table>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* Mandatory fields
<table cellpadding="25" border=0>
	<tr>
		<td>
    		<h3>Preview Using Selected Template and First Member of JSON Recipient List</h3><br>
    		<i>**This feature is still in beta...Still working on error messaging...Large Recipient Lists may cause the Preview to malfunction</i>
    		<div class="main">
        		<iframe height="600" id="iframe1" name="iframe1" width="1300" style="background: #FFFFFF;" srcdoc="<p>Please select your Template and Recipient List</p>"></iframe>
    		</div>
    	</td>
	</tr>
</table>
</body>
</html>

