<?php
    //print_r($_POST);
    $caseId=isset($_POST['caseId']) ? $_POST['caseId'] : null;
    $userId=isset($_POST['userId']) ? $_POST['userId'] : null;
    $commentText=isset($_POST['comment']) ? $_POST['comment'] : null;
    $time=isset($_POST['time']) ? $_POST['time'] : date("U");
    
    $parameters[":comment_text"]=$commentText;
    $parameters[":user_id"]=$userId;
    $parameters[":task_id"]=$caseId;
    $parameters[":date_added"]=$time;
    
    $conditions=null;
    
    $output=$oct->commentCreate($parameters, $conditions);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
