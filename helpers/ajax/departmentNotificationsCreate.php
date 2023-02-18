<?php
    //print_r($_POST);
    $departmentId=isset($_POST['departmentId']) ? $_POST['departmentId'] : null;
    $userId=isset($_POST['userId']) ? $_POST['userId'] : null;
    $notifyNew=isset($_POST['notifyNew']) ? $_POST['notifyNew'] : null;
    $notifyChange=isset($_POST['notifyChange']) ? $_POST['notifyChange'] : null;
    $notifyDel=isset($_POST['notifyDel']) ? $_POST['notifyDel'] : null;
    $tablename="category_notifications";
    $inserts=array(
        'category_id'   =>  $departmentId,
        'user_id'       =>  $userId,
        'notify_new'    =>  $notifyNew,
        'notify_change' =>  $notifyChange,
        'notify_close'    =>  $notifyDel
    );
    
    //TODO: Check that this user doesn't already have an entry
    $query = "SELECT * FROM ".$oct->dbprefix.$tablename." WHERE category_id=".$departmentId." AND user_id=".$userId;
    $results=$oct->fetch($query);
    //$oct->showArray($results);
    if(!$results) {
        $results=$oct->insertTable($tablename, $inserts);
        
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>null, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"There is already an entry for this user", "query"=>$query, "parameters"=>null, "count"=>0, "total"=>0);
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
