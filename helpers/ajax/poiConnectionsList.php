<?php
    $personId=isset($_POST['personId']) ? $_POST['personId'] : null;

    $parameters=array(':personId'=>$personId);
    
    $conditions="person_id = :personId";
    
    $order="";
    
    $output=$oct->poiConnectionsList($parameters, $conditions, $order);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
