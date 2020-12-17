<?php
    //print_r($_POST);
    $attachmentId=isset($_POST['attachmentId']) ? $_POST['attachmentId'] : null;
    $newValue=isset($_POST['newValue']) ? $_POST['newValue'] : null;
    
    $parameters[":file_desc"]=$newValue;
    $parameters[":attachment_id"]=$attachmentId;
    $conditions="attachment_id = :attachment_id";
    
    $output=$oct->attachmentUpdate($parameters, $conditions);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
