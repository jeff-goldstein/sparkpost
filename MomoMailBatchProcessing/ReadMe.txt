Input Params (paramters.ini), all required:
    # 1.  BaseURL           		        - URL for Web API....DO NOT INCLUDE ANYTHING PAST VERSION INDICATION
    # 2.  Authorization                   - Web API Authorization Key
    # 3.  ProcessedFilesDirectory         - Location to archive processed files
    # 4.  LogFile                         - Name of logging file 
    # 5.  MaxInputFileSize                - Largest size of input file allowed to be processed
    # 6.  AdminEmail                      - Email address for errors and warnings
    # 7.  ErrorTemplate                   - Template used for emailing errors and warnings
    # 8.  MomoMailDirectory               - Location process will look for files to be processed
    # 9.  Postfix                         - Only files with this .<postfix> will be processed
    # 10. ErrorCampaignName               - Used for reporting (both UI and Web Hooks)
    # 11. Test                            - When set to "On", processed files are not archived or renamed; allows for easier repeat testing
    # 12. ArchiveWarningSize              - When the 'ProcessedFilesDirectory' reaches this size, a warning email will be sent to the admin
    # 13. FileTrackerToggle               - When set to "On", repeat file names cannot be processed
    # 14. TrackerFile                     - Name of file containing list of already processed files
    # 15. FileTrackerOffset               - How many of the last processed files to track
    # 16. ValidateProcessedToggle         - When set to "On" the field name for the paramter "ExpectedNumberofItems" will be used to compare
                                            how many items were processed compared to how many were expected to be processed. Default is off.
    # 17. ExpectedFieldName               - This is the Field Name in the json file that contains the expected number of items
    
    Hardcoded items to watch for if making additions or changing for your environment

    ini_set (memory_limit) at top of application.  This is set because the json_decode used to validate the input file takes a lot of memory
    If you need to save memory, you can skip that check and throw caution to the wind.
    $parametersFile - hardcoded to parameters.ini
    $cat.  The first usage of this variable obtains the 'global' category in the parameters.ini
    After that, the first CLI parameter is used to set the $cat variable in order to obtain specific category input parameters.
    

momomail.php -  Batch processing application that acts on behalf of back office application(s) and
                thier Momentum/Sparkpost.com/Sparkpost Elite MTA platforms.
SYNOPSIS
  php momobulk.php transmission|recipient|template [-u|update]
DESCRIPTION
  MomoBulk simplifies the bulk work of sending emails, updating/adding templates and adding recipient lists.
  This application will only do one type of bulk work at a time and is driven by two arguments that are obtained when the application is executed.
  The first parameter identifies the type of work expected, while the second parameter tells the system if the upload should be treated
  as an 'add/post' or an 'update/put' call.  Currently, the second parameter is only used for the 'template' calls.
  While not manditory, it is expected that this process will be automatically run via a process like cron or something similar.
  For example: >> crontab -e * * * * * /opt/msys/3rdParty/bin/php /root/momomail.php transmission
  will run the processes once every minute.  If you want to run multiple processes similtaneously, you need multiple entries."

PARAMETERS
  o First parameter tells the system what kind of work is being requested.  It must be one of the entries, 'transmission', 'recipient' or 'template'
  o The second paramater is optional and only used for 'template' bulk work and must be either an 'u' or 'update'; both telling the
    system to update the template already on the system.
