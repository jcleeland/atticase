<?php
    //print_r($_POST);
    $caseId=isset($_POST['caseId']) ? $_POST['caseId'] : null;
    $poiId=isset($_POST['poiId']) ? $_POST['poiId'] : null;
    $commentText=isset($_POST['comment']) ? $_POST['comment'] : null;
    $time=isset($_POST['time']) ? $_POST['time'] : date("U");
    
    $inserts['comment']=$commentText;
    $inserts['person_id']=$poiId;
    $inserts['task_id']=$caseId;
    $inserts['created']=$time;
    $inserts['modified']=$time;
    
    $tablename="people_of_interest";
    
    
    $results=$oct->insertTable($tablename, $inserts);
    $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>1, "total"=>1, "insertid"=>$results);
    return $output;
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
