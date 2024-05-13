<?php
    //print_r($_POST);
    $identifier=isset($_POST['identifier']) ? $_POST['identifier'] : null;
    
    if(empty($identifier) && $identifier !== "0") {
        $output = array("results"=>"Error - Not enough data provided");
    } else {
        $surname=isset($_POST['surname']) ? $_POST['surname'] : null;
        $pref_name=isset($_POST['pref_name']) ? $_POST['pref_name'] : null;
        $started=isset($_POST['started']) ? $_POST['started'] : null;
        $primary_key=isset($_POST['primary_key']) ? $_POST['primary_key'] : null;
        $data=isset($_POST['data']) ? @openssl_encrypt($_POST['data'], 'aes-128-cbc', 'ct2016') : null;
        $modified=time();

        $tablename="member_cache";
        $updates=array(
            'pref_name'     =>  $pref_name,
            'surname'       =>  $surname,
            'joined'        =>  $started,
            'primary_key'   =>  $primary_key,
            'data'          =>  $data,
            'modified'      =>  $modified,
            'subs_paid_to'  =>  '1970-01-01', //This can go once the database is fixed
            'paying_emp'    =>  ''
        );
          
        $wheres="member = '$identifier'";
        
        $results=$oct->updateTable($tablename, $updates, $wheres, null, 0);
        $output=array("results"=>"New row inserted with id ".$identifier, "query"=>null, "parameters"=>$inserts, "count"=>1, "total"=>1, "insertid"=>$identifier, "response"=>$results);
    }
    
?>
