<?php
    //print_r($_POST);
    $casetypeId=isset($_POST['casetypeId']) ? $_POST['casetypeId'] : null;
    
    if(empty($casetypeId)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."list_tasktype";
        $query .= "\r\n WHERE tasktype_id = :casetypeId";
        $parameters[':casetypeId']=$casetypeId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
