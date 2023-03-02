<?php
    $orig_name=$_FILES["attachmentFile"]["name"];
    $file_type=$_FILES["attachmentFile"]["type"];
    $file_size=$_FILES["attachmentFile"]["size"];
    $file_location=$_FILES["attachmentFile"]["tmp_name"];
    

    $fileDesc=$_POST['attachmentFileDesc'];
    $caseId=$_POST['caseId'];
    $userId=$_POST['userId'];
    $time=isset($_POST['time']) ? $_POST['time'] : date("U");
    
    mt_srand(make_seed());
    $randval=mt_rand();
    $file_name=$caseId."_".$randval;
    $attachmentDir=$oct->config['installation']['attachmentdir']['value'];
    
    $error=null;
    
    if(!file_exists($file_location)) {
        $error="Uploaded file does not exist [".$file_location."]";
    }
    
    if(!move_uploaded_file($file_location, "$attachmentDir/$file_name")) {
        //Try old style move
        if(!rename($file_location, "$attachmentDir/$file_name")) {
            $error="Could not move file to storage [$attachmentDir/$file_name]";
        } else {
            @chmod("$attachmentDir/$file_name", 0644);
        }
    } else {
        @chmod("$attachmentDir/$file_name", 0644);
    }
    
    if(file_exists("$attachmentDir/$file_name")) {
        //Insert new database entry
        $parameters[":task_id"]=$caseId;
        $parameters[":orig_name"]=$orig_name;
        $parameters[":file_name"]=$file_name;
        $parameters[":file_desc"]=$fileDesc;
        $parameters[":file_type"]=$file_type;
        $parameters[":file_size"]=$file_size;
        $parameters[":added_by"]=$userId;
        $parameters[":date_added"]=$time;
        //$parameters[":attachments_module"]="OCT";
        //$parameters[":url"]=null;
        
        $conditions=null;
        
        $output=$oct->attachmentCreate($parameters, $conditions);        
    }
    
    function make_seed() {
        list($usec, $sec) = explode(' ', microtime());
        return $sec + $usec * 1000000;
    }
    
?>
