<?php
//Required fields:
    //Extract the custom fields from the array
    $standardfields=array();
    
    foreach($_POST['newValues'] as $key=>$val) {
        $updates[$key]=$val;
    }
    $userId=intval($_POST['userId']);
    
    $output=false;
    //$oct->updateTable()
    if($return=$oct->updateTable("users", $updates, "user_id=".$userId, $userId, 0)) {
        $output=true;
    } else {
        //$oct->showArray($updates);
        //We have to work out why it failed
        if($return === 0) {
            $output="No changes were made";
        } else {
            $output=false;
        };
        //$output=false;
    }
    
?>
