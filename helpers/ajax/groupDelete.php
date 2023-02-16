<?php
    //print_r($_POST);
    $groupId=isset($_POST['groupId']) ? $_POST['groupId'] : null;

    $parameters[":group_id"]=$groupId;
    
    $conditions=null;
    
    $output=$oct->groupDelete($parameters, $conditions);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
