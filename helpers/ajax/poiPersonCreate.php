<?php
    //print_r($_POST);
    $firstname=isset($_POST['firstname']) ? $_POST['firstname'] : null;
    $lastname=isset($_POST['lastname']) ? $_POST['lastname'] : null;
    $position=isset($_POST['position']) ? $_POST['position'] : null;
    $organisation=isset($_POST['organisation']) ? $_POST['organisation'] : null;
    $phone=isset($_POST['phone']) ? $_POST['phone'] : null;
    $email=isset($_POST['email']) ? $_POST['email'] : null;
    $created=time();
    $modified=time();
    
    if(!empty($firstname) && !empty($lastname)) {
        $tablename="people";
        $inserts=array(
            'firstname'     =>  $firstname,
            'lastname'      =>  $lastname,
            'position'      =>  $position,
            'organisation'  =>  $organisation,
            'phone'         =>  $phone,
            'email'         =>  $email,
            'created'       =>  $created,
            'modified'      =>  $modified,
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"Error - You must provide both a first and a last name");
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
