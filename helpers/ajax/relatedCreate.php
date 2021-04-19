<?php
    //print_r($_POST);
    $caseId=isset($_POST['caseId']) ? $_POST['caseId'] : null;
    $userId=isset($_POST['userId']) ? $_POST['userId'] : null;
    $relatedCaseId=isset($_POST['comment']) ? $_POST['comment'] : null;
    $time=isset($_POST['time']) ? $_POST['time'] : date("U");
    
    $parameters[":related_task"]=$relatedCaseId;
    $parameters[":user_id"]=$userId;
    $parameters[":this_task"]=$caseId;
    //$parameters[":date_added"]=$time;
    
    $conditions=null;
    
    $output=$oct->relatedCreate($parameters, $conditions);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
