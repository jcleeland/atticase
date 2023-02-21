<?php
    //print_r($_POST);
    $modifyAction=isset($_POST['modifyAction']) ? $_POST['modifyAction'] : null;
    $customText=isset($_POST['customText']) ? $_POST['customText'] : null;
    
    if(!empty($modifyAction)) {
        $tablename="custom_texts";
        $inserts=array(
            'modify_action' =>  $modifyAction,
            'custom_text' =>  $customText,
            'custom_text_lang' => 'en',
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"Error - No modify action provided");
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
