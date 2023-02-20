<?php
    //print_r($_POST);
    $resolutionId=isset($_POST['resolutionId']) ? $_POST['resolutionId'] : null;
    
    if(empty($resolutionId)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."list_resolution";
        $query .= "\r\n WHERE resolution_id = :resolutionId";
        $parameters[':resolutionId']=$resolutionId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
