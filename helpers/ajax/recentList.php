<?php
    //print_r($_POST);
    $first=isset($_POST['first']) ? $_POST['first'] : 0;
    $last=isset($_POST['last']) ? $_POST['last'] : 1000000000;
    $parameters=isset($_POST['parameters']) ? $_POST['parameters'] : array();
    $conditions=isset($_POST['conditions']) ? $_POST['conditions'] : null;
    $order=isset($_POST['order']) ? $_POST['order'] : null;
    //error_reporting(E_ALL);
    $output=$oct->recentList($parameters, $conditions, $order, $first, $last);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
