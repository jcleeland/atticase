<?php
    //print_r($_POST);
    $customTextId=isset($_POST['customTextId']) ? $_POST['customTextId'] : null;
    $fieldname=isset($_POST['fieldName']) ? $_POST['fieldName'] : null;
    $newValue=isset($_POST['value']) ? $_POST['value'] : null;
    $tablename="custom_texts";
    
    if(empty($customTextId)) {
        $output="Incorrect information";
    } else {
        $updates[$fieldname]=$newValue;
        $wheres="custom_text_id = $customTextId";
        
        $output=$oct->updateTable($tablename, $updates, $wheres, null, 0);
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
