<?php

//Definition of all the various configuation settings, including defaults
$configsettings=array(
    'installation'  => array(
        'basedir'           =>  array(
                'description'   => 'Base directory for installation',
                'type'          => 'string',
                'default'       => '/wamp64/www',
            ),
        'baseurl'           =>  array(
                'description'   => 'Base URL for installation',
                'type'          => 'string',
                'default'      => 'http://opencasetracker.yourdoman.com/',
            ),
        'phpmailerpath'     =>  array(
                'description'   => 'The file location of the phpmailer class',
                'type'          => 'string',
                'default'       => '/wamp64/www/phpmailer',
            ),
        'dbprefix'          =>  array(
                'description'   => 'The prefix to add to database table names',
                'type'          => 'string',
                'default'       => 'oct_',
            ),
        'defaulttimezone'   => array(
                'description'   => 'The default timezone for this installation (ie: Australia/Melbourne)',
                'type'          => 'string',
                'default'       => 'Australia/Melbourne',
            ),
        'attachmentdir'     =>  array(
                'description'   => 'The file location of the attachment directory',
                'type'          => 'string',
                'default'       => '/wamp64/attachments',
            ),
    ),
    'externaldb'    =>  array(
        'useexternaldb'     =>  array(
                'description'   => 'Use an external membership or client database?',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'externaldb'        =>  array(
                'description'   => 'The name of the external db (class directory name)',
                'type'          => 'string',
                'default'       => 'casetracker',
            ),
        'extdbuser'         =>  array(
                'description'   => 'User name required to access external db',
                'type'          => 'string',
                'default'       => 'casetracker',
            ),
        'extdbpass'         =>  array(
                'description'   => 'The password required to access external db',
                'type'          => 'password',
                'default'       => 'casetracker',
            ),
    ),
    'email'         => array(
        'retrievalmethod'   =>  array(
                'description'   => 'The method used to retrieve emails (pop or imap)',
                'type'          => 'string',
                'default'       => 'imap',
            ),
        'retrievalhost'     =>  array(
                'description'   => 'The host address for retrieving emails (ie: smtp.office365.com)',
                'type'          => 'string',
                'default'       => 'smtp.office365.com',
            ),
        'retrievalaccount'  =>  array(
                'description'   => 'The account name for retrieving emails',
                'type'          => 'string',
                'default'       => 'opencasetracker@yourdomain.com'
            ),
        'retrievalpassword' =>  array(
                'description'   => 'The password for retrieving emails',
                'type'          => 'password',
                'default'       => 'password',
            ),
        'pop3protocol'      =>  array(
                'description'   => 'If using POP, which protocal (ie: tcp, ssl, sslv2, sslv3, tls)',
                'type'          => 'string',
                'default'       => 'tls',
            ),
        'pop3port'          =>  array(
                'description'   => 'If using POP, which port? (ie: tcp=110, ssl=443, tls=995)',
                'type'          => 'string',
                'default'       => '995',
            ),
        'pop3useIPV6'       =>  array(
                'description'   => 'If using POP, use IPV6? (default false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'pop3usesockets'    =>  array(
                'description'   => 'If using POP, use sockets? (default false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'pop3autodetect'    =>  array(
                'description'   => 'If using POP, autodetect? (default true)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
        'pop3hideusernameatlog'=>array(
                'description'   => 'If using POP, hide the username in the log files? (default false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        
        'imapoptions'       =>  array(
                'description'   => 'If using IMAP, additional options (ie: /imap/ssl/notls/secure)',
                'type'          => 'string',
                'default'       => '/imap/secure',
            ),
        'imapport'          =>  array(
                'description'   => 'If using IMAP, which port? (ie: 143, or 993 for ssl)',
                'type'          => 'string',
                'default'       => '143',
            ),
        'imapmailbox'       =>  array(
                'description'   => 'If using IMAP, the name of the mailbox (default Inbox)',
                'type'          => 'string',
                'default'       => 'Inbox',
            ),
        
        'usereplyto'        =>  array(
                'description'   => 'If your sender can`t fake the sender address, use reply-to for the sender info (default=true)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
        'sendemaildebug'    =>  array(
                'description'   => 'Show detailed debugging messages while sending emails (turn off in production environment)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
        
        'smtphost'          =>  array(
                'description'   => 'The SMTP host for outgoing emails',
                'type'          => 'string',
                'default'       => 'smtp.office365.com',
            ),
        'smtpaccount'       =>  array(
                'description'   => 'The SMTP account for outgoing emails',
                'type'          => 'string',
                'default'       => 'opencasetracker@yourdomain.com',
            ),
        'smtppassword'      =>  array(
                'description'   => 'The SMTP password for outgoing emails',
                'type'          => 'password',
                'default'       => 'password',
            ),
        'smtp_auth'         =>  array(
                'description'   => 'Authentication SMTP? (default=true)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
        'smtp_tls'          =>  array(
                'description'   => 'Use TLS for SMTP? (default=true)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
        'smtp_ntlm'         =>  array(
                'description'   => 'Use NTLM for SMTP? (default=false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'smtp_debug'        =>  array(
                'description'   => 'What level debug to use? (0=none, 1=basic, 2=detailed)',
                'type'          => 'integer',
                'default'       => '0',
            ),
        
        'mailsizemultiplier'=>  array(
                'description'   => 'A setting to allow the system to estimate the real size of an email before processing, to stop memory outages. Default=1',
                'type'          => 'integer',
                'default'       => '2',
            ),
        'email_stop_on_err' =>  array(
                'description'   => 'Stop processing scripts on any email error (for checking error message) - default=false',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        
        'attach_address'    =>  array(
                'description'   => 'The email address used to send attachments to opencasetracker (ie: opencasetracker@yourdomain.com)',
                'type'          => 'string',
                'default'       => 'opencasetracker@yourdomain.com',
            ),
        'email_tempdir'     =>  array(
                'description'   => 'The temporary directory to store attachments before filing',
                'type'          => 'string',
                'default'       => '/wamp64/www/tmp',
            ),
        'email_keeplog'     =>  array(
                'description'   => 'Keep a log of emails?',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        
    ),
    
    'billing'   =>  array(
        'usebilling'        =>  array(
                'description'   => 'Whether or not to enable billing (default=false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'billingrate'       =>  array(
                'description'   => 'The rate per hour at which to bill',
                'type'          => 'integer',
                'default'       => '120',
            ),
    ),
    'comments'  =>  array(
        'allowtime'         =>  array(
                'description'   => 'Allow time record with comments (default=false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'allowcost'         =>  array(
                'description'   => 'Allow a cost record with comments (default=false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'allowdate'         =>  array(
                'description'   => 'Allow the date of a comment to be modified (default=false)',
                'type'          => 'boolean',
                'default'       => 'false',
            ),
        'allowselfedit'     =>  array(
                'description'   => 'Allow users to edit their own comments (default=true)',
                'type'          => 'boolean',
                'default'       => 'true',
            ),
    )
);  
?>
