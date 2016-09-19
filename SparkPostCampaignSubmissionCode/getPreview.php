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
<?php {
    $curl       = curl_init();
    $apikey     = $_GET["apikey"];
    $template   = $_GET["template"];
    $recipients = $_GET["recipients"];
    $url        = "https://api.sparkpost.com/api/v1/recipient-lists/" . $recipients . "?show_recipients=true";
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
    
    // Get the User Substitution Data
    //var_dump($response);
    $someArrayofRecipients = json_decode($response, true);
    //$obj = $someArrayofRecipients->results->recipients[0]->substitution_data;
    $rec_sub               = $someArrayofRecipients['results']['recipients'][0]['substitution_data'];
    $rec_sub               = json_encode($rec_sub, JSON_FORCE_OBJECT);
    $indexPattern          = "/\"\\d\":/";
    $rec_sub               = preg_replace($indexPattern, "", $rec_sub);
    $indexPattern          = "/{{/";
    $rec_sub               = preg_replace($indexPattern, "[{", $rec_sub);
    $indexPattern          = "/}}/";
    $rec_sub               = preg_replace($indexPattern, "}]", $rec_sub);
    
    // Get Global Substitution Data
    // Well, can't do that because SparkPost doesn't keep global Substitution data
    // That means Global data like dynamic_html will be missing and may cause preview failure
    // Keeping this code for when global data is supported
    //
    //$global_sub = $someArrayofRecipients['results']['substitution_data'];
    //$global_sub = json_encode($global_sub, JSON_FORCE_OBJECT);
    //$indexPattern = "/\"\\d\":/";
    //$global_sub = preg_replace($indexPattern, "", $global_sub);
    //$indexPattern = "/{{/";
    //$global_sub = preg_replace($indexPattern, "[{", $global_sub);
    //$indexPattern = "/}}/";
    //$global_sub = preg_replace($indexPattern, "}]", $global_sub);
    
    $subEntry = '{"substitution_data":' . $rec_sub . '}';
    $curl     = curl_init();
    $url      = "https://api.sparkpost.com/api/v1/templates/" . $template . "/preview";
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $subEntry,
        CURLOPT_HTTPHEADER => array(
            "authorization: $apikey",
            "cache-control: no-cache",
            "content-type: application/json"
        )
    ));
    
    $response = curl_exec($curl);
    
    $encodedResponse = json_decode($response, true);
    $errorFromAPI    = $encodedResponse["errors"];
    $preview = $encodedResponse["results"]["html"];
    $err     = curl_error($curl);
    
    curl_close($curl);
    if ($err) {
        $preview = "cURL Error #:" . $err;
        echo $preview;
    } else {
        if ($errorFromAPI != null) {
            $error_out = "<h3>Matching Problem between selected Template: <u>" . $template . "</u> and Recipient List: <u>" . $recipients . "</u></h3>";
            $error_string .= var_export($errorFromAPI, true);
            $error_out .= "<pre>" . $error_string . "</pre>";
            echo $error_out;
        } else {
            $preview_out = "<h3>***Preview for selected Template: <u>" . $template . "</u> and Recipient List: <u>" . $recipients . "</u></h3>" .  $preview;
            echo $preview_out;
        }
    }
}
?>
