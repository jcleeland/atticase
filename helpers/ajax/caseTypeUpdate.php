<?php
    //print_r($_POST);
    $taskId=isset($_POST['taskId']) ? $_POST['taskId'] : null;
    $fieldname=isset($_POST['fieldName']) ? $_POST['fieldName'] : null;
    $newValue=isset($_POST['value']) ? $_POST['value'] : null;
    $tablename="list_tasktype";
    
    if(empty($taskId)) {
        $output="Incorrect information";
    } else {
        $updates[$fieldname]=$newValue;
        $wheres="tasktype_id = $taskId";
        
        $output=$oct->updateTable($tablename, $updates, $wheres, null, 0);
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
