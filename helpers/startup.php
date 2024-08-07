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
    /* use Microsoft\Graph\Graph;
    use Microsoft\Graph\Model; */        

    //All the things needed to get opencasetracker scripts connected and up and running
    if(!file_exists(__DIR__."/../config/config.php")) {
        //There's no config file - so we haven't even installed yet!
        require_once __DIR__."/../helpers/oct.php";
        $oct=new oct;
        include(__DIR__."/../helpers/initial.php");
        die();
    } else {
        require_once __DIR__.'/../config/config.php';
        require_once __DIR__."/../helpers/oct.php";
        $oct=new oct;
        $oct->dbuser=$settings['dbuser'];
        $oct->dbpass=$settings['dbpass'];
        $oct->dbhost=$settings['dbhost'];
        $oct->dbname=$settings['dbname'];
        $oct->dbprefix=$settings['dbprefix'];
        $output=$oct->connect();
        
        if(!$output) {
            //Database not available
            $message="Could not find database with the name ".$settings['dbname'];
            include(__DIR__."/initial.php");
            die();
        }

        //Check to see if there are any tables in the database - because if there's a database, but no tables, then we need to create them
        $sql="SHOW TABLES";
        $result=$oct->fetchMany($sql);
        
        if((isset($result['records']) && $result['records']==0) || !isset($result['records'])) {
            //There is a database, but there are no tables, so we need to create an empty database
            require_once(__DIR__."/initialise_db.php");
            die();
        }

        require_once __DIR__."/configsettings.php";
        
        $oct->cookiePrefix=$oct->getSetting('installation', 'cookiePrefix');
        $cookieStatusName=$oct->cookiePrefix."Status";
        $cookieSystemName=$oct->cookiePrefix."System";
        
        //$oct->showArray($configsettings); die();
        if($oct->getSetting("externaldb", "useexternaldb")==1 && $oct->getSetting("externaldb", "externaldb") != "") {
        //if($configsettings['externaldb']['useexternaldb']['value']==1 && $configsettings['externaldb']['externaldb']['value'] != "") {
            $oct->externalDb=true;
            require_once(__DIR__."/../helpers/externaldb/".$configsettings['externaldb']['externaldb']['value'].".php");
        } else {
            $oct->externalDb=false;
        }
        
        //Now it's time to log in
        include __DIR__."/../scripts/authenticate.php";   

    }
?>
