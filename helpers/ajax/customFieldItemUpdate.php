<?php
    //print_r($_POST);
    $customFieldListId=isset($_POST['customFieldListId']) ? $_POST['customFieldListId'] : null;
    $customFieldValue=isset($_POST['customFieldValue']) ? $_POST['customFieldValue'] : null;
    $customFieldName=isset($_POST['customFieldName']) ? $_POST['customFieldName'] : null;
    $tablename="custom_field_lists";
    
    if(empty($customFieldListId)) {
        $output="Insufficient information";
    } else {
        $updates[$customFieldName]=$customFieldValue;
        $wheres="custom_field_list_id = $customFieldListId";
        
        $output=$oct->updateTable($tablename, $updates, $wheres, null, 0);
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
