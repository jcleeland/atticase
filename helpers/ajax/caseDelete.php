<?php
    $caseId=isset($_POST['caseId']) ? $_POST['caseId'] : null;
    $settings=$oct->getCookies('OpenCaseTrackerSystem');
    if($caseId && $settings->administrator==1) {
        $deletelist=array();
        $parameters=array(":caseId"=>$caseId);

        //Delete all the history
        $query="DELETE FROM ".$oct->dbprefix."history WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['history']=$results;
        //Delete all the Billing
        $query="DELETE FROM ".$oct->dbprefix."times WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['billing']=$results;
        //Delete all the payments
        $query="DELETE FROM ".$oct->dbprefix."payments WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['payments']=$results;
        //Delete all the invoices
        $query="DELETE FROM ".$oct->dbprefix."invoices WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['invoices']=$results;
        //Delete all the planning
        $query="DELETE FROM ".$oct->dbprefix."strategy WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['strategy']=$results;
        //Delete all the notifications
        $query="DELETE FROM ".$oct->dbprefix."notifications WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['notifications']=$results;
        //Delete all the related
        $query="DELETE FROM ".$oct->dbprefix."related WHERE this_task=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['related']=$results;
        $query="DELETE FROM ".$oct->dbprefix."companion WHERE this_task=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['companion']=$results;
        $query="DELETE FROM ".$oct->dbprefix."master WHERE master_task=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['master']=$results;
        //Delete all the POI connections
        $query="DELETE FROM ".$oct->dbprefix."people_of_interest WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['poi_connections']=$results;

        //Delete the attachments, including the files themselves
        $attachmentsdir=$oct->config['installation']['attachmentdir']['value'];
        $query = "SELECT file_name FROM ".$oct->dbprefix."attachments WHERE task_id=:caseId";
        $results=$oct->fetchMany($query, $parameters);
        $filelist=array();
        foreach($results['output'] as $fileresult) {
            $filelist[]=$attachmentsdir."/".$fileresult['file_name'];
        }
        //$oct->showArray($filelist);
        foreach($filelist as $filename) {
            if(file_exists($filename)) {
                unlink($filename);
            }                
        }        
        $query="DELETE FROM ".$oct->dbprefix."attachments WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['payments']=$results;
        //Delete all the comments
        $query="DELETE FROM ".$oct->dbprefix."comments WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['comments']=$results;
        //Delete from tasktype checks
        $query="DELETE FROM ".$oct->dbprefix."tasktype_checked WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['tasktype_checks']=$results;
        //Delete from reminders (not currently used)
        $query="DELETE FROM ".$oct->dbprefix."reminders WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['reminders']=$results;
        //Delete the custom field connections
        $query="DELETE FROM ".$oct->dbprefix."custom_fields WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['custom_fields']=$results;
        //Delete the case itself         
        $query="DELETE FROM ".$oct->dbprefix."tasks WHERE task_id=:caseId";
        $results=$oct->execute($query, $parameters);
        $deletelist['cases']=$results;
        $message="Case deleted - ".implode(", ", $deletelist);
        //$oct->showArray($deletelist, "Delete List");
        $output=array("results"=>$deletelist, "query"=>"", "parameters"=>"", "count"=>1, "total"=>1);
    } else {
        $output=array("results"=>"No case deleted", "query"=>"", "parameters"=>"", "count"=>0, "total"=>0);
    }

?>
