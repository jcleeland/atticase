<?php
    session_start();
    error_reporting(E_ALL);
    if(isset($_GET['test']) && $_GET['test']=="yes") {
        $_POST=$_GET;
    }
    if(!isset($_POST['method']) || $_POST['method']=="") die("ERROR No method called");
    require_once("helpers/startup.php");
    
    $functionFile="helpers/ajax/".$_POST['method'].".php";
    if(!file_exists($functionFile)) die("ERROR Function does not exist ($functionFile)");
    
    require_once($functionFile);
    
    echo json_encode($output);
     
?>
