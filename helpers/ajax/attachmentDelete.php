<?php
    //print_r($_POST);
    $attachmentId=isset($_POST['attachmentId']) ? $_POST['attachmentId'] : null;

    $parameters[":attachment_id"]=$attachmentId;
    
    $conditions=null;
    
    $output=$oct->attachmentDelete($parameters, $conditions);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
