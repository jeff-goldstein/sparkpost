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
<title>Campaign Generator for SparkPost</title>
<link href="http://bit.ly/2elb0Hw" rel="shortcut icon" type="image/vnd.microsoft.icon">
<link rel="stylesheet" type="text/css" href="cgCommonFormatting.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="cgCommonScripts.js"></script>
<style>
table {
    width: 75%;
}
</style>
</head>

<body bgcolor="#f4f5f7">
<ul class="topnav" id="generatorTopNav">
  <li><a class="active" href="cgKey.php">Home</a></li>
  <li><a href="cgHelp.php">Help</a></li>
  <li><a href="https://developers.sparkpost.com/" target="_blank">SparkPost Documentation</a></li>
  <li><a href="mailto:email.goldstein@gmail.com?subject=cgMail">Contact</a></li>
  <li class="icon">
    <a href="javascript:void(0);" style="font-size:15px;" onclick="generatorNav()">☰</a>
  </li>
</ul>
<h2>Appendix</h2>
<ul>
<li><a href="#LoggingIn">Logging in with a SparkPost API Key</a></li>
<li><a href="#StoredData">Using Stored Templates and Recipient Lists for Campaign Generation</a></li>
<center>
    <br><h1>Campaign Generator Help</h1>
</center>
<table border="0" width="90%" cellpadding="30">
<tr><td colspan="2">
<strong>Welcome</strong> to Campaign Generator.  First things first....  This tool was not created nor is it supported by SparkPost.  This tool was created by some SparkPost community contributors and is open for anyone to use.
This tool is free to use at your own risk and should be up and running at all times but is not monitored for uptime. The Campaign Generator DOES NOT hold any of your information, it simply uses your API key to obtain enough 
information in order to create, schedule or cancel your campaigns. The code behind this site is available from my Github repository at: https://github.com/jeff-goldstein/sparkpost/tree/master/SparkPostCampaignSubmissionCode. 
</td></tr>
<tr><td colspan="2">
<strong>I</strong> created this simple generation tool to fill in missing functionality from SparkPost.  Since SparkPost is geared toward developers anyone who needs to send Campaigns either had to build the functionality themselves or use one of SparkPost's partners.  So I created this tool as a quick way to either launch a campaign now or to schedule it out for a later release.
When using this tool, please understand the nuances of SparkPost because I won't document them in this tool.  For example as of Sept 2016, campaigns can only be cancelled if they are scheduled for a time more than one day out.  I'm not going to document all of these nuances and will let SparkPost documentation speak for itself; so if something isn't exactly working
the way you expected it to work, please check their documentation first at, 
<a href="https://developers.sparkpost.com/">SparkPost Documentation</a>
</td></tr>

<tr><td valign="top">
<a name="LoggingIn">
<h4>Logging in with a SparkPost API Key</h4>
<strong>The</strong> first step in using the generator is to obtain a SparkPost API key which will give this application access to your account.  As stated before this key is NOT stored by this application and is only used during your sessions.  If you don't have an API Key and need some help in generating one, here is a SparkPost article on the subject: <a href="https://support.sparkpost.com/customer/portal/articles/1933377">Create API Keys.</a>
<p>
Once you have the key, you can use it on the main page to start the campaign process.  In order to protect your key, the system will then obfuscate your key through the rest of the session.  Also, I have turned off autofill for the field that requests the API key, so the browser probably won't fill in that field the next time you come back to that page.  I state this because the SparkPost API generator does not hold the key either so if you loose that key you will have to create another one.

The only other field on this page is the API Root directory.  They system <strong>defaults</strong> to the SparkPost.com API Root directory <strong>(https://api.sparkpost.com/api/v1)</strong>, but if you are using this application onsite after downloading it from Github, or you are an Elite/Enterprise customer, you need to enter in the appropriate URL. The system doesn't care if you have a trailing '/' after 'v1' or not; it doesn't need/want it, but will strip it off if you put it into the field. 
</td>
<td valign="top"><a href="https://dl.dropboxusercontent.com/u/4387255/cgScreenSnapshots/cgKey.png" target="_blank"><img src="https://dl.dropboxusercontent.com/u/4387255/cgScreenSnapshots/cgKey.png" alt="Campaign Generator Login Page" width="800" height=800"></img></a>
</td></tr>

<tr><td valign="top">
<h4>Using Stored Templates and Recipient Lists for Campaign Generation</h4>
<a name="StoredData">
<strong>Here</strong> is one of two ways to submit a campaign.  This approach uses the API Key to call the SparkPost server to obtain a list of your published templates and all 
recipient lists that have been uploaded to the system.  You must select which ones you are interested in using for this campaign.  <a name="validate">If you want to check to see
that you have a good match, press the 'Preview & Validate' button directly below the Recipient list.  At this time, the system will do several things:
<ol>
<br><li>The Campaign Generator will grab the substitution data from the first recipient in the selected recipient list.</li>
<br><li>Display what the email will look with the users data.  Please look at the preview content closely to see if it looks like the data is filling out the template correctly.</li>
<br>
<li>The Campaign Generator will compare how the data and the template matches up in two different ways (see the lower table to the right of the Campaign Submission Form):
<ul>
<br><li>The first approach is to compare the fields in the substitution data against what fields the template is using.  For each substitution field, the system will scan the template looking for it's usage.  In the summary section, you will see a break out for several different counts:
<ul><br>
<li>How many fields were found in the substitution data</li>
<li>How many substitution fields match template fields</li>
<li>How many fields did not match</li>
<li>How many fields did not match, but were probably index fields within the data and would not expect to be found in the template.  For example; the template is used to display 'x' number of products, where the number of products can change each time. The products could be indexed in either the global substitution (or recipeint) data section in the following manner:
<pre>
"products" : {
    "17823": {
            "url": "https://db.tt/emZu6fd2",
            "height" : "720",
            "width" : "1420",
            "price" : "$355.00",
            "reduction" : "$10",
            "title": "ALPS Mountaineering Hiking Shoes",
            "owner" : "",
            "category" : "Outdoor",
            "image": "https://db.tt/emZu6fd2"
        },
        "18056": {
            "url": "https://db.tt/4tZJ4G0J",
            "height" : "952",
            "width" : "1200",
            "price" : "$199.99 and Up",
            "reduction" : "Up To 62%",
            "owner" : "EggWedge",
            "category" : "Outdoor",
            "title": "Hike the World With Blue Lake",
            "image": "https://db.tt/4tZJ4G0J"
        },
        "18111": {
            "url": "https://db.tt/w97FMz9w",
            "height" : "952",
            "width" : "1200",
            "price" : "$259.00",
            "reduction" : "Up To 65% Off",
            "category" : "Outdoor",
            "title": "One Man's Hotel...Live Under the Stars",
            "image": "https://db.tt/w97FMz9w"
        },
        "18152": {
            "url": "https://db.tt/JEPmoIyG",
            "height" : "952",
            "width" : "1200",
            "price" : "$295.00",
            "reduction" : "Up to 60% Off",
            "owner" : "Stanza",
            "category" : "Research",
            "title": "Easy Six Course Meals",
            "image": "https://db.tt/JEPmoIyG"
        },
</pre> 
The numeric indexed products can then be used in a myriad number of ways.  One example would be in a loop where the recipients data would have an array of which products to display: "products": ["18152", "18056"], while another users recipient data might have "products": ["17823", "18056", "18111" , "18152"].  Because the fields are solely being used within the data for reference we wouldn't expect to see them referenced in the data.</li>
<br><li>The last number represents what I call 'system indexes'.  I plan on getting rid of those in the near future, but due to the language I'm using for some of the back end work, the system add's array index numbers to my substitution fields when I make an array out of the for usage (I know...blah, blah, blah).  Anyway, any numberic substitution field under 50 is going to be labeled as a probably system index and the row will be displayed in 'yellow highlight' color.</li></ul>
<br><li>The second appoach is the reverse of the first.  The Campaign Generator scans for all variables being used in the template and checks to see if there is matching data.  The summary is much simplier since it doesn't have to deal with the indexed products or system index numbers.  So the summary simply shows: 
<ul>
<li>How many fields were found in the template</li>
<li>How many matches were found in the substitution data</li>
<li>How many fields did not find a match in the substitition data</li>
</ul></ul>
<br>We haven't discussed how to add in 'global substitution data' into the system just yet, but <strong>YES</strong>, the system does compare both recipeint and global data to the template.
<br><br><li>If there is an error message from the server when it tries to create the preview, the system will warn you in three different ways:
<ul>
<br><li>An Alert Box will display telling you of an issue</li>
<li>The error will display in the Preview Section and possibly in the area that compares the substitution and template fields</li>
<li>The submit button will turn off and turn red.  You will not be able to submit a campaign until you either press the reset button, or you use the Preview button on a good Template/Recipient combination.</li>
</ul></ol>

<p>Next to the 'Preview and Validate' button are two other buttons.  The 'Show Global Sub' button exposes a new data entry field that allows the user to enter in.....you guessed, <em>Global Substitution data</em>.  It is very very very important that you enter in this data properly.  The field does have a sample in it that will only display when there is no data in that field; so as-soon-as you start to type data, it will dissapear.  The expected format is a json structure with the key name "substitution_data" and the value(s) following it.  It must be a proper json structure without the curly bracket before the substitution_data key word.  Here is a 'simple' example:
<pre>
"substitution_data" : 
{
     "company_home_url" : "www.sparkpost.com",
     "company_logo" : "https://db.tt/lRplbEmw",
     "logo_height" : "20",
     "logo_width" : "75",
     "header_color" : "#cc6600",
     "header_box_color" : "#fff4ea",
     "order_generic_comments" : "Many thanks for your phone order!",
     "company" : "Slippery Bikes"
}
</pre>
<p>The substitution data can be as complex as you need, but does have a length limit of 70K characters.  As explained before, this data will be used in the preview and the data comparison features discussed earlier.  Notice that this field is expandable by pulling on the lower right-hand corner of the box.  This is extremely useful in helping you see what you have entered.
<p>The next button is the 'Send Test Email' button that allows you to send a test email to a comma seperated list of email addresses.  It uses the same data that the preview is using, so those emails should be identical.  
<p>Now you can pick when you want the campaign to go out.  You can either have it go out immediately, or you can schedule it out.  Currently, SparkPost will reject any scheduled campaign farther out than a month.  If you enter in a scheduled date/time, that will take precedence over the 'now' flag.  The hours are 0-23 military hours.  The SparkPost Server thinks in GMT so you will want to set the offset for when you want the campaign to go out compared to GMT.
<p>You will notice that there are fields to allow for 5 metadata key value pairs to be added to the campaign.  These key value pairs will be added to the campaign which in turn are added to the web hook events generated by SparkPost.  I am guessing that 5 is enough, if not please give me feedback and/or make the changes yourself and submit them to the Github repository.
<p><i>If you are using ip_pools or bindings, you can use meta data to guide your emails to the proper location.  For example, Enterprise users may have a meta data field called "binding" and the data would be "outbound".<i>
<p>Once you submit the data, the campaign will be scheduled and you will be taken to the Receipts page.
</td>
<td valign="top"><a href="https://dl.dropboxusercontent.com/u/4387255/cgScreenSnapshots/cgBuildSubmitStored.png" target="_blank"><img src="https://dl.dropboxusercontent.com/u/4387255/cgScreenSnapshots/cgBuildSubmitStored.png" alt="Campaign Generator Login Page" width="800" height=800"></img></a>
</td></tr>

<tr><td valign="top">
<h4>Using CSV or JSON data for Campaign Generation</h4>
<a href="CSVJSONBuild"></a>
<strong>The</strong> second approach to submitting a campaign is very similar to the previous one (please refer to <a href="#StoredData">Using Stored Templates and Recipient Lists for Campaign Generation)</a> except that the recipient data is entered by you.  While this is a lot more flexible, it can be more challenging to get the data correct. There are three large data entry fields; CSV and Recipients and Global Substitution Data fields. The Global field is initially hidden and can be displayed by pressing the 'Show Global Sub' button.  The text on that button will toggle from 'show' or 'hide' depending on if the text field is being displayed or not.
<p>The CSV field expects a typical csv list of data.  The only field that MUST be in the list is the "address" field; all others are optional. Once entered, press the 'Convert to JSON' button in order to change your CSV file into something the server can understand. The first are the names of the fields, seperated by commas and incased in quotes.  If you have substitution data, you must have a field name "substitution" and all data following it on the row will be concidered substitution_data fields.
Here is an example:
<p>  
<pre>
"address","UserName","substitution","first","id","city"
"jeff.goldstein@sparkpost.com","Sam Smith","_","Sam",342,"Princeton"
"austein@hotmail.com","Fred Frankly","_","Fred",38232,"Nowhere"
"jeff@geekswithapersonality.com","Zachary Zupers","_","Zack",9,"Hidden Town"
</pre>
<p>
<strong>In each data row, use an underscore as the data for the "substitution" field . IT MUST BE AN UNDERSCORE!! The system will use the combination of the substitution/underscore to determin the begining of the substitution data.</strong> The above sample will change to the following JSON format which will be displayed in the Recipient data entry field (if you have data in the Recipient data field prior to hitting the 'Convert to JSON' button, that data will be overriden):
<p>
<table border=0><tr><td>
{"recipients":[{"address":"jeff.goldstein@sparkpost.com","UserName":"Sam Smith","substitution_data":{"first":"Sam","id":"342","city":"Princeton"}},
{"address":"austein@hotmail.com","UserName":"Fred Frankly","substitution_data":{"first":"Fred","id":"38232","city":"Nowhere"}},
{"address":"jeff@geekswithapersonality.com","UserName":"Zachary Zu
</td></tr></table>
<p>Yes that is ugly; so by using a json formatter (<a href="http://jsonlint.com/#">JSONLINT</a>), you can see what the output looks like in human readable form.  Notice that the dash does not show up in your data:
<pre>
{
 "recipients": [{
  "address": "jeff.goldstein@sparkpost.com",
  "UserName": "Sam Smith",
  "substitution_data": {
   "first": "Sam",
   "id": "342",
   "city": "Princeton"
  }
 }, {
  "address": "austein@hotmail.com",
  "UserName": "Fred Frankly",
  "substitution_data": {
   "first": "Fred",
   "id": "38232",
   "city": "Nowhere"
  }
 }, {
  "address": "jeff@geekswithapersonality.com",
  "UserName": "Zachary Zupers",
  "substitution_data": {
   "first": "Zack",
   "id": "9",
   "city": "Hidden Town"
  }
 }]
}
</pre>

<p><em>NOTE: JSONLINT is a great tool.  There is also a PRO version: <a href="http://pro.jsonlint.com/">PRo JSONLINT</a>. JSON LINT is open source GitHub repository availalbe.  
These tools can save you a ton of time in figuring out why your JSON is being rejected.</em>
<p>Once you have your CSV converted to JSON, you can go ahead and leverage the "Preview and Validate" process to see how the email may look and how well the data and the template match.  For more information on the "Preview and Validate" process, please refer to the <a href="#validate">Validate Process Description</a> given above.  (I'm to lazy to upkeep both places :-). 

<p>If you wish, you can skip the CSV process and enter (cut/paste) JSON directly into the Recipient data field, go right ahead.  There is a sample of what the structure needs to look like in the data field itself.  The field is expected to be a good JSON format and can have as many users in it as you with up to 700K of data.  Depending on the number of substitution fields you have that could be a lot of users!!  

<p>The Global data field works the same way as it does on the Campaign Generation Process that uses stored templates and recipient lists.  Please refer to that text for further information.

<p>The only other difference between this process and using stored information is that you may need to enter a 'global return path'.  This is only needed for Enterprise/Elite users.  The system will automatically get a list of validated domains that are available for your API Key. Enter in the name you want to use and the system will combine the two into a proper email address.

<p>A subtle difference between this Campaign Generation process and the one that uses stored recipients is that we don't have a 'Send Test Email' button on this page.  The reason for that is because you can enter in a set of users to get test emails by placing them in the Recipient data field.  By taking this approach, you can send different data sets to different people so you can really see how your template is performing.  Unlike the stored recipient approach that only uses the data from the first recipient, this will send to everyone in the recipient data field. So you if you want 10 people to get a test email; have 10 recipients along with their individual data and submit the campaign just like you would the real campaign. 
</td>
<td valign="top"><a href="https://dl.dropboxusercontent.com/u/4387255/cgScreenSnapshots/cgBuildSubmitCSVJSON.png" target="_blank"><img src="https://dl.dropboxusercontent.com/u/4387255/cgScreenSnapshots/cgBuildSubmitCSVJSON.png" alt="Campaign Generator Login Page" width="800" height=800"></img></a>
</td></tr>

<tr><td valign="top">
<h4>Commit Page</h4>
<a href="Receipts"></a>
<strong>Once</strong> you have pressed the submit button on the Campaign Generation page, the campaign is ready for one final review before submission.  If everything looks good, hit the "Press to Send/Schedule Campaign' button at the bottom of the page.  Once pressed, a box will appear that will reflect the output from the server. Check the text closely to make sure that your campaign was scheduled or sent. If everything went well, you will see output similar to:
<p><code>
{ "results": { "total_rejected_recipients": 0, "total_accepted_recipients": 1, "id": "102445114446875802" } }</code>. 
<p>If not, you will see some server errors that might look like:
<p><code>
{ "errors": [ { "message": "Failed to generate message", "description": "[internal] Error while rendering part html: line 64: substitution value 'dynamic_html' did not exist or was null", "code": "1901" } ], "results": { "total_rejected_recipients": 0, "total_accepted_recipients": 1, "id": "66416320824814490" } }
</code>
<p>As a precaution, the Schedule Campaign Button will change text and color to reflect that the campaign was submitted to the server and it either worked or didn't.  So again, check the results closely.  If you entered in an email address in the Generation page, an email should be sent out immediately. 
</td> 
<td valign="top"><a href="https://dl.dropboxusercontent.com/u/4387255/cgScreenSnapshots/cgConfirmSubmission.png" target="_blank"><img src="https://dl.dropboxusercontent.com/u/4387255/cgScreenSnapshots/cgConfirmSubmission.png" alt="Campaign Generator Login Page" width="800" height=800"></img></a>
</td></tr>

<tr><td valign="top">
<h4>Scheduled Campaigns</h4>
<a href="Scheduled"></a>
<strong>While</strong> the Generation page does show a list off all scheduled campaigns, you need to go to the Scheduled Campaigns page to see details and to cancel them.  This page is a rather simple interface.  Copy the 'id' of the campaign you want to cancel, paste it into the field and press 'cancel campaign'.  This will cancel the campaign and refresh the page with a list of remaining campaigns.
<p><i>**Warning** Campaigns that are targeted to kick off within 24 hours can not be cancelled at this time due to SparkPost rules.  There is nothing in this codebase that stops it, so if that rule changes some day, this application will work appropriately.  If you try to cancel a campaign and it keeps showing up in the list, it is probably due to this rule!</i>
</td>
<td valign="top"><a href="https://dl.dropboxusercontent.com/u/4387255/cgScreenSnapshots/cgScheduled.png" target="_blank"><img src="https://dl.dropboxusercontent.com/u/4387255/cgScreenSnapshots/cgScheduled.png"</tr>
</table>
</body>
</html>


