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
$users=$oct->userList(array(), null, null, 0, 1000000000);

//Get a list of case types
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";
$casetypes=$oct->caseTypeList(array(), "1=1", "show_in_list DESC, list_position ASC");

$results=$oct->fetchMany("SELECT task_type, count(*) as total FROM ".$oct->dbprefix."tasks GROUP BY task_type ORDER BY task_type ASC", null, 0, 10000);
$casetypecounts=array();
foreach($results['output'] as $cgcount) {
    $casetypecounts[$cgcount['task_type']]=$cgcount['total'];
}
  
?>
<script src="js/pages/admin/casetypes.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Case Types</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                    <div class="row pb-0 mb-0">
                        <div class="col-sm-4 text-center">
                            <span class='small btn bg-light text-secondary border-bottom rounded p-1' >Name</span>
                        </div>
                        <div class="col-sm-5 text-center">
                            <span class='small btn bg-light text-secondary border-bottom rounded p-1' >Description</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class='small btn bg-light text-secondary border-bottom rounded p-1' >Position</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class='small btn bg-light text-secondary border-bottom rounded p-1' >Show</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class='small btn bg-light text-secondary border-bottom rounded p-1' >Cases</span>
                        </div>
                    </div>
                <div class="form-group overflow-auto p-1 w-100" style="min-height: 40vh; max-height: 60vh" >

<?php
foreach($casetypes['results'] as $casetype) {
    $id=$casetype['tasktype_id'];
?>                
                    <div class="row mb-1">
                        <input type="hidden" name="id[]" value="<?php echo $casetype['tasktype_id'] ?>" />
                        <div class="col-sm-4">
                            <input action="tasktype_name" typeid="<?php echo $id ?>" class="form-control smaller updatetypefield" placeholder="Case Type Name" id="categoryname<?php echo $id ?>" type="text" name="category_name[]" value="<?php echo $casetype['tasktype_name'] ?>" />
                        </div>
                        <div class="col-sm-5">
                            <input action="tasktype_description" typeid="<?php echo $id ?>" class="form-control smaller updatetypefield" placeholder="Case Type Description" id="categorydescrip<?php $id ?>" type="text" name="category_descrip[]" value="<?php echo $casetype['tasktype_description'] ?>" />
                        </div>
                        <div class="col-sm">
                            <input action="list_position" typeid="<?php echo $id ?>" class="form-control smaller updatetypefield" placeholder="Position in list" id="listposition<?php echo $id ?>" type="text" size="2" name="list_position[]" value="<?php echo $casetype['list_position'] ?>" />
                        </div>
                        <div class="col-sm text-center">
                            <input action="show_in_list" typeid="<?php echo $id ?>" class="form-control smaller updatetypefield" placeholder="Show in list?" id="showinlist<?php echo $id ?>" type="checkbox" name="show_in_list[]" <?php if ($casetype['show_in_list']==1) echo "checked" ?> />
                        </div>
                        <div class="col-sm text-center smaller">
                            <?php
                            if(isset($casetypecounts[$casetype['tasktype_id']])) {
                                echo $casetypecounts[$casetype['tasktype_id']];
                            } elseif ($casetype['show_in_list']==1) {
                                echo "0";
                            } else {
                                echo "<span class='btn btn-warning btn-sm' title='This case type can be deleted because there are no cases assigned against it' onClick='deleteCaseType(\"".$id."\")'>Del</span>";
                            }
                            ?>
                        </div>
                    </div>    
<?php
}
?>                
                
                
                
                </div>
                </form>
            </div>
            
            <h4 class="header">Add Case Type</h4>
            <div class="row border rounded centered">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-4 text-center">
                            <span class="admin-headers">Name</span>
                        </div>
                        <div class="col-sm-5 text-center">
                            <span class="admin-headers">Description</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Position</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Show</span>
                        </div>
                        <div class="col-sm text-center">
                            &nbsp;
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2 w-100" >
                       <div class="row mb-1">
                            <div class="col-sm-4">
                                <input action='tasktype_name' class="form-control smaller" placeholder="Case Type Name" id="categoryname" type="text" name="category_name" />
                            </div>
                            <div class="col-sm-5">
                                <input action='tasktype_descrip' class="form-control smaller" placeholder="Description of case type" id="categorydescrip" type="text" name="category_descrip" />
                            </div>
                            <div class="col-sm">
                                <input action='list_position' class="form-control smaller" placeholder="Position in list" id="listposition" type="text" size="2" name="list_position" />
                            </div>
                            <div class="col-sm">
                                <input action='show_in_list' class="form-control smaller text-center" placeholder="Show in list?" id="showinlist" type="checkbox" name="show_in_list" />
                            </div>
                            <div class="col-sm text-center">
                                <span class='btn btn-sm btn-main createtypefield'>Add</span>
                            </div>      
                       </div>          
                </div>            
        </div>
    </div>
</div>

<?php
    //$oct->showArray($casetypes);
?>