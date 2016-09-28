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
    <title>SparkPost Campaign Generation</title>
    <link href="https://dl.dropboxusercontent.com/u/4387255/Tools.ico" rel=
    "shortcut icon" type="image/vnd.microsoft.icon">
    <style>
    /* Set Background to SparkPost Flames */
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

    /* Expandable text fields when active */
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
    width: 300px;
    border: 3px solid #555;
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

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>


<script>
function createhash() 
{
    var japikey = document.getElementById("key").value;
    var jhash  = document.getElementById("hash").value;
    jhash = CryptoJS.AES.encrypt(japikey, "sparkpost");
}

function d2h(d) {
    return d.toString(16);
}

function stringToHex() {
    var japikey = document.getElementById("key").value;
    var str = '',
        i = 0,
        tmp_len = japikey.length,
        c;
 
    for (; i < tmp_len; i += 1) {
        c = japikey.charCodeAt(i);
        str += d2h(c);
    }
    document.keyform.key.value = str;
    return true;
}
</script>
</head>

<body id="bkgnd">
<ul class="topnav" id="myTopnav">
  <li><a class="active" href="SparkPostKey.php">Home</a></li>
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
<center>
    <h1>SparkPostMail Campaign Generator Help</h1>
</center>
<table border="0" height=100 width="90%" cellpadding="10">
<tr><td>
<strong>Welcome</strong> to SparkPostMail Campaign Generator.  First things first....  This tool was not created nor is it supported by SparkPost.  This tool was created by some SparkPost community contributors and is open for anyone to use.
This tool is free to use at your own risk and should be up and running at all times but is not monitored for uptime. The Campaign Generator DOES NOT hold any of your information, it simply uses your API key to obtain enough 
information in order to create, schedule or cancel your campaigns. The code behind this site is available from my Github repository at: https://github.com/jeff-goldstein/sparkpost/tree/master/SparkPostCampaignSubmissionCode. 
</td></tr>
<tr><td>
<strong>I</strong> created this simple generation tool to fill in missing functionality from SparkPost.  Since SparkPost is geared toward developers anyone who needs to send Campaigns either had to build the functionality themselves or use one of SparkPost's partners.  So I created this tool as a quick way to either launch a campaign now or to schedule it out for a later release.
When using this tool, please understand the nuances of SparkPost because I won't document them in this tool.  For example as of Sept 2016, campaigns can only be cancelled if they are scheduled for a time more than one day out.  I'm not going to document all of these nuances and will let SparkPost documentation speak for itself; so if something isn't exactly working
the way you expected it to work, please check their documentation first at, 
<a href="https://developers.sparkpost.com/">SparkPost Documentation</a>
</td></tr>
<tr><td>
<h4>Logging in with a SparkPost API Key</h4>
<strong>The</strong> first step in using the generator is to obtain a SparkPost API key which will give this application access to your account.  As stated before this key is NOT stored by this application and is only used during your sessions.  If you don't have an API Key and need some help in generating one, here is a SparkPost article on the subject: <a href="https://support.sparkpost.com/customer/portal/articles/1933377">Create API Keys.</a>
<p>
Once you have the key, you can use it on the main page to start the campaign process.  In order to protect your key, the system will then obfuscate your key through the rest of the session.  Also, I have turned off autofill for the field that requests the API key, so the browser probably won't fill in that field the next time you come back to that page.  I state this because the SparkPost API generator does not hold the key either so if you loose that key you will have to create another one.
</td></tr>
<tr><td>
<h4>Creating Your Campaign</h4>
<strong>Here</strong> is the meat of the application.  The API Key is used to call the SparkPost application to obtain a list of your published templates and all recipient lists that have been uploaded to the system.  Go ahead and select which ones you are interested in using for this campaign.  Down at the bottom, the SparkPostMail system will attempt to display what the email will look like by taking the first person in the recipient list and using their data.  
<p><i>**Warning** If the recipient list is large, it will take some time for they system to retrieve the data for the preview.  The large list may also cause the preview to timeout. </i>
<p>If SparkPost had an error matching the data to the template, the preview box will output the system error, a warning alert will display and the submit button will be turned off. Please keep in mind that this does not mean that all mismatched data will cause an error so look at the template closely to make sure you have the data you need in the recipient list to fill out the template properly. 
<p>Now you can pick when you want the campaign to go out.  You can either have it go out immediately, or you can schedule it out.  Currently, SparkPost will reject any scheduled campaign farther out than a month.  If you enter in a scheduled date/time, that will take precedence over the 'now' flag.  The hours are 0-23 military hours.  The SparkPost Server thinks in GMT so you will want to set the offset for when you want the campaign to go out compared to GMT.
<p>You will notice that there are fields to allow for 5 metadata key value pairs to be added to the campaign.  These key value pairs will be added to the campaign and added to the web hook events generated by SparkPost.  I am guessing that 5 is enough, if not please give me feedback and/or make the changes yourself and submit them to the Github repository.
<p>Once you submit the data, the campaign will be scheduled and you will be taken to the Receipts page.
</td></tr>
<tr><td>
<h4>Receipts Page</h4>
<strong>Once</strong> you have pressed the submit button on the Campaign Generation page, the campaign is created.  This page simply shows you what you entered and what the system did with that information.  Check the bottom text closely to make sure that your campaign was scheduled. If everything went well, you will see output similar to:
<p><code>
{ "results": { "total_rejected_recipients": 0, "total_accepted_recipients": 1, "id": "102445114446875802" } }</code>. 
<p>If not, you will see some server errors that might look like:
<p><code>
{ "errors": [ { "message": "Failed to generate message", "description": "[internal] Error while rendering part html: line 64: substitution value 'dynamic_html' did not exist or was null", "code": "1901" } ], "results": { "total_rejected_recipients": 0, "total_accepted_recipients": 1, "id": "66416320824814490" } }
</code>
<p>So again, check the results closely.  If you entered in an email address in the Generation page, an email should be sent out immediately.  Keep in mind, that this system does it's work at you submit it, so there is no way for the system to send you any reminders closer to your selected launch date.
</td></tr>
<tr><td>
<h4>Scheduled Campaigns</h4>
<strong>While</strong> the Generation page does show a list off all scheduled campaigns, you need to go to the Scheduled Campaigns page to see details and to cancel them.  This page is a rather simple interface.  Copy the 'id' of the campaign you want to cancel, paste it into the field and press 'cancel campaign'.  This will cancel the campaign and refresh the page with a list of remaining campaigns.
<p><i>**Warning** Campaigns that are targeted to kick off within 24 hours can not be cancelled at this time due to SparkPost rules.  There is nothing in this codebase that stops it, so if that rule changes some day, this application will work appropriately.  If you try to cancel a campaign and it keeps showing up in the list, it is probably due to this rule!</i>
</td></tr>
</table>
</body>
</html>


