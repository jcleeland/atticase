<?php
    $groupId=isset($_POST['groupId']) ? $_POST['groupId'] : null;
    $query2="SELECT *";
    $query2.="\r\nFROM ".$this->dbprefix."version_permissions";
    $query2.="\r\nWHERE group_id=$groupId";
    $query2.="\r\nAND enabled=1";
    $results2=$oct->fetchMany($query2, array(), 0, 1000);
    $output=array();
    foreach($results2['output'] as $row) {
        $output[]=$row['version_id'];    
    }
    
?>
