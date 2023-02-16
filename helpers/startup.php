<?php
    /* use Microsoft\Graph\Graph;
    use Microsoft\Graph\Model; */        

    //All the things needed to get opencasetracker scripts connected and up and running
    if(!file_exists("config/config.php")) {
        include("helpers/initial.php");
        die();
    } else {
        require_once 'config/config.php';
        require_once "helpers/oct.php";

        $oct=new oct;
        $oct->dbuser=$settings['dbuser'];
        $oct->dbpass=$settings['dbpass'];
        $oct->dbhost=$settings['dbhost'];
        $oct->dbname=$settings['dbname'];
        $oct->dbprefix=$settings['dbprefix'];
        $oct->connect();

        require_once "helpers/configsettings.php";
        
        //$oct->showArray($configsettings); die();
        if($oct->getSetting("externaldb", "useexternaldb")==1 && $oct->getSetting("externaldb", "externaldb") != "") {
        //if($configsettings['externaldb']['useexternaldb']['value']==1 && $configsettings['externaldb']['externaldb']['value'] != "") {
            require_once("helpers/externaldb/".$configsettings['externaldb']['externaldb']['value'].".php");
        }
        
        include "scripts/authenticate.php";   
    }
?>
