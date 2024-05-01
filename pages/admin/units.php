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
$units=$oct->unitList(array(), $oct->dbprefix."list_unit.parent_id < 1", null, 0, 10000000);
//$oct->showArray($units, "Units");
$parentlist=$oct->buildSelectList($units['results'], array('class'=>'form-control smaller', 'action'=>'parent_id', 'unitid'=>'replaceunitid', 'name'=>'parent_id[]'), 'unit_id', 'unit_descrip', null, 'None', false);
$childrenunits=$oct->unitList(array(), $oct->dbprefix."list_unit.parent_id > 0", "parent_id", 0, 10000000);
$children=array();
foreach($childrenunits['results'] as $cl) {
    $children[$cl['parent_id']][]=$cl;
}
//$oct->showArray($children, "Children");
/*$results=$oct->fetchMany("SELECT product_version, count(*) as total FROM ".$oct->dbprefix."tasks GROUP BY product_version ORDER BY product_version ASC", null, 0, 10000);
$casegroupcounts=array();
foreach($results['output'] as $cgcount) {
    $casegroupcounts[$cgcount['product_version']]=$cgcount['total'];
}*/
?>
<script src="js/pages/admin/units.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Units</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-7">
                            Description
                        </div>
                        <div class="col-sm-1">
                            Position
                        </div>
                        <div class="col-sm">
                            Parent
                        </div>
                        <div class="col-sm text-center">
                            Show
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto m-2 p-2" style="max-height: 600px">
                
<?php
foreach($units['results'] as $unit) {
    $id=$unit['unit_id'];

?>                
                    <div class="row mb-1">
                        <input type="hidden" name="id[]" value="<?php echo $id ?>" />
                        <div class="col-sm-7">
                            <input action='unit_descrip' unitid="<?php echo $id ?>" class="form-control smaller updateunitfield" placeholder="Unit description" id="unitdescrip<?php echo $id ?>" type="text" name="version_name[]" value="<?php echo $unit['unit_descrip'] ?>" />
                        </div>
                        <div class="col-sm-1">
                            <input action='list_position' unitid="<?php echo $id ?>" class="form-control smaller updateunitfield w-100" placeholder="Position in list" id="listposition<?php echo $id ?>" type="text" size="2" name="list_position[]" value="<?php echo $unit['list_position'] ?>" />
                        </div>
                        <div class="col-sm">
                            <?php echo str_replace("smaller", "smaller updateunitfield", str_replace("replaceunitid", $id, $parentlist)) ?>
                        </div>
                        <div class="col-sm">
                            <input action='show_in_list' unitid="<?php echo $id ?>" class="form-control smaller updateunitfield" placeholder="Show in list?" id="showinlist<?php echo $id ?>" type="checkbox" name="show_in_list[]" <?php if ($unit['show_in_list']==1) echo "checked" ?> />
                        </div>
                        <div class="col-sm text-center smaller">
                            <?php 
                            if(isset($casegroupcounts[$id])) {
                                echo $casegroupcounts[$id];
                            } else {
                                echo "<span class='btn btn-warning btn-sm' title='This unitp can be deleted because there are no cases assigned against it' onClick='deleteUnit(\"".$id."\")'>Del</span>";
                            }
                            ?>
                        </div>
                    </div>    
<?php
    if(isset($children[$id])) {
        //$oct->showArray($children[$id]);
        foreach($children[$id] as $child) {
            $cid=$child['unit_id'];
            $pid=$child['parent_id'];
            $thisparentselect=str_replace("replaceunitid", $cid, $parentlist);
            $thisparentselect=str_replace("smaller", "smaller updateunitfield", $thisparentselect);
            $thisparentselect=str_replace("option value='$pid'", "option value='$pid' selected", $thisparentselect);
            $thisparentselect=str_replace("option value=''", "option value='0' selected", $thisparentselect);
            
            ?>
                     <div class="row mb-1">
                        <input type="hidden" name="id[]" value="<?php echo $cid ?>" />
                        <div class="col-sm-1"></div>
                        <div class="col-sm-6">
                            <input action='unit_descrip' unitid="<?php echo $cid ?>" class="form-control smaller updateunitfield" placeholder="Unit description" id="unitdescrip<?php echo $cid ?>" type="text" name="version_name[]" value="<?php echo $child['unit_descrip'] ?>" />
                        </div>
                        <div class="col-sm-1">
                            <input action='list_position' unitid="<?php echo $cid ?>" class="form-control smaller updateunitfield w-100" placeholder="Position in list" id="listposition<?php echo $cid ?>" type="text" size="2" name="list_position[]" value="<?php echo $child['list_position'] ?>" />
                        </div>
                        <div class="col-sm">
                            <?php echo $thisparentselect; ?>
                        </div>
                        <div class="col-sm">
                            <input action='show_in_list' unitid="<?php echo $cid ?>" class="form-control smaller updateunitfield" placeholder="Show in list?" id="showinlist<?php echo $cid ?>" type="checkbox" name="show_in_list[]" <?php if ($child['show_in_list']==1) echo "checked" ?> />
                        </div>
                        <div class="col-sm text-center smaller">
                            <?php 
                            if(isset($casegroupcounts[$cid])) {
                                echo $casegroupcounts[$cid];
                            } else {
                                echo "<span class='btn btn-warning btn-sm' title='Thisunit can be deleted because there are no cases assigned against it' onClick='deleteUnit(\"".$cid."\")'>Del</span>";
                            }
                            ?>
                        </div>
                    </div>            
            <?php
        }
        
    }
}
?>                
                
                </div>
                </form>
            </div>
            <h4 class="header">Add Unit</h4>
            <div class="row border rounded centered">
                <div class="p-2 w-100">
                <div class="row mb-1">
                    <div class="col-sm-7">
                        Description
                    </div>
                    <div class="col-sm-1">
                        Position
                    </div>
                    <div class="col-sm">
                        Parent
                    </div>
                    <div class="col-sm text-center">
                        Show
                    </div>
                    <div class="col-sm">
                    </div>
                </div>
                   
                </div>
                <div class="form-group overflow-auto m-2 p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-7">
                            <input action='unit_descrip' class="form-control smaller" placeholder="Unit description" id="unitdescrip" type="text" name="unit_descrip" />
                        </div>
                        <div class="col-sm-1">
                            <input action='list_position' class="form-control smaller w-100" placeholder="Position in list" id="listposition" type="text" size="2" name="list_position" />
                        </div>
                        <div class="col-sm">
                            <?php echo str_replace("class", "id='parentid' class", $parentlist) ?>
                        </div>
                        <div class="col-sm">
                            <input action='show_in_list' class="form-control smaller" placeholder="Show in list?" id="showinlist" type="checkbox" name="show_in_list" />
                        </div>
                        <div class="col-sm text-center">
                            <span class='btn btn-sm btn-main createunit'>Add</span>
                        </div>      
                    </div>          
                </div>
            </div>
        </div>            
        </div>
    </div>
</div>  