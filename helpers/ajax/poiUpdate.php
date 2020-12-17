<?php
    //print_r($_POST);
    $poiId=isset($_POST['poiId']) ? $_POST['poiId'] : null;
    $newValue=isset($_POST['newValue']) ? $_POST['newValue'] : null;
    
    $parameters[":comment"]=$newValue;
    $parameters[":id"]=$poiId;
    $conditions="id = :id";
    
    $output=$oct->poiUpdate($parameters, $conditions);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
