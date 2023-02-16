<?php
    //$oct->showArray($_POST);
    $output="";
    
    //Lets just make sure that there isn't already an entry in the prefs table for this value
    // and if there is, we'll update rather than inssert
    //$oct->showArray($_POST['values']);
    $query="SELECT * FROM ".$oct->dbprefix."prefs WHERE pref_name='".$_POST['values']['pref_name']."'";
    $result=$oct->execute($query, array());
    if($result > 0) {
        $output=$oct->updateTable("prefs", $_POST['values'], "pref_name='".$_POST['values']['pref_name']."'", 0);
    } else {
        $output=$oct->insertTable("prefs", $_POST['values'], 1);    
    }
    

?>
