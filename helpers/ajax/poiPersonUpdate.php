<?php
    //print_r($_POST);
    $personId=isset($_POST['personId']) ? $_POST['personId'] : null;
    $fieldname=isset($_POST['fieldName']) ? $_POST['fieldName'] : null;
    $newValue=isset($_POST['value']) ? $_POST['value'] : null;
    $tablename="people";
    
    if(empty($personId)) {
        $output="Incorrect information";
    } else {
        $updates[$fieldname]=$newValue;
        $wheres="id = $personId";
        
        $output=$oct->updateTable($tablename, $updates, $wheres, null, 0);
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
