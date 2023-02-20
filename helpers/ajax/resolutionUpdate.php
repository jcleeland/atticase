<?php
    //print_r($_POST);
    $resolutionId=isset($_POST['resolutionId']) ? $_POST['resolutionId'] : null;
    $fieldname=isset($_POST['fieldName']) ? $_POST['fieldName'] : null;
    $newValue=isset($_POST['value']) ? $_POST['value'] : null;
    $tablename="list_resolution";
    
    if(empty($resolutionId)) {
        $output="Incorrect information";
    } else {
        $updates[$fieldname]=$newValue;
        $wheres="resolution_id = $resolutionId";
        
        $output=$oct->updateTable($tablename, $updates, $wheres, null, 0);
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
