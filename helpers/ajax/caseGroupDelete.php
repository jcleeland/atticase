<?php
    //print_r($_POST);
    $versionId=isset($_POST['versionId']) ? $_POST['versionId'] : null;
    
    if(empty($versionId)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."list_version";
        $query .= "\r\n WHERE version_id = :versionId";
        $parameters[':versionId']=$versionId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
