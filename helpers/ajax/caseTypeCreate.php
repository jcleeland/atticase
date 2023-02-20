<?php
    //print_r($_POST);
    $casetypeName=isset($_POST['casetypeName']) ? $_POST['casetypeName'] : null;
    $casetypeDescrip=isset($_POST['casetypeDescrip']) ? $_POST['casetypeDescrip'] : null;
    $listPosition=isset($_POST['listPosition']) ? $_POST['listPosition'] : null;
    $showInList=isset($_POST['showInList']) ? $_POST['showInList'] : null;
    
    if(!empty($casetypeName)) {
        $tablename="list_tasktype";
        $inserts=array(
            'tasktype_name'     =>  $casetypeName,
            'tasktype_description'  =>  $casetypeDescrip,
            'list_position'     =>  $listPosition,
            'show_in_list'      =>  $showInList
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"Error - No case type name provided");
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
