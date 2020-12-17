<?php
    //print_r($_POST);
    $commentId=isset($_POST['commentId']) ? $_POST['commentId'] : null;

    $parameters[":comment_id"]=$commentId;
    
    $conditions=null;
    
    $output=$oct->commentDelete($parameters, $conditions);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
