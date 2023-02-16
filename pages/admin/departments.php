<?php

$users=$oct->userList(array(), null, null, 0, 1000000000);

//Get a list of departments
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";
$departments=$oct->departmentList(array(), null, null, 0, 10000000);
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($departments); echo "</pre>";
?>
<script src="js/pages/admin/departments.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <img src="images/save.svg" class="floatright pointer img-fluid rounded ml-2" title="Save changes" id="saveDepartmentsBtn"/>
            <img src="images/undo.svg" class="floatright pointer img-fluid rounded hidden ml-2" width='24px' title="Undo changes" id="undoDepartmentsBtn"/>
            <h4 class="header">Departments</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-3">
                            Name
                        </div>
                        <div class="col-sm-4">
                            Description
                        </div>
                        <div class="col-sm">
                            Owner
                        </div>                   
                        <div class="col-sm">
                            Position
                        </div>
                        <div class="col-sm">
                            Show
                        </div>
                        <div class="col-sm-2">
                            Notifications
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2" style="height: 700px" >
                <?php
                    foreach($departments['results'] as $dept) {
                        $id=$dept['category_id'];
                        $selectattributes=array(
                            "name"=>"group_in[]", 
                            "id"=>"groupin$id",
                            "class"=>"form-control smaller w-75",
                            "placeholder"=>"Department Owner"
                        );
                        $userselect=$oct->buildSelectList($users['results'], $selectattributes, "user_id", "real_name", $dept['category_owner'], "Select owner", "group_name");

                        ?>
                        
                    <div class="row mb-1">
                        <input type="hidden" name="id[]" value="<?php echo $dept['category_id'] ?>" />
                        <div class="col-sm-3">
                            <input class="form-control smaller" placeholder="Department Name" title='Department ID <?php echo $id ?>' id="categoryname<?php echo $id ?>" type="text" name="category_name[]" value="<?php echo $dept['category_name'] ?>" />
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control smaller" placeholder="Department Description" id="categorydescrip<?php $id ?>" type="text" name="category_descrip[]" value="<?php echo $dept['category_descrip'] ?>" />
                        </div>
                        <div class="col-sm">
                            <?php echo $userselect ?>
                        </div>
                        <div class="col-sm">
                            <input class="form-control smaller w-100" placeholder="Position in list" id="listposition<?php echo $id ?>" type="text" size="2" name="list_position[]" value="<?php echo $dept['list_position'] ?>" />
                        </div>
                        <div class="col-sm">
                            <input class="form-control smaller" placeholder="Show in list?" id="showinlist<?php echo $id ?>" type="checkbox" name="show_in_list[]" <?php if ($dept['show_in_list']==1) echo "checked" ?> />
                        </div>
                        <div class="col-sm-2">
                            <span class='btn btn-info btn-sm smaller notification-btn pointer' departmentId='<?php echo $dept['category_id'] ?>'>Notifications</span>
                        </div>
                    </div>
                    <!-- NOTIFICATIONS SHELL -->
                    <div class="row mb-2 hidden border rounded notificationsshell" id="notifications_<?php echo $dept['category_id'] ?>">

                    </div>
                        <?php
                    }
                ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>