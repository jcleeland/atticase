<?php
    //print_r($_POST);
    $customFieldListId=isset($_POST['customFieldListId']) ? $_POST['customFieldListId'] : null;
    $customFieldDefinitionId=isset($_POST['customFieldDefinitionId']) ? $_POST['customFieldDefinitionId'] : null;
    
    if(empty($customFieldListId) && empty($customFieldDefinitionId)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else if(!empty($customFieldListId)){
        $query = "DELETE FROM ".$oct->dbprefix."custom_field_lists";
        $query .= "\r\n WHERE custom_field_list_id = :customFieldListId";
        $parameters[':customFieldListId']=$customFieldListId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    } else if(!empty($customFieldDefinitionId)){
        $query = "DELETE FROM ".$oct->dbprefix."custom_field_lists";
        $query .= "\r\n WHERE custom_field_definition_id = :customFieldDefinitionId";
        $parameters[':customFieldDefinitionId']=$customFieldDefinitionId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
    }
    
?>
