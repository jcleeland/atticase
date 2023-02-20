<?php
    //print_r($_POST);
    $resolutionName=isset($_POST['resolutionName']) ? $_POST['resolutionName'] : null;
    $resolutionDescrip=isset($_POST['resolutionDescrip']) ? $_POST['resolutionDescrip'] : null;
    $listPosition=isset($_POST['listPosition']) ? $_POST['listPosition'] : null;
    $showInList=isset($_POST['showInList']) ? $_POST['showInList'] : null;
    
    if(!empty($resolutionName)) {
        $tablename="list_resolution";
        $inserts=array(
            'resolution_name'     =>  $resolutionName,
            'resolution_description'  =>  $resolutionDescrip,
            'list_position'     =>  $listPosition,
            'show_in_list'      =>  $showInList
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"Error - No resolution name provided");
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
