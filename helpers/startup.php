<?php
    //All the things needed to get opencasetracker scripts connected and up and running
    require_once("config/config.php");
    require_once("helpers/oct.php");
    if($settings['useexternaldb']===true) {
        require_once("helpers/externaldb/".$settings['externaldb'].".php");

    }
    $oct=new oct;
    $oct->dbuser=$settings['dbuser'];
    //$oct->dbpass=$settings['dbpass'];
    $oct->dbpass=$settings['dbpass'];
    $oct->dbhost=$settings['dbhost'];
    $oct->dbname=$settings['dbname'];
    $oct->dbprefix=$settings['dbprefix'];
    $oct->connect();
    
    //Gather user settings
    require_once("helpers/settings.php");
    /**
    * Array
(
    [additional_admins_p4] => Array
        (
            [value] => jcleeland@cpsuvic.org
            [description] => Additional admin notification to be sent to these emails
        )

    [admin_email] => Array
        (
            [value] => jcleeland@cpsuvic.org
            [description] => Reply email address for notifications
        )

    [allow_billing] => Array
        (
            [value] => 1
            [description] => Display billing tab
        )

    [allow_restricted] => Array
        (
            [value] => 1
            [description] => Allow tasks to be set with restricted access
        )

    [anon_group] => Array
        (
            [value] => 1
            [description] => Group for anonymous registrations
        )

    [anon_open] => Array
        (
            [value] => 0
            [description] => Allow anonymous users to open new tasks
        )

    [anon_view] => Array
        (
            [value] => 
            [description] => Allow anonymous users to view this BTS
        )

    [assigned_groups] => Array
        (
            [value] => 1 3 8  10 11 12 13 14
            [description] => Members of these groups can be assigned tasks
        )

    [base_url] => Array
        (
            [value] => https://web.cpsuvic.org:4443/casetracker/
            [description] => Base URL for this installation
        )

    [billing_rate] => Array
        (
            [value] => 220
            [description] => Per hour billing rate
        )

    [dateformat] => Array
        (
            [value] => 
            [description] => 
        )

    [dateformat_extended] => Array
        (
            [value] => 
            [description] => 
        )

    [default_cat_owner] => Array
        (
            [value] => 0
            [description] => Default category owner
        )

    [default_project] => Array
        (
            [value] => 4
            [description] => Default project id
        )

    [hidden_users] => Array
        (
            [value] => 45
            [description] => Users that are hidden from the standard case list
        )

    [hover_effects] => Array
        (
            [value] => 1
            [description] => Use css mouseover effects on lists
        )

    [jabber_password] => Array
        (
            [value] => 
            [description] => Jabber password
        )

    [jabber_port] => Array
        (
            [value] => 
            [description] => Jabber server port
        )

    [jabber_server] => Array
        (
            [value] => 
            [description] => Jabber server
        )

    [jabber_username] => Array
        (
            [value] => 
            [description] => Jabber username
        )

    [lang_code] => Array
        (
            [value] => en-union
            [description] => Language
        )

    [overdue_modal] => Array
        (
            [value] => 2
            [description] => Display a modal box listing overdue cases
        )

    [project_title] => Array
        (
            [value] => Case Tracker!
            [description] => Project title
        )

    [restrict_view] => Array
        (
            [value] => 
            [description] => Restrict access to own cases
        )

    [spam_proof] => Array
        (
            [value] => 
            [description] => Use confirmation codes for user registrations
        )

    [theme_style] => Array
        (
            [value] => Bluey
            [description] => Theme / Style
        )

    [unit_list_use] => Array
        (
            [value] => 0
            [description] => 
        )

    [user_notify] => Array
        (
            [value] => 2
            [description] => Force task notifications as
        )

    [version] => Array
        (
            [value] => 2.180725
            [description] => Database Version Number
        )

)
    */
    
    include("scripts/authenticate.php");   
?>
