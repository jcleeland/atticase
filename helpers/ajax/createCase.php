<?php
//Required fields:
    //Extract the custom fields from the array
    $standardfields=array();
    $customfields=array();
    
    foreach($_POST['newValues'] as $key=>$val) {
        if(substr($key, 0, 7) == "custom_") {
            $customfields[substr($key, 13)]=$val;
        } else {
            $standardfields[$key]=$val;
        }
    }
    
    $userId=$_POST['user_id'];

    $createCase=$oct->createTable("tasks", $standardfields, $userId);
    
    $caseId=$createCase['insertId'];
    
    //Update custom Fields
    // - first, get a list of all existing records for custom fields for this case
    // - then compare that list with the list of updates in this submission
    // - if there are any updates in this submission without a custom_field entry, create it
    // - if there are any updates that already have a custom_field entry, update it and move the old value to "custom_field_old_value"
    
    //$oct->showArray($customfields, "Update to Custom Fields");

    //Get the existing values
    $existing=$oct->getCustomFields($caseId);
    $current=array();
    //$oct->showArray($existing, "Existing Custom Field Values");
    foreach($existing['results'] as $key=>$val) {
        $current[$val['custom_field_definition_id']]=$val['custom_field_value'];
    }
    //$oct->showArray($current, "Current Values");
    
    $missingCustomFields=array();
    foreach($customfields as $key=>$val) {
        //Iterate through any custom fields in this submission
        //Get the old value
        //echo "Doing $key -> $val<br />";
        
        if(isset($current[$key])) {
            //This custom field already has a value, so update it - but only if it has actually changed
            if($current[$key] != $val) {
                $updatearray=array("custom_field_value"=>$val, "custom_field_old_value"=>$current[$key]);
                $updatewheres="task_id=".$caseId." AND custom_field_definition_id=".$key;
                $changedFields[$key]=array("old"=>$current[$key], "new"=>$val);
                //$oct->showArray($updatearray, "Update Array");
                //$oct->showArray($updatewheres, "Update Where Statement");
                $createCustom=$oct->updateTable("custom_fields", $updatearray, $updatewheres, 2, 0);             
            }

        } else {
            //This custom field does not have a value, so create it
            $inserts=array("custom_field_definition_id"=>$key, "task_id"=>$caseId, "custom_field_value"=>$val, "custom_field_old_value"=>"");
            $wheres=array();
            //$oct->showArray($parameters, "INSERT Custom Field Parameters");
            $changedFields[$key]=array("old"=>"", "new"=>$val);
            $createCustom=$oct->insertTable("custom_fields", $inserts, 0);
        }

        //If there is no entry, create one with the new value
        
        
        //If there is an olde entry, update it with the new value
        //Set the new value as "custom_field_value"=$val, "custom_field_old_value"=$oldvalue where "custom_field_definition_id=$key and task_id="case_id"
   
    }
    
    $output=array("insertId"=>$caseId);
    
?>
