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

        $oct->cookiePrefix=$oct->getSetting('installation', 'cookiePrefix');
        $cookieStatusName=$oct->cookiePrefix."Status";
        $cookieSystemName=$oct->cookiePrefix."System";
        
        //$oct->showArray($configsettings); die();
        if($oct->getSetting("externaldb", "useexternaldb")==1 && $oct->getSetting("externaldb", "externaldb") != "") {
        //if($configsettings['externaldb']['useexternaldb']['value']==1 && $configsettings['externaldb']['externaldb']['value'] != "") {
            require_once("helpers/externaldb/".$configsettings['externaldb']['externaldb']['value'].".php");
        }
        
        include "scripts/authenticate.php";   
    }
?>
