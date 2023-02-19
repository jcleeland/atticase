<?php
    //print_r($_POST);
    $departmentName=isset($_POST['departmentName']) ? $_POST['departmentName'] : null;
    $departmentDescrip=isset($_POST['departmentDescrip']) ? $_POST['departmentDescrip'] : null;
    $groupIn=isset($_POST['groupIn']) ? $_POST['groupIn'] : 0;
    $listpos=isset($_POST['listpos']) ? $_POST['listpos'] : 0;
    $showin=isset($_POST['showin']) ? $_POST['showin'] : 0;
    $parentId=isset($_POST['parentId']) ? $_POST['parentId'] : 0;
    
    if(!empty($departmentName)) {
        $tablename="list_category";
        $inserts=array(
            'category_name' =>  $departmentName,
            'category_descrip' =>   $departmentDescrip,
            'category_owner'    =>  $groupIn,
            'list_position' =>  $listpos,
            'show_in_list'  =>  $showin
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"Error - No department name provided");
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
