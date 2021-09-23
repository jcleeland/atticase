<?php
    $caseid=isset($_POST['caseId']) ? $_POST['caseId'] : null;
    $output=$oct->getCustomFields($caseid);
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
