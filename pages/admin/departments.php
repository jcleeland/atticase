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
$users=$oct->userList(array(), "account_enabled=1", null, 0, 1000000000);

//Get a list of departments
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";
$departments=$oct->departmentList(array(), null, null, 0, 10000000);
$parents=$oct->departmentList(array(), $oct->dbprefix."list_category.parent_id < 1", null, 0, 10000000);
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($departments); echo "</pre>";
$results=$oct->fetchMany("SELECT product_category, count(*) as total FROM ".$oct->dbprefix."tasks GROUP BY product_category ORDER BY product_category ASC", null, 0, 10000);
$departmentcounts=array();
foreach($results['output'] as $cgcount) {
    $departmentcounts[$cgcount['product_category']]=$cgcount['total'];
}
//$oct->showArray($parents);

$userparameters=array(
    "name"=>"create_group_in", 
    "id"=>"createUserNotificationTemplate",
    "class"=>"form-control smaller w-100 hidden",
    "placeholder"=>"Department Owner"
);

$userlist=$oct->buildSelectList($users['results'], $userparameters, "user_id", "real_name", null, array("value"=>"0", "text"=>"No owner"), "group_name");
$selectattributes=array(
    "name"=>"group_in[]", 
    "id"=>"createNewDepartmentUserList",
    "class"=>"form-control smaller w-100",
    "placeholder"=>"Department Owner"
);
$newuserselect=$oct->buildSelectList($users['results'], $selectattributes, "user_id", "real_name", null, array("value"=>"0", "text"=>"No owner"), "group_name");
?>
<script src="js/pages/admin/departments.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Departments</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-2 text-center">
                            <span class="admin-headers">Name</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            <span class="admin-headers">Description</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Owner</span>
                        </div>                   
                        <div class="col-sm text-center">
                            <span class="admin-headers">Position</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Parent</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Visible</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Notifications</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Cases</span>
                        </div>
                    </div>
                </div>
                <div class="overflow-auto p-2 w-100" style="max-height: 600px" >
                <?php
                    foreach($departments['results'] as $dept) {
                        $id=$dept['category_id'];
                        $selectattributes=array(
                            "name"=>"group_in[]", 
                            "id"=>"groupin$id",
                            "class"=>"form-control smaller w-100 updatedepartmentfield",
                            "placeholder"=>"Department Owner",
                            "action"=>"user_id",
                            "departmentid"=>$id,
                        );
                        $userselect=$oct->buildSelectList($users['results'], $selectattributes, "user_id", "real_name", $dept['category_owner'], array("value"=>"0", "text"=>"No owner"), "group_name");

                        $selectattributes=array(
                            "name"=>"parent_id[]",
                            "id"=>"parentcategoryid$id",
                            "class"=>"form-control smaller w-100 updatedepartmentfield",
                            "placeholder"=>"Parent Department",
                            "action"=>"parent_id",
                            "departmentid"=>$id
                        );
                        $parentselect=$oct->buildSelectList($parents['results'], $selectattributes, "category_id", "category_name", $dept['parent_id'], array("value"=>"0", "text"=>"None"), "parent_name");                        
                ?>
                        
                    <div class="row mb-1" id="departmentRow<?php echo $id ?>">
                        <input type="hidden" name="id[]" value="<?php echo $dept['category_id'] ?>" />
                        <?php
                            if($dept['parent_id'] != "0") {
                                $indent="w-90 floatright";
                            } else {
                                $indent="";
                            }
                        ?>
                        <div class="col-sm-2">                        
                            <input action="category_name" departmentid="<?php echo $id ?>" class="form-control smaller updatedepartmentfield <?php echo $indent ?>" placeholder="Department Name" title='Department ID <?php echo $id ?>' id="categoryname<?php echo $id ?>" type="text" name="category_name[]" value="<?php echo $dept['category_name'] ?>" />
                        </div>
                        <div class="col-sm-4">
                            <input action="category_descrip" departmentid="<?php echo $id ?>" class="form-control smaller updatedepartmentfield" placeholder="Department Description" id="categorydescrip<?php echo $id ?>" type="text" name="category_descrip[]" value="<?php echo $dept['category_descrip'] ?>" />
                        </div>
                        <div class="col-sm">
                            <?php echo $userselect ?>
                        </div>
                        <div class="col-sm">
                            <input action="list_position" departmentid="<?php echo $id ?>" class="form-control smaller w-100 updatedepartmentfield" placeholder="Position in list" id="listposition<?php echo $id ?>" type="text" size="2" name="list_position[]" value="<?php echo $dept['list_position'] ?>" />
                        </div>
                        <div class="col-sm">
                            <?php echo $parentselect ?>
                        </div>
                        <div class="col-sm">
                            <input action="show_in_list" departmentid="<?php echo $id ?>" class="form-control smaller updatedepartmentfield" placeholder="Show in list?" id="showinlist<?php echo $id ?>" type="checkbox" name="show_in_list[]" <?php if ($dept['show_in_list']==1) echo "checked" ?> />
                        </div>
                        <div class="col-sm">
                            <span class='btn btn-info btn-sm smaller notification-btn pointer' departmentId='<?php echo $dept['category_id'] ?>'>Notifications</span>
                        </div>
                        <div class="col-sm text-center smaller">
                            <?php 
                            if(isset($departmentcounts[$dept['category_id']])) {
                                echo "<span class=''>".$departmentcounts[$dept['category_id']]."</span>";     
                            } else {
                                if($dept['show_in_list']==1) {
                                    echo "<span class=''>0</span>";
                                } else {
                                    echo "<span class='btn btn-sm btn-warning' title='This Department can be deleted because it is not visible to users and there are no cases assigned against it' onClick='deleteDepartment(\"".$dept['category_id']."\")'>Del</span>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- NOTIFICATIONS SHELL -->
                    <div class="row mb-2 hidden border rounded notificationsshell" id="notifications_<?php echo $dept['category_id'] ?>">

                    </div>
                        <?php
                    }
                ?>
                    
                </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <br />
            <h4 class="header">Add Department</h4>
            <div class="row border rounded centered">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                    <div class="col-sm-2 text-center">
                            <span class="admin-headers">Name</span>
                        </div>
                        <div class="col-sm-4 text-center">
                            <span class="admin-headers">Description</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Owner</span>
                        </div>                   
                        <div class="col-sm text-center">
                            <span class="admin-headers">Position</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Parent</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Visible</span>
                        </div>
                        <div class="col-sm-2">
                            &nbsp;
                        </div>
                        
                    </div>
                </div>
                <div class="form-group p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-2">
                            <input type='text' id='create_category_name' class='form-control smaller' />
                        </div>
                        <div class="col-sm-4">
                            <input type='text' id='create_category_descrip' class='form-control smaller' />
                        </div>
                        <div class="col-sm">
                            <?php echo $newuserselect ?>
                        </div>                   
                        <div class="col-sm">
                            <input type='text' id='create_list_position' class='form-control smaller' size='2' />
                        </div>
                        <div class="col-sm">
                            <select></select>
                        </div>
                        <div class="col-sm">
                            <input type='checkbox' id='create_show_in_list' class='form-control smaller' />
                        </div>
                        <div class="col-sm-2">
                            <span class='btn btn-small btn-main smaller createDepartmentField'>Add</span>
                        </div>                  
                    </div>    
                </div>            
            </div>
        </div>
    </div>    
</div>