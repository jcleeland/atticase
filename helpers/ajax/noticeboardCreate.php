<?php
    $title=$oct->cleanPost('title');
    $message=$oct->cleanPost('message');
    $publishDate=$oct->cleanPost('publish_date');
    $expiryDate=$oct->cleanPost('expiry_date');
    $allowComments=$oct->cleanPost('allow_comments')=="on" ? 1 : 0;
    $createdBy=$oct->cleanPost('created_by');
    $published=$oct->cleanPost('published')=="on" ? 1 : 0;
        
    if(!empty($title) && !empty($message) && !empty($publishDate) && !empty($expiryDate) && !empty($createdBy)){
        $tablename="noticeboard";
        $inserts=array(
            "title"=>$title,
            "message"=>$message,
            "publish_date"=>DateTime::createFromFormat('d/m/Y', $publishDate)->format('Y-m-d'),
            "expiry_date"=>DateTime::createFromFormat('d/m/Y', $expiryDate)->format('Y-m-d'),
            "allow_comments"=>$allowComments,
            "created_by"=>$createdBy,
            "published"=>$published
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"Error - Not all required fileds complete", "post"=>$_POST);
    }

?>
