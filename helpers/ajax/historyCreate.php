<?php
    //print_r($_POST);
    $taskId=isset($_POST['taskId']) ? $_POST['taskId'] : null;
    $userId=isset($_POST['userId']) ? $_POST['userId'] : null;
    $eventDate=isset($_POST['eventDate']) ? $_POST['eventDate'] : date("U");
    $eventType=isset($_POST['eventType']) ? $_POST['eventType'] : null;
    $fieldChanged=isset($_POST['fieldChanged']) ? $_POST['fieldChanged'] : '';
    $oldValue=isset($_POST['oldValue']) ? $_POST['oldValue'] : '';
    $newValue=isset($_POST['newValue']) ? $_POST['newValue'] : '';
    
    
    $parameters[":task_id"]=$taskId;
    $parameters[":user_id"]=$userId;
    $parameters[":event_date"]=$eventDate;
    $parameters[":event_type"]=$eventType;
    $parameters[":field_changed"]=$fieldChanged;
    $parameters[":new_value"]=$newValue;
    $parameters[":old_value"]=$oldValue;
    
    $conditions=null;
    
    $output=$oct->historyCreate($parameters);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
