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
    session_start();
    
    //Turn off for production installation
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    /** Simple testing
    *   run ajax.php?test=yes
    */
    
    if(isset($_GET['test']) && $_GET['test']=="yes") {
        $_GET['parameters'][':assignedto']=2;
        $_GET['parameters'][':isclosed']=1;
        $_GET['parameters'][':datedue']='1606712841';
        $_GET['order']='date_due+ASC';
        $_GET['first']='0';
        $_GET['last']='9';
        $_GET['conditions']="assigned_to+=+:assignedto+AND+is_closed+!=+:isclosed+AND+date_due+<=+:datedue";
        
        $_POST=$_GET;
    }
    
    
    if(!isset($_POST['method']) && isset($_GET['method'])) $_POST['method']=$_GET['method'];
    if(!isset($_POST['method']) || $_POST['method']=="") die("ERROR No method called");
    require_once("helpers/startup.php");
    $functionFile="helpers/ajax/".$_POST['method'].".php";
    if(!file_exists($functionFile)) die("ERROR Function does not exist ($functionFile)");
    
    require_once($functionFile);
    
    echo json_encode($output);
     
?>
