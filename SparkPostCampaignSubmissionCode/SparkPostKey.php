<!DOCTYPE html>
<html><head></head>
<style>
#bkgrnd {
    background-image: url(https://dl.dropboxusercontent.com/u/4387255/Color-Flame-BKGR_Transparent.png), url(https://dl.dropboxusercontent.com/u/4387255/Color-Flame-BKGR_Transparent.png);
    background-position: right bottom, left top;
    background-repeat: no-repeat, repeat;
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
</style>
<body id="bkgrnd">
<h1><center> SparkPostMail Simple UI Campaign Generator </center></h1>
<form action="SparkPostSubmit.php">

<h3>Your SparkPost API Key:</h3>
  <input name="apikey" type="text" required><br><br>
<input type="submit" value="Submit" STYLE="color: #FFFFFF; font-family: Verdana; font-weight: bold; font-size: 12px; background-color: #72A4D2;" size="10" >
</form>
<br><br>
Note: These API Keys can be created from the one of the admin pages on your SparkPost account at <a href="https://app.sparkpost.com/account/credentials" >https://app.sparkpost.com/account/credentials</a>.  <br>At a minimum, you need to select 'Recipient Lists: Read/Write, Templates: Read/Write, and Transmissions:Read/Write'.
</body>
</html>
