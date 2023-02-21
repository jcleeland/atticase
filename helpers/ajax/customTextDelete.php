<?php
    //print_r($_POST);
    $customTextId=isset($_POST['customTextId']) ? $_POST['customTextId'] : null;
    
    if(empty($customTextId)) {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $query = "DELETE FROM ".$oct->dbprefix."custom_texts";
        $query .= "\r\n WHERE custom_text_id = :customTextId";
        $parameters[':customTextId']=$customTextId;

        $results=$oct->execute($query, $parameters);
        
        $output=array("results"=>$results." rows deleted", "query"=>$query, "parameters"=>$parameters, "count"=>$results, "total"=>$results);
        
    }
    
?>
