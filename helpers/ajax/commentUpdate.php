<?php
    //print_r($_POST);
    $commentId=isset($_POST['commentId']) ? $_POST['commentId'] : null;
    $newValue=isset($_POST['newValue']) ? $_POST['newValue'] : null;
    
    $parameters[":comment_text"]=$newValue;
    $parameters[":comment_id"]=$commentId;
    $conditions="comment_id = :comment_id";
    
    $output=$oct->commentUpdate($parameters, $conditions);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
