<?php
    $caseid=isset($_POST['caseId']) ? $_POST['caseId'] : null;
    $output=$oct->getCase($caseid);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
