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
    $unitDescrip=isset($_POST['unitDescrip']) ? $_POST['unitDescrip'] : null;
    $listPosition=isset($_POST['listPosition']) ? $_POST['listPosition'] : null;
    $showInList=isset($_POST['showInList']) ? $_POST['showInList'] : null;
    $parentId=isset($_POST['parentId']) && !empty($_POST['parentId']) ? $_POST['parentId'] : 0;
    
    if(!empty($unitDescrip)) {
        $tablename="list_unit";
        $inserts=array(
            'unit_descrip'  =>  $unitDescrip,
            'list_position' =>  $listPosition,
            'show_in_list'  =>  $showInList,
            'parent_id'     =>  $parentId,
        );
        //$oct->showArray($inserts, "Inserts");    
        
        $results=$oct->insertTable($tablename, $inserts);
        $output=array("results"=>"New row inserted with id ".$results, "query"=>null, "parameters"=>$inserts, "count"=>$results, "total"=>$results);
    } else {
        $output=array("results"=>"Error - No unit description provided");
    }

    
    //echo "<pre>"; print_r($output); echo "</pre>";
?>
