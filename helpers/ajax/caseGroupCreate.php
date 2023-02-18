<?php
    //print_r($_POST);
    $versionName=isset($_POST['versionName']) ? $_POST['versionName'] : null;
    $listPosition=isset($_POST['listPosition']) ? $_POST['listPosition'] : null;
    $showInList=isset($_POST['showInList']) ? $_POST['showInList'] : null;
    $isEnquiry=isset($_POST['isEnquiry']) ? $_POST['isEnquiry'] : null;
    
    if(!empty($versionName)) {
        $tablename="list_version";
        $inserts=array(
            'version_name'  =>  $versionName,
            'list_position' =>  $listPosition,
            'show_in_list'  =>  $showInList,
            'is_enquiry'    =>  $isEnquiry,
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"Error - No category name provided");
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
