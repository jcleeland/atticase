<?php
    //print_r($_POST);
    $clientId=isset($_POST['clientId']) ? $_POST['clientId'] : null;
    
    if(empty($clientId) && $clientId !== "0") {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."member_cache";
        $query .= "\r\n WHERE member = :clientId";
        $parameters[':clientId']=$clientId;
        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
