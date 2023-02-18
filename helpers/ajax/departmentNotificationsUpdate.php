<?php
    //print_r($_POST);
    $departmentId=isset($_POST['departmentId']) ? $_POST['departmentId'] : null;
    $userId=isset($_POST['userId']) ? $_POST['userId'] : null;
    $fieldName=isset($_POST['name']) ? $_POST['name'] : null;
    $newValue=isset($_POST['value']) ? $_POST['value'] : null;
    $tablename="category_notifications";
    
    if(empty($departmentId) || empty($userId)) {
        $output="Incorrect information";
    } else {
        $updates[$fieldName]=$newValue;
        $wheres="category_id = $departmentId AND user_id= $userId";
        
        $output=$oct->updateTable($tablename, $updates, $wheres, null, 0);
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
