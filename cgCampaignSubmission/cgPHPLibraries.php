<?php
{
//
// Get The Stored Recipient-Lists Templates from the Account
//
function getRecipientListFromServer($apikey, $apiroot)
{
    $url = $apiroot . "/recipient-lists";
    $curl = curl_init();
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

    // Convert JSON string to Array
    return $recipientsArray = json_decode($response, true);
}


//
// Get The 'Published Only' Templates from the Account
//
function getTemplateListFromServer($apikey, $apiroot)
{
    $url = $apiroot . "/templates/?draft=false";
    $curl = curl_init();
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
    $err      = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    }
    
    return $templateArray = json_decode($response, true);
}

//
// Get The 'Domains' from the Account
//
function getDomainListFromServer($apikey, $apiroot)
{
    $url = $apiroot . "/sending-domains";
    $curl = curl_init();
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
    $err      = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    }
    
    return $domainArray = json_decode($response, true);
}

                        
//
// Build the dropdown Selector from the Template API call
//
function buildTemplateList ($key, $apiroot)
{
	$templateArray = gettemplateListFromServer($key, $apiroot);
	echo "<option selected=\"selected\" value=\"Template Not Entered\">Select a Template</option>";
	foreach ($templateArray as $key => $value) 
	{
    	foreach ($value as $key2 => $value2) 
    	{
        	foreach ($value2 as $key3 => $value3) 
        	{
            	if ($key3 == "id") echo '<option value="' . $value3 . '">' . $value3 . '</option>';
        	}
    	}
    }
}

//
// Build the dropdown Selector from the Template API call
//
function buildRecipientList ($key, $apiroot)
{
	$recipientArray = getRecipientListFromServer($key, $apiroot);
	echo "<option selected=\"selected\" value=\"Recipient List Not Entered\">Select a Recipient</option>";
	foreach ($recipientArray as $key => $value) 
	{
		foreach ($value as $key2 => $value2) 
		{
	        foreach ($value2 as $key3 => $value3) 
	        {
	            if ($key3 == "id") echo '<option value="' . $value3 . '">' . $value2["name"] . " (" . $value2["total_accepted_recipients"] . ' Recipients)</option>';
	        }
	    }
	}
}

//
// Build the dropdown Selector from the Template API call
//
function buildDomainList ($key, $apiroot)
{
	$domainArray = getDomainListFromServer($key, $apiroot);
	echo "<option selected=\"selected\" value=\"\">Select a Domain</option>";
	foreach ($domainArray as $key => $value) 
	{
		foreach ($value as $key2 => $value2) 
		{
			if ($value2["status"]["spf_status"]=="valid" && $value2["status"]["dkim_status"]=="valid")
			{
				echo '<option value="' . $value2["domain"] . '">' . $value2["domain"] . '</option>';
			}
		}
	}
}
		

//
// Get a list of all current timezones, doing this dynamically because of DST
//
function timezoneList()
{
    $timezoneIdentifiers = DateTimeZone::listIdentifiers();
    $utcTime = new DateTime('now', new DateTimeZone('UTC'));
 
    $tempTimezones = array();
    foreach ($timezoneIdentifiers as $timezoneIdentifier) {
        $currentTimezone = new DateTimeZone($timezoneIdentifier);
 
        $tempTimezones[] = array(
            'offset' => (int)$currentTimezone->getOffset($utcTime),
            'identifier' => $timezoneIdentifier
        );
    }
 
    // Sort the array by offset,identifier ascending
    usort($tempTimezones, function($a, $b) {
		return ($a['offset'] == $b['offset'])
			? strcmp($a['identifier'], $b['identifier'])
			: $a['offset'] - $b['offset'];
    });
 
	$timezoneList = array();
    foreach ($tempTimezones as $tz) {
		$sign = ($tz['offset'] > 0) ? '+' : '-';
		$offset = gmdate('H:i', abs($tz['offset']));
        $timezoneList[$tz['identifier']] = $sign . $offset;
    }
 
    return $timezoneList;
}


//
// Compact List of for Timezone Dropdown while getting current offsets
//
function buildTimeZoneList ()
{
	$defaultZone = "America/Los_Angeles";

	$displayZones["Pacific/Midway"] = "Midway Island, Samoa";  
	$displayZones["Pacific/Honolulu"] = "Hawaii";  
	$displayZones["Pacific/Tahiti"] = "Tahiti";  
	$displayZones["America/Anchorage"] = "Alaska";  
	$displayZones["America/Los_Angeles"] = "Los Angeles, San Francisco, Seattle";  
	$displayZones["America/Phoenix"] = "Phoenix";  
	$displayZones["America/Tijuana"] = "Tijuana, Baja California";  
	$displayZones["America/Denver"] = "Mountain Time (US & Canada)";  
	$displayZones["America/Bogota"] = "Bogota/Cayman/Jamaica";  
	$displayZones["America/Mexico_City"] = "Mexico_City";  
	$displayZones["America/Chicago"] = "Chicago";  
	$displayZones["America/Indiana/Knox"] = "Indiana";  
	$displayZones["America/New_York"] = "New York";  
	$displayZones["America/Toronto"] = "Toronto";  
	$displayZones["America/Puerto_Rico"] = "Puerto Rico";  
	$displayZones["America/Argentina/Buenos_Aires"] = "Buenos Aires/San Juan";  
	$displayZones["America/Goose_Bay"] = "Greenland";  
	$displayZones["America/Sao_Paulo"] = "Sao Paulo";  
	$displayZones["Atlantic/Bermuda"] = "Bermuda";  
	$displayZones["America/St_Johns"] = "St Johns/Mid-Atlantic";  
	$displayZones["Atlantic/Cape_Verde"] = "Cape Verde";  
	$displayZones["UTC"] = "UTC";  
	$displayZones["Africa/Monrovia"] = "Monrovia, Reykjavik";  
	$displayZones["Europe/London"] = "Dublin, Edinburgh, Lisbon, London";  
	$displayZones["Africa/Casablanca"] = "Casablanca, Lagos, Tunis";  
	$displayZones["Africa/Cairo"] = "Cairo, Johannesburg, Tripoli";  
	$displayZones["Europe/Amsterdam"] = "Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna";  
	$displayZones["Europe/Belgrade"] = "Belgrade, Bratislava, Budapest, Ljubljana, Prague";  
	$displayZones["Europe/Brussels"] = "Brussels, Copenhagen, Madrid, Paris, Warsaw";  
	$displayZones["Europe/Athens"] = "Athens, Bucharest, Istanbul";  
	$displayZones["Asia/Jerusalem"] = "Jerusalem, Syowa, Moscow, St. Petersburg, Volgograd";  
	$displayZones["Europe/Bucharest"] = "Brussels, Copenhagen, Madrid, Paris, Warsaw";  
	$displayZones["Europe/Belgrade"] = "Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius";  
	$displayZones["Asia/Beirut"] = "Beirut, Cairo, Gaza, Kuwait";  
	$displayZones["Asia/Tehran"] = "Tehran";  
	$displayZones["Asia/Kabul"] = "Kabul";  
	$displayZones["Asia/Tashkent"] = "Islamabad, Karachi, Tashkent";  
	$displayZones["Asia/Colombo"] = "Colombo, Chennai, Kolkata, Mumbai, New Delhi";  
	$displayZones["Asia/Kathmandu"] = "Kathmandu";  
	$displayZones["Asia/Almaty"] = "Almaty, Novosibirsk";  
	$displayZones["Asia/Rangoon"] = "Yangon (Rangoon)";  
	$displayZones["Asia/Bangkok"] = "Bangkok, Hanoi, Jakarta";  
	$displayZones["Asia/Hong_Kong"] = "Beijing, Chongqing, Hong Kong, Urumqi";  
	$displayZones["Asia/Taipei"] = "Taipei, Perth";  
	$displayZones["Asia/Pyongyang"] = "Pyongyang";  
	$displayZones["Australia/Eucla"] = "Eucla";  
	$displayZones["Asia/Tokyo"] = "Osaka, Sapporo, Tokyo";  
	$displayZones["Australia/Darwin"] = "Darwin";  
	$displayZones["Australia/Brisbane"] = "Brisbane, Guam";  
	$displayZones["Australia/Sydney"] = "Canberra, Melbourne, Sydney, Pohnpei";  
	$displayZones["Pacific/Fiji"] = "Fiji, Wake, Wallis";  
	$displayZones["Pacific/Auckland"] = "Auckland, Enderbury";  
	$displayZones["Pacific/Chatham"] = "Chatham";  
	$displayZones["Pacific/Apia"] = "Apia, Kiritimati";  

	$timezoneList = timezoneList();
	$timeSelect = '<select name="tz">';
	
	foreach ($displayZones as $tzone => $location)
	{		
		if ($tzone == $defaultZone)
		{
		
			$timeSelect .= '<option selected="selected" value="' . $timezoneList[$tzone] . '"> (GMT' . $timezoneList[$tzone] . ') ' . $location . '</option>';
			//echo 'timezone: ' . $timezoneList[$tzone] . '"> (GMT' . $timezoneList[$tzone] . ') ' . $location;
		}
		else
		{
			//echo 'timezone: ' . $timezoneList[$tzone] . '"> (GMT' . $timezoneList[$tzone] . ') ' . $location;
			$timeSelect .= '<option value="' . $timezoneList[$tzone] . '"> (GMT' . $timezoneList[$tzone] . ') ' . $location . '</option>';
		} 
	}
	$timeSelect .= '</select>';
	return $timeSelect;
}

}?>