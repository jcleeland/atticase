<?php
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
                'defeault'      => 'http://opencasetracker.yourdoman.com/',
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
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <img src="images/save.svg" class="floatright pointer img-fluid rounded ml-2" title="Save changes" id="saveDepartmentsBtn"/>
            <img src="images/undo.svg" class="floatright pointer img-fluid rounded hidden ml-2" width='24px' title="Undo changes" id="undoDepartmentsBtn"/>
            <h4 class="header">System Settings</h4>
            <div class="row border rounded">
                <!-- Column 1 -->
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['base_url']['description'] ?>">
                            Base URL
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='base_url' size=30 value='<?php echo $prefs['base_url']['value']; ?>' title='<?php echo $prefs['base_url']['description'] ?>'/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['admin_email']['description'] ?>">
                            Reply email for notifications
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='admin_email' size=30 value='<?php echo $prefs['admin_email']['value']; ?>' />
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['version']['description'] ?>">
                            Database version
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='admin_email' value='<?php echo $prefs['version']['value']; ?>' />
                        </div>                        
                    </div>                     
                </div>
                
                <!-- Column 2 -->
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['allow_billing']['description'] ?>">
                            Allow billing
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control' name='allow_billing'>
                                <option value='0'>No</option>
                                <option value='1' <?php if($prefs['allow_billing']['value']==1) echo " selected" ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['billing_rate']['description'] ?>">
                            Billing rate
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='admin_email' size=5 value='<?php echo $prefs['billing_rate']['value']; ?>' />
                        </div>                        
                    </div> 
                    <div class="row">                    
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['assigned_groups']['description'] ?>">
                            Assignable Groups
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="assign_admin" /> <label for="assign_admin">Admin</label>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['allow_restricted']['description'] ?>">
                            Allow task restriction
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="allow_restricted" checked="<?php if($prefs['allow_restricted']['value']==1) echo "checked" ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['anon_open']['description'] ?>">
                            Allow anonymous to create
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="anon_open" checked="<?php if($prefs['anon_open']['value']==1) echo "checked" ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['restrict_view']['description'] ?>">
                            Restrict views
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="anon_open" checked="<?php if($prefs['restrict_view']['value']==1) echo "checked" ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                        </div>
                    </div>
                </div>
                
                <!-- Other settings -->
                <?php
                foreach($configsettings as $key=>$val) {
                    ?>
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-sm text-capitalize header m-2">
                            <br />
                            <b><?php echo $key ?></b>
                        </div>
                    </div>
                </div>
                    <?php
                    foreach($val as $vkey=>$vval) {
                        ?>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-4 text-right"><?php echo $vkey ?></div><div class="col-8"><?php echo $vval['description'] ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <?php
                    }
                }
                    
                ?>
                
                
                                
            </div>
        </div>
    </div>
</div>