<?php
    $departmentId=$_POST['departmentId'];
    
    $query = "SELECT ".$oct->dbprefix."category_notifications.*, ".$oct->dbprefix."list_category.category_name, ".$oct->dbprefix."list_category.category_descrip, ".$oct->dbprefix."users.real_name, ".$oct->dbprefix."users.email_address";
    $query .= "\r\nFROM ".$oct->dbprefix."category_notifications";
    $query .= "\r\n INNER JOIN ".$oct->dbprefix."list_category ON ".$oct->dbprefix."category_notifications.category_id = ".$oct->dbprefix."list_category.category_id";
    $query .= "\r\n INNER JOIN ".$oct->dbprefix."users ON ".$oct->dbprefix."category_notifications.user_id = ".$oct->dbprefix."users.user_id";
    $query .= "\r\nWHERE ".$oct->dbprefix."category_notifications.category_id = :category_id";
    $query .= "\r\nORDER BY ".$oct->dbprefix."users.real_name, ".$oct->dbprefix."list_category.category_descrip";
    
    $parameters[':category_id']=$departmentId;
    
    $results=$oct->fetchMany($query, $parameters);
    
    $output=array("results"=>$results['output'], "query"=>$query, "parameters"=>$parameters, "count"=>count($results['output']), "total"=>$results['records']);
    //$oct->showArray($return);
    
?>
