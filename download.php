<?php
session_start();
//error_reporting(E_ALL);
require_once("helpers/startup.php");

if(isset($_GET['attachmentid'])) {
    $fileinfo=$oct->getAttachment($_GET['attachmentid']);
    $attachmentdir=$oct->getSetting("installation", "attachmentdir");
    $filename=$fileinfo['results']['file_name'];
    $origname=$fileinfo['results']['orig_name'];
    $filetype=$fileinfo['results']['file_type'];
    if(file_exists("$attachmentdir/$filename")) {
        $path="$attachmentdir/$filename";
        $orig_name=urlencode($origname);

        header("Cache-Control: max-age=60"); //Fix for Internet explorer bug
        header("Pragma: public");
        header("Content-type: $filetype");
        header("Content-Disposition: attachment; filename=$origname");
        header("Content-transfer-encoding: binary\n");
        header("Content-length: " . filesize($path) . "\n");
        
        readfile("$path");
    }
    
}
  
?>
