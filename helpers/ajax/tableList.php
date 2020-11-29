<?php
    //print_r($_POST);
    $first=isset($_POST['first']) ? $_POST['first'] : 0;
    $last=isset($_POST['last']) ? $_POST['last'] : 1000000000;
    $tablename=isset($_POST['tablename']) ? $_POST['tablename'] : null;
    $joins=isset($_POST['joins']) ? $_POST['joins'] : null;
    $select = isset($_POST['select']) ? $_POST['select'] : "*";
    $parameters=isset($_POST['parameters']) ? $_POST['parameters'] : null;
    $conditions=isset($_POST['conditions']) ? $_POST['conditions'] : null;
    $order=isset($_POST['order']) ? $_POST['order'] : null;
    $output=$oct->tableList($tablename, $joins, $select, $parameters, $conditions, $order, $first, $last);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
