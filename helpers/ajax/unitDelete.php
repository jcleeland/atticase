<?php
    //print_r($_POST);
    $unitId=isset($_POST['unitId']) ? $_POST['unitId'] : null;
    
    if(empty($unitId)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."list_unit";
        $query .= "\r\n WHERE unit_id = :unitId OR parent_id = :unitId";
        $parameters[':unitId']=$unitId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
