<?php
    //print_r($_POST);
    $groupId=isset($_POST['groupId']) ? $_POST['groupId'] : null;
    $versionId=isset($_POST['versionId']) ? $_POST['versionId'] : null;
    $newValue=isset($_POST['newValue']) ? $_POST['newValue'] : null;
    $tablename="version_permissions";
    
    if(empty($groupId) || empty($versionId)) {
        $output="Incorrect information";
    } else {
        //$updates[$fieldName]=$newValue;
        //$wheres="group_id = $groupId";
        //check if this already exists
        $output=$oct->fetch("SELECT * FROM ".$oct->dbprefix."version_permissions WHERE group_id='$groupId' AND version_id='$versionId'");
        $oct->showArray($output);
        if(!$output) {
            $inserts['version_id']=$versionId;
            $inserts['group_id']=$groupId;
            $inserts['enabled']=$newValue;
            $output=$oct->insertTable($tablename, $inserts, 0);
        } else {
            //echo "Updating - $newValue";
            $updates['enabled']=$newValue;
            $wheres="version_id=$versionId AND group_id=$groupId";
            $output=$oct->updateTable($tablename, $updates, $wheres, null, 0);            
        }
        
        
    }
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
