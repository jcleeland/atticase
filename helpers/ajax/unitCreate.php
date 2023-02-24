<?php
    //print_r($_POST);
    $unitDescrip=isset($_POST['unitDescrip']) ? $_POST['unitDescrip'] : null;
    $listPosition=isset($_POST['listPosition']) ? $_POST['listPosition'] : null;
    $showInList=isset($_POST['showInList']) ? $_POST['showInList'] : null;
    
    if(!empty($unitDescrip)) {
        $tablename="list_unit";
        $inserts=array(
            'unit_descrip'  =>  $unitDescrip,
            'list_position' =>  $listPosition,
            'show_in_list'  =>  $showInList,
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"Error - No unit description provided");
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
