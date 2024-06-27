<?php
/*
 * Copyright [2022] [Jason Alexander Cleeland, Melbourne Australia]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
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
                'default'      => 'http://opencasetracker.yourdomain.com/',
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
        'cookiePrefix'      =>  array(
                'title'         =>  'Cookie Prefix',
                'description'   =>  'The text prefix for stored cookies',
                'type'          =>  'string',
                'default'       =>  'AttiCase',
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
                'default'       => '',
                'select'       => array(
                    ''=>'none',
                    'oms'=>'oms',
                ),
                'usewith'       => 'useexternaldb=true',
            ),
        'extdbuser'         =>  array(
                'title'         => 'External DB Username',
                'description'   => 'User name required to access external db',
                'type'          => 'string',
                'default'       => 'casetracker',
                'usewith'       => 'useexternaldb=true',
            ),
        'extdbpass'         =>  array(
                'title'         => 'External DB Password',
                'description'   => 'The password required to access external db',
                'type'          => 'password',
                'default'       => 'casetracker',
                'usewith'       => 'useexternaldb=true',
            ),
    ),
    'emailretrieval'         => array(
        'retrievalmethod'   =>  array(
            'title'         => 'Retrieval Method',
            'description'   => 'The method used to retrieve emails',
            'type'          => 'string',
            'default'       => '',
            'select'        => array(
                'microsoft'     => 'Microsoft 365 Exchange (oAuth)',
                'imap'          => 'iMap',
                'pop'           => 'POP3'
            )
        ),


        'popretrievalhost'     =>  array(
            'title'         => 'Host',
            'description'   => 'The host address for retrieving emails (ie: smtp.office365.com)',
            'type'          => 'string',
            'default'       => 'smtp.office365.com',
            'usewith'       => 'retrievalmethod=pop',
        ),
        'popretrievalaccount'  =>  array(
            'title'         => 'Account Name',
            'description'   => 'The account name for retrieving emails',
            'type'          => 'string',
            'default'       => 'opencasetracker@yourdomain.com',
            'usewith'       => 'retrievalmethod=pop',
        ),
        'popretrievalpassword' =>  array(
            'title'         => 'Password',
            'description'   => 'The password for retrieving emails',
            'type'          => 'password',
            'default'       => 'password',
            'usewith'       => 'retrievalmethod=pop',
        ),        
        'pop3protocol'      =>  array(
            'title'         => 'Pop3 Protocol',
            'description'   => 'Which POP protocal (ie: tcp, ssl, sslv2, sslv3, tls)',
            'type'          => 'string',
            'default'       => 'tls',
            'select'        => array(
                'ssl'           => 'SSL',
                'sslv2'         => 'SSL Version 2',
                'sslv3'         => 'SSL Version 3',
                'tcp'           => 'TCP',
                'tls'           => 'TLS',
            ),
            'usewith'       => 'retrievalmethod=pop',
        ),
        'pop3port'          =>  array(
            'title'         => 'Pop3 Port',
            'description'   => 'Which POP port? (ie: tcp=110, ssl=443, tls=995)',
            'type'          => 'string',
            'default'       => '995',
            'usewith'       => 'retrievalmethod=pop',
        ),
        'pop3useIPV6'       =>  array(
            'title'         => 'Use IPV6',
            'description'   => 'Use IPV6? (default false)',
            'type'          => 'boolean',
            'default'       => 'false',
            'usewith'       => 'retrievalmethod=pop',
        ),
        'pop3usesockets'    =>  array(
            'title'         => 'Use Sockets',
            'description'   => 'If using POP, use sockets? (default false)',
            'type'          => 'boolean',
            'default'       => 'false',
            'usewith'       => 'retrievalmethod=pop',
        ),
        'pop3autodetect'    =>  array(
            'title'         => 'Autodetect POP3 Method?',
            'description'   => 'Autodetect POP method? (default true)',
            'type'          => 'boolean',
            'default'       => 'true',
            'usewith'       => 'retrievalmethod=pop',
        ),
        'pop3hideusernameatlog'=>array(
            'title'         => 'Hide Pop3 Username in Log files',
            'description'   => 'Hide the username in the log files? (default false)',
            'type'          => 'boolean',
            'default'       => 'false',
            'usewith'       => 'retrievalmethod=pop',
        ),

        
        'imapretrievalhost'     =>  array(
            'title'         => 'Host',
            'description'   => 'The host address for retrieving emails (ie: smtp.office365.com)',
            'type'          => 'string',
            'default'       => 'smtp.office365.com',
            'usewith'       => 'retrievalmethod=imap',
        ),        
        'imapretrievalaccount'  =>  array(
            'title'         => 'Account Name',
            'description'   => 'The account name for retrieving emails',
            'type'          => 'string',
            'default'       => 'opencasetracker@yourdomain.com',
            'usewith'       => 'retrievalmethod=imap',
        ),        
        'imapretrievalpass' =>  array(
            'title'         => 'Password',
            'description'   => 'The password for retrieving emails',
            'type'          => 'password',
            'default'       => 'password',
            'usewith'       => 'retrievalmethod=imap',
        ),
        'imapoptions'       =>  array(
            'title'         => 'Additional IMAP options',
            'description'   => 'If using IMAP, additional options (ie: /imap/ssl/notls/secure)',
            'type'          => 'string',
            'default'       => '/imap/secure',
            'usewith'       => 'retrievalmethod=imap',
        ),
        'imapport'          =>  array(
            'title'         => 'IMAP Port',
            'description'   => 'Which IMAP port? (ie: 143, or 993 for ssl)',
            'type'          => 'string',
            'default'       => '143',
            'usewith'       => 'retrievalmethod=imap',
        ),
        'imapmailbox'       =>  array(
            'title'         => 'IMAP Mailbox',
            'description'   => 'Name of IMAP mailbox (default Inbox)',
            'type'          => 'string',
            'default'       => 'Inbox',
            'usewith'       => 'retrievalmethod=imap',
        ),

        
        'microsoftappid'    =>  array(
            'title'         => 'Microsoft App ID',
            'description'   => 'The App ID for Microsoft 365',
            'type'          => 'string',
            'default'       => '',
            'usewith'       => 'retrievalmethod=microsoft',
        ),
        'microsoftappsecret'=>  array(
            'title'         => 'Microsoft App Secret',
            'description'   => 'The App Secret for Microsoft 365',
            'type'          => 'string',
            'default'       => '',
            'usewith'       => 'retrievalmethod=microsoft',
        ),
        'microsofttenantid' =>  array(
            'title'         => 'Microsoft Tenant ID',
            'description'   => 'The Tenant ID for Microsoft 365',
            'type'          => 'string',
            'default'       => '',
            'usewith'       => 'retrievalmethod=microsoft',
        ),
        'microsoftaccount' => array(
            'title'         => 'Microsoft Email Account',
            'description'   => 'The email account for Microsoft 365',
            'type'          => 'string',
            'default'       => 'atticase@yourdomain.com',
            'usewith'       => 'retrievalmethod=microsoft',
        )
    ),
    'emailsending' => array(
        'smtpemail'         => array(
            'title'         => 'Email Address',
            'description'   => 'The email address to send from',
            'type'          => 'string',
            'default'       => 'atticase@yourdomain.com',
        ),
        'smtpemailname'     => array(
            'title'         => 'Email Name',
            'description'   => 'The name to send from',
            'type'          => 'string',
            'default'       => 'AttiCase',
        ),
        'smtphost'          =>  array(
            'title'         => 'SMTP Host',
            'description'   => 'The SMTP host for outgoing emails',
            'type'          => 'string',
            'default'       => 'smtp.office365.com',
        ),
        'smtp_port'         =>  array(
            'title'         => 'SMTP Port',
            'description'   => 'The SMTP port for outgoing emails',
            'type'          => 'integer',
            'default'       => '587',
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
        'smtp_debug'        =>  array(
            'title'         => 'SMTP Debug Level',
            'description'   => 'What level debug to use? (0=none, 1=basic, 2=detailed)',
            'type'          => 'string',
            'default'       => '0',
            'select'        => array(
                '0'             => 'None',
                '1'             => 'Basic (Commands)',
                '2'             => 'Basic (Data and commands',
                '3'             => 'Advanced (Data, commands and connection status)',
                '4'             => 'Advanced (Data, commands, connection status and full debug)',
            ),
        ),
        'email_testmode'    => array(
            'title'         => 'Email Test Mode',
            'description'   => 'Send all emails to a test email address (default=off)',
            'type'          => 'boolean',
            'default'       => 'false',
        ),
        'email_test_address'    => array(
            'title'         => 'Test Email',
            'description'   => 'The email address to send all emails to in test mode',
            'type'          => 'string',
            'default'       => ''
        ),
    ),
    'emailgeneral'  =>  array(        
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
                'usewith'       => 'allow_billing=true',
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
        'admin_email'           =>  array(
                'title'         => 'Administrator email',
                'description'   => 'Send administration emails to this address',
                'type'          => 'string',
                'default'       => 'admin@casetracker.com',
        ),
        'additional_admins_p4'  =>  array(
                'title'         => 'Additional admin emails',
                'description'   => 'Send additional administration emails to these addresses (separate with comma)',
                'type'          => 'string',
                'default'       => '',
        ),
    ),
    'ldap'      =>  array(
        'useldap'               =>  array(
                'title'         =>  'Use LDAP',
                'description'   =>  'Use LDAP for authentication',
                'type'          =>  'boolean',
                'default'       =>  false,
        ),
        'ldaphost'              =>  array(
                'title'         =>  'LDAP Host IP',
                'description'   =>  'The IP address of the LDAP host',
                'type'          =>  'string',
                'default'       =>  '',
                'usewith'       =>  'useldap=true',
        ),
        'ldapport'              =>  array(
                'title'         =>  'LDAP Port',
                'description'   =>  'The port used by LDAP host',
                'type'          =>  'string',
                'default'       =>  '389',
                'usewith'       =>  'useldap=true',
        ),
        'ldapbasedn'            =>  array(
                'title'         =>  'LDAP Base Domain',
                'description'   =>  'Base domain for LDAP Host',
                'type'          =>  'string',
                'default'       =>  'DC=domain,DC=dom',
                'usewith'       =>  'useldap=true',
        ),
        'ldapacsuffix'          =>  array(
                'title'         =>  'LDAP AC Suffix',
                'description'   =>  'LDAP AC Suffix',
                'type'          =>  'string',
                'default'       =>  '@domain.com',
                'usewith'       =>  'useldap=true',
        ),
        'ldapadminuser'         =>  array(
                'title'         =>  'LDAP Admin User',
                'description'   =>  'LDAP Admin Username (optional, enter null if not required)',
                'type'          =>  'string',
                'default'       =>  'null',
                'usewith'       =>  'useldap=true',
        ),
        'ldapadminpassword'     =>  array(
                'title'         =>  'LDAP Admin user Password',
                'description'   =>  'The password for LDAP admin user (optional, enter null if not required)',
                'type'          =>  'string',
                'default'       =>  'null',
                'usewith'       =>  'useldap=true',
        ),
        'ldaplocaladmin'        =>  array(
                'title'         =>  'OpenCaseTracker admin user',
                'description'   =>  'The OpenCaseTracker username who can login without LDAP permission',
                'type'          =>  'string',
                'default'       =>  'admin',                
                'usewith'       =>  'useldap=true',
        ),
        'ldapnewusergroup'      =>  array(
                'title'         =>  'LDAP New User Group',
                'description'   =>  'User group to put new/unknown LDAP users into (use group ID number)',
                'type'          =>  'integer',
                'default'       =>  '0',
                'usewith'       =>  'useldap=true',
        )
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
        'unit_list_use' => array(
                'title'         => 'Use list for units',
                'description'   => 'Restrict entries for units to only those in the list',    //There are three levels for this - none=0, optional=1 and only=2
                'type'          => 'boolean',
                'default'       => '0',
                'selectoptions' => array("0"=>"Free text", "1"=>"Suggested from list", "2"=>"Force from list"),
        ),
        'pager_default_qty' => array(
                'title'         => 'Default case quantity',
                'description'   => 'The default number of cases that display in a list',
                'type'          => 'integer',
                'default'       => '20',
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
                )
    )
);  

//Now load all the database preferences
$query="SELECT * FROM ".$oct->dbprefix."prefs ORDER BY pref_name";
$settings=$oct->fetchMany($query);
$settings=$settings['output'];
foreach($settings as $setting) {
  $prefs[$setting['pref_name']]=array("value"=>$setting['pref_value'], "description"=>$setting['pref_desc']);
}
//$oct->showArray($prefs);
// Go through configsettings and populate it with saved settings, or defaults as required
foreach($configsettings as $group=>$groupprefs) {
    foreach($groupprefs as $preftitle=>$prefvalues) {
        if(isset($prefs[$preftitle])) {
            $configsettings[$group][$preftitle]['value']=$prefs[$preftitle]['value'];
            $configsettings[$group][$preftitle]['set']=true;
            unset($prefs[$preftitle]);
        } else {
            $configsettings[$group][$preftitle]['value']=$configsettings[$group][$preftitle]['default'];
            $configsettings[$group][$preftitle]['set']=false;
        }
    } 
}
foreach($prefs as $key=>$val) {
    $configsettings['other'][$key]=$val;
    $configsettings['other'][$key]['title']=$key;
    $configsettings['other'][$key]['type']='string';
    $configsettings['other'][$key]['default']=null;
    $configsettings['other'][$key]['set']=false;
}
//$oct->showArray($prefs);
unset($prefs);
//$oct->showArray($configsettings);
$oct->config=$configsettings;
?>
