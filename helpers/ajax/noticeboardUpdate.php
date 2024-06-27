<?php
    //print_r($_POST);
    $tablename="noticeboard";

    $id=$oct->cleanPost('id');
    $title=$oct->cleanPost('title');
    $message=$oct->cleanPost('message');
    $publishDate=$oct->cleanPost('publish_date');
    $expiryDate=$oct->cleanPost('expiry_date');
    $allowComments=($oct->cleanPost('allow_comments')=="on") ? 1 : 0;
    $createdBy=$oct->cleanPost('created_by');
    $published=($oct->cleanPost('published')=="on") ? 1 : 0;
    
    if(empty($id)) {
        $output="Incorrect information";
    } else {
        $updates=array(
            "title"=>$title,
            "message"=>$message,
            "publish_date"=>DateTime::createFromFormat('d/m/Y', $publishDate)->format('Y-m-d'),
            "expiry_date"=>DateTime::createFromFormat('d/m/Y', $expiryDate)->format('Y-m-d'),
            "allow_comments"=>$allowComments,
            "created_by"=>$createdBy,
            "published"=>$published
        );
        $wheres="id = $id";
        
        $output=$oct->updateTable($tablename, $updates, $wheres, $createdBy, 1);
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
