<?php
    //print_r($_POST);
    $departmentId=isset($_POST['departmentId']) ? $_POST['departmentId'] : null;
    $userId=isset($_POST['userId']) ? $_POST['userId'] : null;
    
    if(empty($departmentId) || empty($userId)) {
        $output = array("results"=>"Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."category_notifications";
        $query .= "\r\n WHERE category_id = :departmentId AND user_id = :userId";
        $parameters[':departmentId']=$departmentId;
        $parameters[':userId']=$userId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
