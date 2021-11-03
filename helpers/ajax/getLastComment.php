<?php
    //print_r($_POST);
    $caseid=isset($_POST['caseid']) ? $_POST['caseid'] : null;
    $output=$oct->getLastComment($caseid);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
