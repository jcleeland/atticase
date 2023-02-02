<?php
    //All the things needed to get opencasetracker scripts connected and up and running
    if(!file_exists("config/config.php")) {
        include("helpers/initial.php");
        die();
    } else {
        require_once("config/config.php");
        require_once("helpers/oct.php");
        require_once("helpers/externaldb/oms.php");
        $oct=new oct;
        $oct->dbuser=$settings['dbuser'];
        //$oct->dbpass=$settings['dbpass'];
        $oct->dbpass=$settings['dbpass'];
        $oct->dbhost=$settings['dbhost'];
        $oct->dbname=$settings['dbname'];
        $oct->dbprefix=$settings['dbprefix'];
        $oct->connect();
        
        require_once("helpers/settings.php");
        
        include("scripts/authenticate.php");   
    }
?>
