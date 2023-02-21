<?php
    //print_r($_POST);
    $customFieldDefinitionId=isset($_POST['customFieldDefinitionId']) ? $_POST['customFieldDefinitionId'] : null;
    $fieldname=isset($_POST['fieldName']) ? $_POST['fieldName'] : null;
    $newValue=isset($_POST['value']) ? $_POST['value'] : null;
    $tablename="custom_field_definitions";
    
    if(empty($customFieldDefinitionId)) {
        $output="Incorrect information";
    } else {
        $updates[$fieldname]=$newValue;
        $wheres="custom_field_definition_id = $customFieldDefinitionId";
        
        $output=$oct->updateTable($tablename, $updates, $wheres, null, 0);
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
