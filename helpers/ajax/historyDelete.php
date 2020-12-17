<?php
    //print_r($_POST);
    $historyId=isset($_POST['historyId']) ? $_POST['historyId'] : null;

    $parameters[":history_id"]=$historyId;
    
    $conditions=null;
    
    $output=$oct->historyDelete($parameters, $conditions);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
