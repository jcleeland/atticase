<?php
    //print_r($_POST);
    $personId=isset($_POST['personId']) ? $_POST['personId'] : null;
    
    if(empty($personId)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."people";
        $query .= "\r\n WHERE id = :personId";
        $parameters[':personId']=$personId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
