<?php
    session_start();
    error_reporting(E_ALL);
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
    if(!isset($_POST['method']) || $_POST['method']=="") die("ERROR No method called");
    require_once("helpers/startup.php");
    
    $functionFile="helpers/ajax/".$_POST['method'].".php";
    if(!file_exists($functionFile)) die("ERROR Function does not exist ($functionFile)");
    
    require_once($functionFile);
    
    echo json_encode($output);
     
?>
