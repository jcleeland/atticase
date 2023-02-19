<?php
    //print_r($_POST);
    $departmentId=isset($_POST['departmentId']) ? $_POST['departmentId'] : null;
    
    if(empty($departmentId)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."list_category";
        $query .= "\r\n WHERE category_id = :categoryId";
        $parameters[':categoryId']=$departmentId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
