<?php
    //print_r($_POST);
    $customFieldName=isset($_POST['customFieldName']) ? $_POST['customFieldName'] : null;
    $customFieldType=isset($_POST['customFieldType']) ? $_POST['customFieldType'] : null;
    $customFieldVisible=isset($_POST['customFieldVisible']) ? $_POST['customFieldVisible'] : null;
    
    if(!empty($customFieldName)) {
        $tablename="custom_field_definitions";
        $inserts=array(
            'custom_field_name' =>  $customFieldName,
            'custom_field_type' =>  $customFieldType,
            'custom_field_visible'  =>  $customFieldVisible,
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"Error - No case type name provided");
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
