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
    <title>SparkPost Campaign Submission</title>
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
  <li><a class="active" href="#home">Home</a></li>
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
        <h1>SparkPostMail Simple UI Campaign Generator</h1>
    </center>
    <form action="SparkPostSubmit.php" id="keyform" name="keyform" onsubmit="stringToHex()">
        <h3>Your SparkPost API Key:</h3>
        <input id="key" name="apikey" placeholder="API Key.." required=true type="text" autocomplete="off">
        <br><br>
        <input size="10" style=
        "color: #FFFFFF; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #72A4D2;"
        type="submit" value="Submit">
    </form><br>
    <br>
    Note: These API Keys can be created from the one of the admin pages on your
    SparkPost account at <a href=
    "https://app.sparkpost.com/account/credentials">https://app.sparkpost.com/account/credentials</a>.<br>

    At a minimum, you need to select 'Recipient Lists: Read/Write, Templates:
    Read/Write, and Transmissions:Read/Write'.
    <center>
        <table border="0" height=100 width="50%">
            <tr>
                <td></td>
            </tr>
        </table>
    </center>
    <center>
        <table border="1" style="background-color:#F5870A" width="50%">
            <tr>
                <td>
                    This tool is free to use at your own risk and should be up
                    and running at all times but is not monitored for uptime.
                    The Campaign Generator DOES NOT hold any of your
                    information, it simply uses your API key to obtain enough
                    information in order to create, schedule or cancel your
                    campaigns. The code behind this site is available from my
                    Github repository at: <a href=
                    "https://github.com/jeff-goldstein/sparkpost/tree/master/SparkPostCampaignSubmissionCode" target="_blank">
                    https://github.com/jeff-goldstein/sparkpost/tree/master/SparkPostCampaignSubmissionCode</a>.
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
