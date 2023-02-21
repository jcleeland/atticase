<?php
    //print_r($_POST);
    $taskId=isset($_POST['taskId']) ? $_POST['taskId'] : null;
    $personId=isset($_POST['personId']) ? $_POST['personId'] : null;
    
    if(empty($taskId) || empty($personId)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."people_of_interest";
        $query .= "\r\n WHERE task_id = :taskId AND person_id = :personId";
        $parameters[':taskId']=$taskId;
        $parameters[':personId']=$personId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
