<?php
    //print_r($_POST);
    $customFieldDefinitionId=isset($_POST['customFieldDefinitionId']) ? $_POST['customFieldDefinitionId'] : null;
    $customFieldValue=isset($_POST['customFieldValue']) ? $_POST['customFieldValue'] : null;
    $customFieldOrder=isset($_POST['customFieldOrder']) ? $_POST['customFieldOrder'] : null;
    
    if(!empty($customFieldValue)) {
        $tablename="custom_field_lists";
        $inserts=array(
            'custom_field_definition_id' =>  $customFieldDefinitionId,
            'custom_field_value' =>  $customFieldValue,
            'custom_field_order'  =>  $customFieldOrder,
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>1, "total"=>1, "insertId"=>$results);
    } else {
        $output=array("results"=>"Error - No custom field value provided");
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
