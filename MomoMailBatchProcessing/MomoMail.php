<?php
ini_set('memory_limit', '4096M');
date_default_timezone_set('America/Los_Angeles');
//Copyright  2015 SparkPost

//Licensed under the Apache License, Version 2.0 (the "License");
//you may not use this file except in compliance with the License.
//You may obtain a copy of the License at
//
//    http://www.apache.org/licenses/LICENSE-2.0
//
//Unless required by applicable law or agreed to in writing, software
//distributed under the License is distributed on an "AS IS" BASIS,
//WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//See the License for the specific language governing permissions and
//limitations under the License.


//
// Author: Jeff Goldstein (June 2015)
//
// Input Params (paramters.ini), all required:
//    # 1.  BaseURL           		        - URL for Web API....DO NOT INCLUDE ANYTHING PAST VERSION INDICATION
//    # 2.  Authorization                   - Web API Authorization Key
//    # 3.  ProcessedFilesDirectory         - Location to archive processed files
//    # 4.  LogFile                         - Name of logging file 
//    # 5.  MaxInputFileSize                - Largest size of input file allowed to be processed
//    # 6.  AdminEmail                      - Email address for errors and warnings
//    # 7.  ErrorTemplate                   - Template used for emailing errors and warnings
//    # 8.  MomoMailDirectory               - Location process will look for files to be processed
//    # 9.  Postfix                         - Only files with this .<postfix> will be processed
//    # 10. ErrorCampaignName               - Used for reporting (both UI and Web Hooks)
//    # 11. Test                            - When set to "On", processed files are not archived or renamed; allows for easier repeat testing
//    # 12. ArchiveWarningSize              - When the 'ProcessedFilesDirectory' reaches this size, a warning email will be sent to the admin
//    # 13. FileTrackerToggle               - When set to "On", repeat file names cannot be processed
//    # 14. TrackerFile                     - Name of file containing list of already processed files
//    # 15. FileTrackerOffset               - How many of the last processed files to track
//    # 16. ValidateProcessedToggle         - When set to "On" the field name for the paramter "ExpectedNumberofItems" will be used to compare
//                                            how many items were processed compared to how many were expected to be processed. Default is off.
//    # 17. ExpectedFieldName               - This is the Field Name in the json file that contains the expected number of items

// TODO LIST
//   Look at allowing for updates for Recipient lists when it's available
//  

// Assumptions
//   


//
// Versioning 
// - 
// - 
// - 
// - 

//
//  Hardcoded items to watch for if making additions or changing for your environment
//
// ini_set (memory_limit) at top of application.  This is set because the json_decode used to validate the input file takes a lot of memory
//    If you need to save memory, you can skip that check and throw caution to the wind.
// $parametersFile - hardcoded to parameters.ini
// $cat.  The first usage of this variable obtains the 'global' category in the parameters.ini
//    After that, the first CLI parameter is used to set the $cat variable in order to obtain specific category input parameters.

//
// Grab the paramters passed to the program
$bulkType = $argv[1];
$update = $argv[2];

//
// bulkType must match the categories in the parameters file and used throughout the program
if ((strcasecmp($bulkType, "Help") == 0) or $bulkType == NULL or (($bulkType != "transmission") and ($bulkType != "recipient") and ($bulkType != "template")))
{
  echo "\nNAME\n";
  echo "  momomail.php -  Batch processing application that acts on behalf of back office application(s) and\n";
  echo "                  thier Momentum/Sparkpost.com/Sparkpost Elite MTA platforms.\n";
  echo "SYNOPSIS\n";
  echo "  php momobulk.php transmission|recipient|template [-u|update]\n\n";
  echo "DESCRIPTION\n";
  echo "  MomoBulk simplifies the bulk work of sending emails, updating/adding templates and adding recipient lists.\n";
  echo "  This application will only do one type of bulk work at a time and is driven by two arguments that are obtained when the application is executed.\n";
  echo "  The first parameter identifies the type of work expected, while the second parameter tells the system if the upload should be treated\n";
  echo "  as an 'add/post' or an 'update/put' call.  Currently, the second parameter is only used for the 'template' calls.\n";
  echo "  While not manditory, it is expected that this process will be automatically run via a process like cron or something similar.\n";
  echo "  For example: >> crontab -e\n";
  echo "                  * * * * * /opt/msys/3rdParty/bin/php /root/momomail.php transmission\n";
  echo "  will run the processes once every minute.  If you want to run multiple processes similtaneously, you need multiple entries.";
  echo "\n";
  echo "PARAMETERS\n";
  echo "o First parameter tells the system what kind of work is being requested.  It must be one of the entries, 'transmission', 'recipient' or 'template'\n";
  echo "o The second paramater is optional and only used for 'template' bulk work.  Any \n";
  exit;
} 

// Start Initialization Section
//
// Let's use the 'microtime' as the process identifier.  In order to support parallel processing
// a file that is just about to be processed will be renamed so other processes will not try to process that
// file.  We will add the microtime to the end of the file name.
$process_identifier = microtime(true);

$DebugText = "\n\n******************************************************************";
$DebugText .= "\nStarting New MomoMail Process: " . date(DATE_RFC822) . "\n";
$DebugText .= "Processing Type: " . $bulkType . "\n";
$DebugText .= "Processing Identifier: " . $process_identifier . " " . $update . "\n";

//Get the 'global' parameters
$parametersFile = "parameters.ini";
$cat = "global";
$paramonly_array = parse_ini_file( $parametersFile, true );

//
// Can't really default these parameters
//
$baseURL = $paramonly_array[$cat]["BaseURL"];
$auth = "Authorization: " . $paramonly_array[$cat]["Authorization"];
$admin_email = $paramonly_array[$cat]["AdminEmail"];
//
// Global, with defaults
//
if ($paramonly_array[$cat]["Test"]) $testToggle = $paramonly_array[$cat]["Test"]; else $testToggle = "Off";
if ($paramonly_array[$cat]["MaxInputFileSize"]) $maxSize = $paramonly_array[$cat]["MaxInputFileSize"]; else $maxSize = 25000000;  //defaulting on the cautious side
if ($paramonly_array[$cat]["ArchiveWarningSize"]) $archiveWarningSize = $paramonly_array[$cat]["ArchiveWarningSize"]; else $archiveWarningSize = 50000000000; //defaulting to 50gig

$cat = $bulkType;
$paramonly_array = parse_ini_file( $parametersFile, true );
//
// Default these parameters
// Some of these parameters exist for certain categories while other don't.  Creating ugly logic around that, we will simple get the setting and/or default
//
if ($paramonly_array[$cat]["ProcessedFilesDirectory"]) $processedDirectoryLocation = $paramonly_array[$cat]["ProcessedFilesDirectory"]; else $processedDirectoryLocation = "Archive";
if ($paramonly_array[$cat]["LogFile"]) $log = $paramonly_array[$cat]["LogFile"]; else $log = "processed.log";
if ($paramonly_array[$cat]["ErrorTemplate"]) $errorTemplate = $paramonly_array[$cat]["ErrorTemplate"]; else $errorTemplate = "errorTemplate";
if ($paramonly_array[$cat]["MomoInputDirectory"]) $momoInputDirectory = $paramonly_array[$cat]["MomoInputDirectory"]; else $momoInputDirectory = "MomoMailInput";
if ($paramonly_array[$cat]["Postfix"]) $postfix = $paramonly_array[$cat]["Postfix"]; else $postfix = "json";
if ($paramonly_array[$cat]["ErrorCampaignName"]) $campaignName = $paramonly_array[$cat]["ErrorCampaignName"]; else $campaignName = "MomoMail Error";
if ($paramonly_array[$cat]["FileTrackerToggle"]) $fileTrackerToggle = $paramonly_array[$cat]["FileTrackerToggle"]; else $fileTrackerToggle = "On";
if ($paramonly_array[$cat]["FileTrackerOffset"]) $offset = $paramonly_array[$cat]["FileTrackerOffset"]; else $offset = 1000;
if ($paramonly_array[$cat]["TrackerFile"]) $fileTracker = $paramonly_array[$cat]["TrackerFile"]; else $fileTracker = "processedtracker.log";
if ($paramonly_array[$cat]["ValidateProcessedToggle"]) $validateProcessed = $paramonly_array[$cat]["ValidateProcessedToggle"]; else $validateProcessed = "Off";
if ($paramonly_array[$cat]["ExpectedFieldName"]) $processedField = $paramonly_array[$cat]["ExpectedFieldName"]; else $processedField = "expected";

$momoMailInputfiles = $momoInputDirectory . "/*." . $postfix;
// This is the string that will be looked for in the transmisison file.  If a template id is included in the transmission file, then the app will
// validate it's existance before submitting to the 'tranmsissions' API. 
$templateID = "template_id";       

if ($fileTrackerToggle == "On")
{
   if (!file_exists($fileTracker))
   {
      $nothing = "";
      file_put_contents ($fileTracker, $nothing, FILE_APPEND | LOCK_EX );
   }   
   $previouslyProcessed = file_get_contents($fileTracker);
}

//
// Strings hardcoded to match API names
switch ($bulkType)
{
  case "transmission":
     $APIName = $baseURL . "transmissions";
     break;
  case "recipient":
     $APIName = $baseURL . "recipient-lists";
     break;  
  case "template":
     $APIName = $baseURL . "templates";
     break;
}

$errormail_ch = curl_init();
curl_setopt($errormail_ch, CURLOPT_URL, $baseURL . "transmissions");
curl_setopt($errormail_ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($errormail_ch, CURLOPT_HEADER, FALSE);
curl_setopt($errormail_ch, CURLOPT_POST, TRUE);

$batch_ch = curl_init();
if (($bulkType == "template") and (($update == "u") or ($update == "update")))
  curl_setopt($batch_ch, CURLOPT_CUSTOMREQUEST, "PUT");
else
  curl_setopt($batch_ch, CURLOPT_POST, TRUE);

curl_setopt($batch_ch, CURLOPT_URL, $APIName);
curl_setopt($batch_ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($batch_ch, CURLOPT_HEADER, FALSE);


// Create the directory for Processed transmission files
// This directory only gets files that did NOT return any errors
if (!file_exists($processedDirectoryLocation)) 
{
  mkdir($processedDirectoryLocation, 0777, true);
}
if (!file_exists($processedDirectoryLocation)) 
{
  //something won't let me create the archive directory of good transmissions
  $DebugText .= "Can't create the backup directory; exiting";
  file_put_contents ($log, $DebugText, FILE_APPEND | LOCK_EX );
  sendErrorMessageEmail ($errormail_ch, $DebugText, $DebugText, $file);
  exit();
}

$archivesize = folderSize($processedDirectoryLocation);
if ($archivesize > $archiveWarningSize)
{
   $subject = "Warning, Archive Folder Reached Warning Limit";
   $textBody = $subject;
   sendErrorMessageEmail ($errormail_ch, $subject, $textBody, $processedDirectoryLocation);
   $DebugText .= "\n" . date(DATE_RFC822) . "\nArchive Processing Folder at Warning Level: " . $archiveWarningSize . "\n End Transmssion Response \n";
}

//
// End Initialization Section


function sendErrorMessageEmail ($ch, $subject, $error, $file)
// Send error detail email to admin
{
  global $auth, $errorTemplate, $campaignName, $admin_email;
  $errorText = file_get_contents($errorTemplate);
  $errorTextBody = str_replace("##error##", $error, $errorText);
  $small = substr($response, 0, 75);
  $errorTextBody = str_replace("##subject##", $subject, $errorTextBody);
  $errorTextBody = str_replace("##file##", $file, $errorTextBody);
  $errorTextBody = str_replace("##campaign##", $campaignName, $errorTextBody);
  $errorTextBody = str_replace("##admin##", $admin_email, $errorTextBody);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $errorTextBody);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $auth));
  $response = curl_exec($ch);
}

function getTransactionID ($results)
// Break out the Transaction ID from the results
{
  $obj = json_decode($results);
  $results = $obj->{'results'};
  return $results->{'id'};          
}

function validate_template_existence ($body)
// If a template is being used, is it actaully up on the server?
{
  global $auth, $template_ch, $baseURL, $template;
  $template_ch = curl_init();
  curl_setopt($template_ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($template_ch, CURLOPT_HEADER, FALSE);
  curl_setopt($template_ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $auth));
  
  if ($template == NULL) 
    return "Embedded";  // No template, the content is embedded
  else
  {
    $url .= $baseURL . "templates/" . $template;
    curl_setopt($template_ch, CURLOPT_URL, $url);
    $response = curl_exec($template_ch);
    $http_status_code = curl_getinfo($template_ch, CURLINFO_HTTP_CODE);
    curl_close($template_ch);
    if ($http_status_code == 200)
       return "TemplateOK";
    else
       return $template;
  }
}

function folderSize($dir)
// This is used when validating the archive folder isn't getting to big
// Used code found at http://www.a2zwebhelp.com/folder-size-php...
{
  $count_size = 0;
  $count = 0;
  $dir_array = scandir($dir);
  foreach($dir_array as $key=>$filename)
  {
    if($filename != ".." && $filename != ".")
    {
      if(is_dir($dir . "/" . $filename))
      {
        $new_foldersize = foldersize($dir . "/" . $filename);
        $count_size = $count_size + $new_foldersize;
      }
      else if(is_file($dir."/".$filename))
      {
        $count_size = $count_size + filesize($dir."/".$filename);
        $count++;
      }
    }
  }
  return $count_size;
} 

function previouslyProcessed_check ($file, $errormail_ch)
// Checking that the file hasn't already been processed
{
  global $testToggle, $DebugText, $fileTrackerToggle, $previouslyProcessed, $fullname;
  
  if (($fileTrackerToggle == "On") and (0 < substr_count($previouslyProcessed, $file)))
  {
    $textBody = "\nTransmissions File: " . $file . " has already been processed.";
    $textBody .= "\nThis file will be skipped and renamed with a postfix of ..alreadyprocessed..";
    $DebugText .= "\n" . date(DATE_RFC822) . $textBody . "\n";
    $textBody = str_replace("\n", "  ", $textBody);
    $textBody = '"' . $textBody . '"';
    $fullname .=  ".alreadyprocessed";
    $subject = "Duplicate Transmission File Found";
    
    sendErrorMessageEmail ($errormail_ch, $subject, $textBody, $file);
    return true;
  }
  return false;
}
 
function toBigtoProcess_check ($file, $fileid, $errormail_ch)
// Checking that the file isn't to big to process and will crash the program.
// If more memory is needed; this program is using ini_set('memory_limit', ...) at the top of the program
{
  global $maxSize, $testToggle, $DebugText, $fullname;

  if (filesize ($fileid) > $maxSize)
  {
    $textBody = "\nTransmissions File: " . $file . " is larger than the set limit of " . $maxSize . " bytes";
    $textBody .= "\nThis file will be skipped and renamed with a postfix of ..filetobig..";
    $textBody .= "\nTo change the size limit please change the 'MaxTransmissionFileSize' parameter within the 'paramters.ini' file.";
    $textBody .= "\n\nPlease keep in mind, this limit must correspond to what the application memory will allow.";
    $DebugText .= "\n" . date(DATE_RFC822) . $textBody . "\n";
    $textBody = str_replace("\n", "  ", $textBody);
    $textBody = '"' . $textBody . '"';
    $fullname .=  ".filetobig";
    $subject = "Transmission File to Large";
    
    sendErrorMessageEmail ($errormail_ch, $subject, $textBody, $file);
    return true;
  }
  return false;
}

function properly_formatted_check ($file, $errormail_ch, $body)
// Check to make sure this is properly formatted json.
//
{
  global $json_check_result, $testToggle, $DebugText, $fullname;
  $results = isJson ($body);
  if ($results != 1 )  // a return of 1 is good, all others are errors
  {
    $textBody = "\nTransmissions File: " . $file . " failed the JSON format validation check.";
    $textBody .= "\nThe following validation check was obtained before submitting the transaction file: " . $json_check_result . ".";
    $textBody .= "\nThis file will be skipped and renamed with a postfix of ..corrupt..";
    $DebugText .= "\n" . date(DATE_RFC822) . $textBody . "\n";
    $textBody = str_replace("\n", "  ", $textBody);
    $fullname .=  ".corrupt";
    $subject = "Json file corrupt" . $json_check_result;
    $textBody = '"' . $textBody . '"';
    sendErrorMessageEmail ($errormail_ch, $subject, $textBody, $file);
    return true;
  }
  return false;
}

function stored_template_missing_check ($file, $errormail_ch, $body)
// Check to see if they are requesting a stored template; if so, make sure it exists.
{
  global $testToggle, $DebugText, $template, $fullname;
  $answer = validate_template_existence($body);
  if ($answer != "TemplateOK" and $answer != "Embedded")
  {
    $subject = "Requested Template: " . $answer . ", not found on server";
    $textBody = "\nTransmissions File: " . $file . ".\n" . $subject . ".";
    $textBody .= "\nThis file will be skipped and renamed with a postfix of ..badtemplatename...";
    $DebugText .= "\n" . date(DATE_RFC822) . $textBody . "\n";
    $textBody = str_replace("\n", "  ", $textBody);
    $textBody = '"' . $textBody . '"';
    sendErrorMessageEmail ($errormail_ch, $subject, $textBody, $file);
    $fullname .=  ".badtemplatename";
    //if ($testToggle != "On") rename ( $fullname, $backupFile );
    return true;
  }
  return false;
}


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
            $json_check_result = ' Underflow or the modes mismatch';
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

function number_of_transaction_entries($archiveFileLog)
// Count the number of previous processed files are in the log.  We need this in order
// To manage the size of this log over time.
{  
   $linecount = 0;
   $handle = fopen($archiveFileLog, "r");
   while(!feof($handle))
   {
      $line = fgets($handle);
      $linecount++;
   }
   fclose($handle);
   return $linecount;
}

function truncate_file_log ($archiveFileLog)
// This is a LIFO cleaning processes.  We only need to validate replicated processing
// on the last 'x' number of files.  The LIFO file size is set in the parameters file.
{
   global $offset;
   $linecount = number_of_transaction_entries($archiveFileLog);
   if ($linecount > $offset)
   {
     $cut = $offset - $linecount;
     $file = file($archiveFileLog);
     $pig = array_splice($file, $cut);
     file_put_contents ($archiveFileLog, $pig, LOCK_EX );
   }
}

function get_fields($body)
// In order to decode the body of the json only once, we get all the fields we want in one pass
{
   global $processedField, $templateID, $template, $bulkType;
   
   if ($bulkType == "transmission" or $bulkType == "recipient")
   {
     $obj = json_decode($body);
     $content = $obj->{'content'};
     $template = $content->{$templateID};
     $expected = $obj->{$processedField};
     $obj = NULL;
     return $expected;
    }
    if ($bulkType == "template")
    {
      $obj = json_decode($body);
      $template = $obj->{id};
      $obj = NULL;
      return 0;
    }
}

function validate_transaction_count($body, $expected, $file, $errormail_ch)
{
  global $testToggle, $DebugText, $fileTrackerToggle, $fullname;
  $obj = json_decode($body);
  $results = $obj->{'results'};
  $rejected = $results->{'total_rejected_recipients'};
  $accepted = $results->{'total_accepted_recipients'};
  $total = $rejected + $accepted;
  if ($total <> $expected)
  {
    $textBody = "\nTransmissions File: " . $file . " processing error";
    $textBody .= "\nThe total items rejected (" . $rejected . ") and accepted (" . $accepted . ") do NOT match the expected (" . $expected . ") amount.";
    $textBody .= "\nThis file will not be archived but renamed with a postfix of ..unexpectedmismatch..";
    $DebugText .= "\n" . date(DATE_RFC822) . $textBody . "\n";
    $textBody = str_replace("\n", "  ", $textBody);
    $textBody = '"' . $textBody . '"';
    $fullname = ".unexpectedmismatch";
    $subject = "Incorrect number of items processed";
    
    sendErrorMessageEmail ($errormail_ch, $subject, $textBody, $file);
    return false;
  }
  return true;
}

// Start of Main Code
//
// Grab a list of each file and process
$files = glob($momoMailInputfiles, GLOB_BRACE);
foreach ($files as $file)
{
  // In order to make this process more parallel processing friendly, we will validate the file still needs processing
  // If the file has been grabbed by another process, we simply skip it.  No harm no fowl.
  if (file_exists($file))
  {
    // File hasn't been processed yet, lets grab it by renaming it with our process ID at the end  
    $fullname = NULL;
    $fileid = $file . "." . $process_identifier;
    rename ($file, $fileid);  // Now the file is ours for processing
    $template = NULL; 
    // Now we are getting down to API specific checks
    switch ($bulkType)
    {
      case "transmission":
        // As a good neighbor, I want to do all checks.
        // Each error will add a postfix to the name to help the mail admin see what is going on with that file
        $previouslyProcessed_error_found = previouslyProcessed_check ( $file, $errormail_ch );
        $toBigtoProcess_error_found = toBigtoProcess_check ( $file, $fileid, $errormail_ch );  
        $body = file_get_contents($fileid);
        $expected = get_fields($body);
        $improperlyFormatted_error_found = properly_formatted_check ( $file, $errormail_ch, $body);
        $templateMissingerror_found = stored_template_missing_check ( $file, $errormail_ch, $body);
        if ($previouslyProcessed_error_found or $toBigtoProcess_error_found or $improperlyFormatted_error_found or $templateMissingerror_found)
          $transmit = false;
        else
          $transmit = true;
        break;
      case "recipient":
        $previouslyProcessed_error_found = previouslyProcessed_check ( $file, $errormail_ch );
        $toBigtoProcess_error_found = toBigtoProcess_check ( $file, $fileid, $errormail_ch );  
        $body = file_get_contents($fileid);
        $expected = get_fields($body);
        //$improperlyFormatted_error_found = properly_formatted_check ( $file, $errormail_ch, $body);
        $improperlyFormatted_error_found = false;
        if ($previouslyProcessed_error_found or $toBigtoProcess_error_found or $improperlyFormatted_error_found)
          $transmit = false;
        else
          $transmit = true;
        break;
      case "template":
        $previouslyProcessed_error_found = previouslyProcessed_check ( $file, $errormail_ch );
        $body = file_get_contents($fileid);
        $expected = get_fields($body);
        $improperlyFormatted_error_found = properly_formatted_check ( $file, $errormail_ch, $body);
        if ($previouslyProcessed_error_found or $improperlyFormatted_error_found)
          $transmit = false;
        else
          $transmit = true;
        break;
    }

    if ($transmit)
    {
      //echo "bulk: " . $bulkType . " update flag: " . ($update == NULL ? "'not entered'" : $update) . " file: " . $file . "\n";
      if (($bulkType == "template") and (($update == "u") or ($update == "update")))
      {
        curl_setopt($batch_ch, CURLOPT_URL, $APIName . "/" . $template); 
      } 
      curl_setopt($batch_ch, CURLOPT_POSTFIELDS, $body);
      curl_setopt($batch_ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $auth));
      $response = curl_exec($batch_ch);
      $valid_trans_count = true;  //By setting this to true, even processes that don't check it's count and have no errors will be moved to archive directory
      if ($validateProcessed == "On") $valid_trans_count = validate_transaction_count($response, $expected, $file, $errormail_ch);
      $http_status_code = curl_getinfo($batch_ch, CURLINFO_HTTP_CODE);
    
      $val = getTransactionID ($response);
      $DebugText .= "\n" . date(DATE_RFC822) . "\nResponse for file: " . $file . " \nTransaction ID: " . $val . "\n" . $response . "\n End Response \n";
      $encodedJSON = json_encode($response);
      $deliveryError = substr_count ($response, '{ "errors"');
      if (($deliveryError < 1) and $valid_trans_count)
      {
        // Archiving the file with the file/processing identifier.  That way if the same name is used multiple times, each one will
        // be archived wihtout being written over.
        $processedFile = $processedDirectoryLocation . "/" . basename ($fileid);
        if ($testToggle != "On") rename ( $fileid, $processedFile );
      }
      else
      { 
        if ($deliveryError > 0)
        {     
          // Add .error postfix
          $fullname .= ".error";
          $cleanresponse = str_replace (array('{','}','[',']',',','"'), "", $cleanresponse);
          $small = substr($cleanresponse, 0, 75);
          $pos = strpos($small, "message:");
          $small = substr( $small, $pos + 8);
          sendErrorMessageEmail ($errormail_ch, $small, $encodedJSON, $file);
        }
        $backup = $fileid . $fullname;
        if ($testToggle != "On") rename ( $fileid, $backup );
      }
      if ($fileTrackerToggle == "On")
      {
         $entry = $file . "\n";
         file_put_contents ($fileTracker, $entry, FILE_APPEND | LOCK_EX );
      } 
    } // End....Passed all validation processes
    else
    {
      $backup = $fileid . $fullname;
      if ($testToggle != "On") rename ( $fileid, $backup ); 
    }  
  }
}  // Each file

curl_close($errormail_ch);
file_put_contents ($log, $DebugText, FILE_APPEND | LOCK_EX );

if (file_exists($fileTracker)) truncate_file_log($fileTracker);
