<?php
    //print_r($_POST);
    $groupId=isset($_POST['groupId']) ? $_POST['groupId'] : null;
    $fieldName=isset($_POST['fieldName']) ? $_POST['fieldName'] : null;
    $newValue=isset($_POST['newValue']) ? $_POST['newValue'] : null;
    $tablename="groups";
    
    if(empty($groupId) || empty($fieldName)) {
        $output="Incorrect information";
    } else {
        $updates[$fieldName]=$newValue;
        $wheres="group_id = $groupId";
        
        $output=$oct->updateTable($tablename, $updates, $wheres, null, 0);
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
