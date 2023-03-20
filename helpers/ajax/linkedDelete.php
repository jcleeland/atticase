<?php
    //print_r($_POST);
    $id=isset($_POST['id']) ? $_POST['id'] : null;
    $linktype=isset($_POST['linkType']) ? $_POST['linkType'] : null;
    
    if(!$id || !$linktype) {
        $output=array("results"=>"Not enough information provided", null, array(), 0, 0);
    } else {
        switch($linktype) {
            case "master":
                $tablename="master";
                $keyname="link_id";
                break;
            case "companion":
                $tablename="companion";
                $keyname="related_id";
                break;
        }
        $parameters[":id"]=$id;
        $query = "DELETE FROM ".$oct->dbprefix.$tablename;
        $query .= "\r\n WHERE ".$keyname." = :id";
        $results=$oct->execute($query, $parameters);
        $output=array("results"=>$results." entries deleted", $query, $parameters, $results, $results);

    }
    
    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
