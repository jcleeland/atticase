<?php
/*
 * Copyright [2022] [Jason Alexander Cleeland, Melbourne Australia]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

    //print_r($_POST);
    $caseId=isset($_POST['caseId']) ? $_POST['caseId'] : null;
    $userId=isset($_POST['userId']) ? $_POST['userId'] : null;
    
    $inserts['user_id']=$userId;
    $inserts['task_id']=$caseId;
    
    $tablename="notifications";
    
    
    $results=$oct->insertTable($tablename, $inserts);
    $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>1, "total"=>1, "insertid"=>$results);
    return $output;
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
