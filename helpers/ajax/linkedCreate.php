<?php
    //print_r($_POST);
    $taskId=isset($_POST['taskId']) ? $_POST['taskId'] : null;
    $linkedId=isset($_POST['linkedId']) ? $_POST['linkedId'] : null;
    $linkType=isset($_POST['linkType']) ? $_POST['linkType'] : null;
    
    if(!$linkType || !$taskId || !$linkedId) {
        $output=array("results"=>"Not enough information provided", null, array(), 0, 0);
    } else {
        $parameters[":task_id"]=$taskId;
        $parameters[":linked_id"]=$linkedId;
        
        $conditions=null;
        
        $output=$oct->linkedCreate($linkType, $parameters);
    }
    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
