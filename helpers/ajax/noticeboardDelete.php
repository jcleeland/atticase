<?php
    $id=$oct->cleanpost('id');
    
    if(empty($id)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."noticeboard";
        $query .= "\r\n WHERE id = :id";
        $parameters[':id']=$id;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
