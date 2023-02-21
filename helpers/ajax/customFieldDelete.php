<?php
    //print_r($_POST);
    $customFieldDefinitionId=isset($_POST['customFieldDefinitionId']) ? $_POST['customFieldDefinitionId'] : null;
    
    if(empty($customFieldDefinitionId)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."custom_field_definitions";
        $query .= "\r\n WHERE custom_field_definition_id = :customFieldDefinitionId";
        $parameters[':customFieldDefinitionId']=$customFieldDefinitionId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
