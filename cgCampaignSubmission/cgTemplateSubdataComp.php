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

File: cgTemplateSubdatacomp.php
Purpose: Compare Templates against Substitution Data and visa-versa.
This will hopefully help the user (along with the template preview, todecide if they 
are sending the right data for the template.  And if appropriate make sure the template is 
using all of the data being sent to it.

-->
<!DOCTYPE html>
<html>
<head>
<title>Campaign Generator for SparkPost</title>
<link href="https://dl.dropboxusercontent.com/u/4387255/Tools.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
body 
{
  	font-family: Helvetica, Arial;
}
table 
{
	font-family: Helvetica, Arial;
    border-collapse: collapse;
}


</style>
</head>
<body>

<?php 
{

$keywords = array("and", "break", "do", "else", "elseif", "end", "false", "for", "function", "if", "in", "local", "nil", ",not", "!", "or", "each", "repeat", "return", "then", "true", "until", "while", '"', '""');
array_push($keywords, "==", "=", "!=", "<", ">", "opening_double_curly", "closing_double_curly", "opening_triple_curly", "closing_triple_curly", "loop_var", "loop_vars", "render_dynamic_content", "dynamic_html", "dynamic_text", "]", ")", "])"); 

function isJson($string) 
{
  global $json_check_result;
  json_decode($string);
  switch (json_last_error()) 
  {
        case JSON_ERROR_NONE:
            $json_check_result = ' No errors';
        break;
        case JSON_ERROR_DEPTH:
            $json_check_result =  ' Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            $json_check_result = ' Underflow or the modes mismatch, check brackets?';
        break;
        case JSON_ERROR_CTRL_CHAR:
            $json_check_result = ' Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            $json_check_result = ' Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            $json_check_result = ' Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            $json_check_result = ' Unknown error';
        break;
  }
  return (json_last_error() == JSON_ERROR_NONE);
}

function embeddedcheck ($haystack, $needle)
{
		
	$embedded = "/[\[\].{}()\s!]/";
	$keyLength = strlen($needle);
	$clean = 10;
	foreach ($haystack as $matchkey => $matchvalue)
	{
		$start = 0;
		while ((($pos = strpos($matchvalue, $needle, $start)) !== FALSE) && $clean !=1) 
		{
  			$before = substr($matchvalue, $pos-1, 1);
  			$checkBefore = preg_match($embedded, $before);
  			if ($checkBefore > 0)
  			{
  				$after =  substr($matchvalue, $pos+$keyLength, 1);
  				$checkAfter = preg_match($embedded, $after);
  				if ($checkAfter > 0) $clean = 1;
  			}
  			$start = $pos + 1;
  			//echo " before: " . $before . " after: " . $after . " clean flag: " .$clean;
  		}
	}
	return $clean;
}

function newWordValidation ($word)
{
	global $substitutionItemList, $templateItemList, $keywords, $templateTable, $templatefnd, $nttemplatefnd, $templateTotal; 
	if (!in_array($word, $keywords) && (strlen($word) > 0))  //Check against keywords first
	{
		if (!array_key_exists($word, $templateItemList)) //Check against already found items in the template
		{
			$templateTotal++;
			if (array_key_exists($word, $substitutionItemList)) //Check against items from the substitution field array
			{
    			$templatefnd++; $templateItemList[$word] = "Yes";
    			$templateTable .= "<tr bgcolor=\"#ddd\"><td>";
    		} 
    		else 
    		{	
    			$nttemplatefnd++; $templateItemList[$word] = "No";
    			$templateTable .= "<tr bgcolor=\"#fcf7f4\"><td>";
    		}
    		$templateTable .=  $word . "</td><td>" . $templateItemList[$word] . "</td></tr>";
    	}
    }
}

function getTemplateFields ($templateHTML)
{

	$initialParse = "/{{(.*)}}/U";
  	$getNumberFound = preg_match_all($initialParse, $templateHTML, $shreded);
  	$shreded = $shreded[1];
  	$scanText = "/[{\s\[\].()!]/";
  	foreach ($shreded as $shredkey => $shredvalue)
  	{
  		$index = 0; $start=0; $end=0;
  		$length=strlen($shredvalue);
  		while ($index < $length)
  		{
  			$currentChar = substr($shredvalue, $index, 1);
  			$stop = preg_match($scanText, $currentChar);
  			if ($stop && $index !=0)
  			{
  				$word = substr($shredvalue, $start, $end-$start);
  				newWordValidation($word);
  				$start = $index + 1;
  			}
  			if ($stop && $index == 0) $start++;
  			$index++; $end++;
  		}
  		$word = substr($shredvalue, $start, $end-$start);
  		newWordValidation($word);	
  	}
}
//
//Main code body
//			
$apikey     = $_POST["apikey"];
$apiroot	= $_POST["apiroot"];
$template   = $_POST["template"];
$recipients = $_POST["recipients"];
$datatype	= $_POST["type"];
$enteredRecipientData	= $_POST["entered"];
$globalsub			= $_POST["globalsub"];

if ($recipients == "cgJson")
{
	$checkjson = isJson ($enteredRecipientData);
	if ($checkjson != 1) 
	{
		echo "<h4>JSON structure error on entered Recipient data: " . $json_check_result . "</h4>";
		echo "<br><br>";
		echo "<h5>This is a great site for validating your JSON input: <a href=\"http://jsonlint.com/#/\" target=\"_blank\">JsonLint</a></h5>";
		return;
	}
	else
	{
		if (strlen($enteredRecipientData) > 1) 
		{
			//get manually entered recipient data
			$makeArray = json_decode($enteredRecipientData, true);
			$one_entry = $makeArray['recipients'][0]['substitution_data'];
			$rec_sub  = json_encode($one_entry);
			$recipients = "User Entered Data";
		}
	}
}
else
{
	if ($recipients != "Recipient List Not Entered")
	{
		$curl     = curl_init();
		$url = $apiroot . "/recipient-lists/" . $recipients . "?show_recipients=true";
		curl_setopt_array($curl, array(
    	CURLOPT_URL => $url,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_ENCODING => "",
    	CURLOPT_MAXREDIRS => 10,
    	CURLOPT_TIMEOUT => 60,
    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    	CURLOPT_CUSTOMREQUEST => "GET",
    	CURLOPT_HTTPHEADER => array(
    		"authorization: $apikey",
    		"cache-control: no-cache",
    		"content-type: application/json")));
    
		$dataResponse = curl_exec($curl);

		$err = curl_error($curl);
		curl_close($curl);
		if ($err) 
		{
    		echo "cURL Error #:" . $err;
		}

		// Get the User Substitution Data
		$arrayofRecipients = json_decode($dataResponse, true);
		$one_entry             = $arrayofRecipients['results']['recipients'][0]['substitution_data'];
		if ($one_entry === NULL) //recipient list doesn't have substitution data;
    	{
    		$rec_sub = "";
    	}
    	else
    	{
			$rec_sub  = json_encode($one_entry);
		}
	}
	else
	{
		$rec_usb = "";
		echo "<h3>**No Recipient List Selected**</h3>";
	}
}
		
//
// Now let's see what data we actually have, and create one substitution group for the comparisons
//
//echo "strlen of glob: " . strlen($globalsub) . " and strlen of rec_sub: " . strlen($rec_sub);			
if ((strlen($globalsub) > 1) && (strlen($rec_sub) > 4)) //we have both global and personal substitution data
{
	// We will concatenate recipient data after the global data into one substitution_data object
	// We need to remove/change some quotes, commas, brackets to do this
	$pos = strpos($globalsub, "{"); // Find the begining of the actual fields after the 'substitution_data' key name
	$globalsub = substr($globalsub, $pos); // Strip 'substitution_data
	if (substr($globalsub, -1) == ",") $globalsub = substr($globalsub, 0, -1); //remove trailing comma user entered
	$globalsub = trim ($globalsub); //remove any white space
	if (substr($globalsub, -1) == "}") $globalsub = substr($globalsub, 0, -1) . ","; //change closing bracket to comma
	$rec_sub = substr($rec_sub, 1);  //remove opening bracket 
	$subEntry = $globalsub . $rec_sub;
}
if ((strlen($globalsub) > 1) && (strlen($rec_sub) < 4)) //global substitution only
{
	$pos = strpos($globalsub, "{"); // Find the begining of the actual fields after the 'substitution_data' key name
	$globalsub = substr($globalsub, $pos); // Strip 'substitution_data
	if (substr($globalsub, -1) == ",") $globalsub = substr($globalsub, 0, -1); //remove trailing comma user entered
	$subEntry = trim ($globalsub); //remove any white space
}
if ((strlen($globalsub) < 1) && (strlen($rec_sub) > 4)) //personal substitution only
{
	//$subEntry = '{"substitution_data":' . $rec_sub . '}';
	$subEntry = $rec_sub;
}
// Create an empty array object.  Will fail at the begining of the loop
if ((strlen($globalsub) < 1) && (strlen($rec_sub) < 4))
{
	$rec_sub = json_decode ("{}");
	//$subEntry = '{"substitution_data":' . $rec_sub . '}';
	$subEntry = json_encode($rec_sub);
	echo "<h4>**No Recipient or Global Substitution Data Found**</h4>";
}

$just_sub = json_decode($subEntry);
//echo "<pre>";
//var_dump($just_sub);
//echo "</pre>";
//some of these can get big, so let's clean them out
unset($enteredRecipientData); unset($rec_sub); unset($makeArray); unset($one_entry);
    
//
// Score Matches of substitution data to templates substitutions
//
$curl     = curl_init();
$url      = $apiroot. "/templates/" . $template;
//    CURLOPT_POSTFIELDS => $subEntry,  just noticed this below...taking it out for the moment.  don't need it on the 'GET'
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
    )
));
    
$response = curl_exec($curl);
$encodedResponse = json_decode($response, true);
$errorFromAPI    = $encodedResponse["errors"];
$storedRawTemplate = $encodedResponse["results"]["content"]["html"];
$err = curl_error($curl);
curl_close($curl);

function checkCompatibility ($string)
{
	global $substitutionfnd, $ntsubstitutionfnd, $storedRawTemplate, $substitutionItemList, $substitutionTable, $total, $systemadded, $numeric;
	foreach ($string as $key => $value)
	{
		$loose = "/{{((?!}}).)*" .$key . "((?!{{).)*}}/";
		$found = array_key_exists($key, $substitutionItemList);
		if (!$found)
		{
			$total++;	
			$pos = preg_match_all($loose, $storedRawTemplate, $allmatches);
			$allmatchesStrings = $allmatches[0];
			$clean = embeddedCheck($allmatchesStrings, $key);

    		if (($pos > 0) && $clean==1) 
    		{
    			$substitutionfnd++; $substitutionItemList[$key] = "Yes";
    			$substitutionTable .= "<tr bgcolor=\"#ddd\"><td>";
    		} 
    		else 
    		{	
    			if (is_numeric($key))
    			{
    				if ($key>50) 
    				{
    					$numeric++;
    					$substitutionItemList[$key] = "Index";
    					$substitutionTable .= "<tr bgcolor=\"#fcf7f4\"><td>";
    				} 
    				else 
    				{
    					$systemadded++;
    					$substitutionItemList[$key] = "Index";
    					$substitutionTable .= "<tr bgcolor=\"yellow\"><td>";
    				}
    			}
    			else
    			{
    				$ntsubstitutionfnd++; $substitutionItemList[$key] = "No";
    				$substitutionTable .= "<tr bgcolor=\"#fcf7f4\"><td>";
    			}
    		}
    		$substitutionTable .=  $key . "</td><td>" . $substitutionItemList[$key] . "</td></tr>";
    	}	
    	if ($key != NULL)
    	{
    		checkCompatibility($value);
    	}
    }
}	
    
$substitutionfnd = 0; $ntsubstitutionfnd = 0; $numeric = 0; $systemadded = 0; $total = 0; 
$templatefnd = 0; $nttemplatefnd = 0; $templateTotal = 0;

$substitutionTable = "<font size=\"3\"><table width=448 border=1 cellpadding=10><tr><th>Substitution Data</th><th width=50>Match?</th></tr>";
checkCompatibility ($just_sub);
$substitutionTable .= "</table></font>";
$summary = "<center><h3> Substitution Data Summary</h3><font face=\"Helvetica\" size=\"3\">Number of Fields in Recipient Data: " . $total;
$summary .= "<br>Number of Fields matched: " . $substitutionfnd;
$summary .= "<br>Number of Fields not matched: " . $ntsubstitutionfnd;
$summary .= "<br>Number of probable index items: " . $numeric;
$summary .= "<br>Number of probable system added indexes: " . $systemadded;
$ratioFound = round((($substitutionfnd / $total) * 100), 2);
$expected = round((($substitutionfnd / ($total - $numeric - $systemadded)) * 100),2);
$summary .= "<br>&nbsp;&nbsp;&nbsp;Overall Ratio is: " . $ratioFound . "%  ...without numeric fields: " . $expected . "%</font></center><br>";
if ($datatype == "substitution")
{
	echo "<font size=\"3\"><table border=1><td>";
	echo $summary; 
	echo $substitutionTable;
	echo "</td></table><font>";
}
$templateTable = "<font size=\"3\"><table width=448 border=1 cellpadding=10><tr><th>Template Variables Found</th><th width=50>Match?</th></tr>";
getTemplateFields($storedRawTemplate);
$templateTable .= "</table></font>";
$summary = "<center><h3> Template Data Summary</h3><font face=\"Helvetica\" size=\"3\">Number of Fields Found in Template: " . $templateTotal;
$summary .= "<br>Number of Fields matched: " . $templatefnd;
$summary .= "<br>Number of Fields not matched: " . $nttemplatefnd;
$ratioFound = round((($templatefnd / $total) * 100), 2);
$summary .= "<br>&nbsp;&nbsp;&nbsp;Overall Ratio is: " . $ratioFound . "%</font></center><br>";
if ($datatype == "template")
{
	echo "<font size=\"3\"><table border=1><td>";
	echo $summary; 
	echo $templateTable;
	echo "</td></table></font>";
}
    
} //end of program
?>
</body></html>
