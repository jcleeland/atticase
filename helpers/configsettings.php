<?php

//Definition of all the various configuation settings, including defaults
$configsettings=array(
    'installation'  => array(
        'basedir'           =>  array(
                'title'         => 'Base Directory',
                'description'   => 'Base directory for installation',
                'type'          => 'string',
                'default'       => '/wamp64/www',
            ),
        'base_url'           =>  array(
                'title'         => 'Base URL',
                'description'   => 'Base URL for installation',
                'type'          => 'string',
                'default'      => 'http://opencasetracker.yourdoman.com/',
            ),
        'phpmailerpath'     =>  array(
                'title'         => 'PHPMailer Directory',
                'description'   => 'The file location of the phpmailer class',
                'type'          => 'string',
                'default'       => '/wamp64/www/phpmailer',
            ),
        'dbprefix'          =>  array(
                'title'         => 'Database Prefix',
                'description'   => 'The prefix to add to database table names',
                'type'          => 'string',
                'default'       => 'oct_',
            ),
        'defaulttimezone'   => array(
                'title'         => 'Default TimeZone',
                'description'   => 'The default timezone for this installation (ie: Australia/Melbourne)',
                'type'          => 'string',
                'default'       => 'Australia/Melbourne',
            ),
        'attachmentdir'     =>  array(
                'title'         => 'Attachment Directory',
                'description'   => 'The file location of the attachment directory',
                'type'          => 'string',
                'default'       => '/wamp64/attachments',
            ),
    ),
    'externaldb'    =>  array(
        'useexternaldb'     =>  array(
                'title'         => 'Use external DB',
                'description'   => 'Use an external membership or client database?',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'externaldb'        =>  array(
                'title'         => 'External DB Name',
                'description'   => 'The name of the external db (class directory name)',
                'type'          => 'string',
                'default'       => 'casetracker',
            ),
        'extdbuser'         =>  array(
                'title'         => 'External DB Username',
                'description'   => 'User name required to access external db',
                'type'          => 'string',
                'default'       => 'casetracker',
            ),
        'extdbpass'         =>  array(
                'title'         => 'External DB Password',
                'description'   => 'The password required to access external db',
                'type'          => 'password',
                'default'       => 'casetracker',
            ),
    ),
    'email'         => array(
        'retrievalmethod'   =>  array(
                'title'         => 'Email Retrieval Method',
                'description'   => 'The method used to retrieve emails (pop or imap)',
                'type'          => 'string',
                'default'       => 'imap',
            ),
        'retrievalhost'     =>  array(
                'title'         => 'Email Retrieval Host',
                'description'   => 'The host address for retrieving emails (ie: smtp.office365.com)',
                'type'          => 'string',
                'default'       => 'smtp.office365.com',
            ),
        'retrievalaccount'  =>  array(
                'title'         => 'Email Retrieval Account Name',
                'description'   => 'The account name for retrieving emails',
                'type'          => 'string',
                'default'       => 'opencasetracker@yourdomain.com'
            ),
        'retrievalpassword' =>  array(
                'title'         => 'Email Retrieval Password',
                'description'   => 'The password for retrieving emails',
                'type'          => 'password',
                'default'       => 'password',
            ),
        'pop3protocol'      =>  array(
                'title'         => 'Pop3 Protocol',
                'description'   => 'If using POP, which protocal (ie: tcp, ssl, sslv2, sslv3, tls)',
                'type'          => 'string',
                'default'       => 'tls',
            ),
        'pop3port'          =>  array(
                'title'         => 'Pop3 Port',
                'description'   => 'If using POP, which port? (ie: tcp=110, ssl=443, tls=995)',
                'type'          => 'string',
                'default'       => '995',
            ),
        'pop3useIPV6'       =>  array(
                'title'         => 'Pop3 Use IPV6',
                'description'   => 'If using POP, use IPV6? (default false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'pop3usesockets'    =>  array(
                'title'         => 'Pop3 Use Sockets',
                'description'   => 'If using POP, use sockets? (default false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'pop3autodetect'    =>  array(
                'title'         => 'Autodetect POP3?',
                'description'   => 'If using POP, autodetect? (default true)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
        'pop3hideusernameatlog'=>array(
                'title'         => 'Hide Pop3 Username in Log files',
                'description'   => 'If using POP, hide the username in the log files? (default false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        
        'imapoptions'       =>  array(
                'title'         => 'Additional IMAP options',
                'description'   => 'If using IMAP, additional options (ie: /imap/ssl/notls/secure)',
                'type'          => 'string',
                'default'       => '/imap/secure',
            ),
        'imapport'          =>  array(
                'title'         => 'IMAP Port',
                'description'   => 'If using IMAP, which port? (ie: 143, or 993 for ssl)',
                'type'          => 'string',
                'default'       => '143',
            ),
        'imapmailbox'       =>  array(
                'title'         => 'IMAP Mailbox',
                'description'   => 'If using IMAP, the name of the mailbox (default Inbox)',
                'type'          => 'string',
                'default'       => 'Inbox',
            ),
        
        'usereplyto'        =>  array(
                'title'         => 'Use the reply-to as sender',
                'description'   => 'If your sender can`t fake the sender address, use reply-to for the sender info (default=true)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
        'sendemaildebug'    =>  array(
                'title'         => 'Show email debug',
                'description'   => 'Show detailed debugging messages while sending emails (turn off in production environment)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
        
        'smtphost'          =>  array(
                'title'         => 'SMTP Host',
                'description'   => 'The SMTP host for outgoing emails',
                'type'          => 'string',
                'default'       => 'smtp.office365.com',
            ),
        'smtpaccount'       =>  array(
                'title'         => 'SMTP Account',
                'description'   => 'The SMTP account for outgoing emails',
                'type'          => 'string',
                'default'       => 'opencasetracker@yourdomain.com',
            ),
        'smtppassword'      =>  array(
                'title'         => 'SMTP Password',
                'description'   => 'The SMTP password for outgoing emails',
                'type'          => 'password',
                'default'       => 'password',
            ),
        'smtp_auth'         =>  array(
                'title'         => 'SMTP Authentication',
                'description'   => 'Authentication SMTP? (default=true)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
        'smtp_tls'          =>  array(
                'title'         => 'SMTP TLS',
                'description'   => 'Use TLS for SMTP? (default=true)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
        'smtp_ntlm'         =>  array(
                'title'         => 'SMTP NTLM',
                'description'   => 'Use NTLM for SMTP? (default=false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'smtp_debug'        =>  array(
                'title'         => 'SMTP Debug Level',
                'description'   => 'What level debug to use? (0=none, 1=basic, 2=detailed)',
                'type'          => 'integer',
                'default'       => '0',
            ),
        
        'mailsizemultiplier'=>  array(
                'title'         => 'Mail size multiplier',
                'description'   => 'A setting to allow the system to estimate the real size of an email before processing, to stop memory outages. Default=1',
                'type'          => 'integer',
                'default'       => '2',
            ),
        'email_stop_on_err' =>  array(
                'title'         => 'Stop emails on error',
                'description'   => 'Stop processing scripts on any email error (for checking error message) - default=false',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        
        'attach_address'    =>  array(
                'title'         => 'Attachment email address',
                'description'   => 'The email address used to send attachments to opencasetracker (ie: opencasetracker@yourdomain.com)',
                'type'          => 'string',
                'default'       => 'opencasetracker@yourdomain.com',
            ),
        'email_tempdir'     =>  array(
                'title'         => 'Email Temp Directory',
                'description'   => 'The temporary directory to store attachments before filing',
                'type'          => 'string',
                'default'       => '/wamp64/www/tmp',
            ),
        'email_keeplog'     =>  array(
                'title'         => 'Keep email log',
                'description'   => 'Keep a log of emails?',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        
    ),
    'billing'   =>  array(
        'allow_billing'        =>  array(
                'title'         => 'Use Billing Feature',
                'description'   => 'Whether or not to enable billing (default=false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'billing_rate'       =>  array(
                'title'         => 'Billing Rate',
                'description'   => 'The rate per hour at which to bill',
                'type'          => 'integer',
                'default'       => '120',
            ),
    ),
    'comments'  =>  array(
        'allowtime'         =>  array(
                'title'         => 'Allow Time Records',
                'description'   => 'Allow time record with comments (default=false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'allowcost'         =>  array(
                'title'         => 'Allow Cost Records',
                'description'   => 'Allow a cost record with comments (default=false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'allowdate'         =>  array(
                'title'         => 'Allow changing comment dates',
                'description'   => 'Allow the date of a comment to be modified (default=false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'allowselfedit'     =>  array(
                'title'         => 'Allow comment edits by users',
                'description'   => 'Allow users to edit their own comments (default=true)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
    ),
    'notifications' => array(
        'admin_email'       =>  array(
                'title'         => 'Administrator email',
                'description'   => 'Send administration emails to this address',
                'type'          => 'string',
                'default'       => 'admin@casetracker.com',
        ),
        'additional_admins_p4' =>  array(
                'title'         => 'Additional admin emails',
                'description'   => 'Send additional administration emails to these addresses (separate with comma)',
                'type'          => 'string',
                'default'       => '',
        ),
    ),
    'general'   =>  array(
        'project_title' =>  array(
                'title'         => 'Site name',
                'description'   => 'Name of site',
                'type'          => 'string',
                'default'       => 'OpenCaseTracker',
        ),
        'theme_style'   =>  array(
                'title'         => 'Theme Style',
                'description'   => 'Theme and style of site',
                'type'          => 'string',
                'default'       => 'Bluey'
        ),
        'lang_code'     =>  array(
                'title'         => 'Language',
                'description'   => 'Language to use',
                'type'          => 'string',
                'default'       => 'en',
        ),
        'version'      => array(
                'title'         => 'Database version',
                'description'   => 'Current db layout version',
                'type'          => 'integer',
                'default'       => '1.000',
                
        ),
        'allow_restricted'      => array(
                'title'         => 'Allow restricted cases',
                'description'   => 'Allow cases to be optionally restricted to assigned user only',
                'type'          => 'boolean',
                'default'       => 'true'
        ),
        'restrict_view' => array(
                'title'         => 'Restrict case views',    
                'description'   => 'Only allow users to see their own cases (restrict all others)',
                'type'          => 'boolean',
                'default'       => 'false',
                ),
        'unit_list_use' => array(
                'title'         => 'Use list for units',
                'description'   => 'Restrict entries for units to only those in the list',    //I think there are three levels for this - none, optional and only
                'type'          => 'boolean',
                'default'       => '0',
        )
    )
);  
?>
