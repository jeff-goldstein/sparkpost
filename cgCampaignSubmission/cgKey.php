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

File: cgKey.php
Purpose: Landing page for logging in.
-->
<!DOCTYPE html>
<html>
<head>
<title>Campaign Generator for SparkPost</title>
<link href="http://bit.ly/2elb0Hw" rel="shortcut icon" type="image/vnd.microsoft.icon">
<link rel="stylesheet" type="text/css" href="cgCommonFormatting.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="cgCommonScripts.js"></script>

</head>

<body id="bkgnd">
<ul class="topnav" id="generatorTopNav">
	<li><a class="active" href="#home">Home</a></li>
	<li><a href="cgHelp.php">Help</a></li>
	<li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
	<li><a href="mailto:email.goldstein@gmail.com?subject=cgMail">Contact</a></li>
	<li class="icon">
    	<a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
  	</li>
</ul>

<center>
    <br><h1>Campaign Generator for SparkPost</h1><br><br>
</center>
<table border="0" width="75%" cellpadding="20">
    <tr>
        <td>
    		<form action="cgBuildSubmitStored.php" id="keyform" name="keyform" onsubmit="stringToHex()">
        		<h3>Your SparkPost API Key:</h3>
        		<input id="key" name="apikey" placeholder="API Key.." required=true type="text" autocomplete="off">
        		<br><br>
        		<h5>Optional for Enterprise/Elite Users: Enter your API Root URL: Empty will default to: https://api.sparkpost.com/api/v1/</h5> 
        		<input id="apiroot" name="apiroot" placeholder="API Root Directory" type="text" value="https://api.sparkpost.com/api/v1">
        		<br><br>
        		<input  type="submit" value="Submit" name="submit">
    		</form>
    	</td>
    </tr>
</table>
<table border="0" width="75%" cellpadding="20">
    <tr>
        <td>
            Note: These API Keys are created from the admin page within your SparkPost account at 
            <a href="https://app.sparkpost.com/account/credentials">https://app.sparkpost.com/account/credentials</a>.  
            Please remember that the SparkPost system only shows your API Key "once", so you need to keep the API Key safe where 
            you can get to it each time you use this or any application that needs an API Key.  If you loose the API Key you can always create a new one.<p>
                    
            At a minimum, you need to select 'Recipient Lists: Read/Write, Templates: Read/Write, Transmissions: Read/Write and Sending Domains: Read'.
            
            <br><br><br><h2>For a secure link you may use <a href="http://geekwithapersonality.ipage.com/cgi-bin/cgKey.php"> Secured Campaign Generator</a></h2>
        </td>
    </tr>
</table>
<br><br><br>
<center>
    <table border="1" style="background-color:#a3e9f7" width="75%" cellpadding="20">
        <tr>
            <td>
                This tool is free to use at your own risk and should be up
                and running at all times but is not monitored for uptime.
                The Campaign Generator DOES NOT hold any of your
                information, it simply uses your API key to obtain enough
                information in order to create, schedule or cancel your
                campaigns. The code behind this site is available from my
                Github repository at: <a href=
                "https://github.com/jeff-goldstein/sparkpost/tree/master/cgCampaignSubmission" target="_blank">
                https://github.com/jeff-goldstein/sparkpost/tree/master/cgCampaignSubmission</a>.
            </td>
        </tr>
    </table>
</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>*v1.05 Last Updated Oct 15, 2016
</body>
</html>
