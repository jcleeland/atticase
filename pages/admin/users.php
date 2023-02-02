<?php
$users=$oct->userList(array(), null, null, 0, 1000000000);
$usergroups=$oct->userGroupList(array(), null, null, 0, 1000000000);
//Get a list of case types
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";
 
?>
<div class="col-sm-12 mb-1">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <img src="images/save.svg" class="floatright pointer img-fluid rounded ml-2" title="Save changes" id="saveCaseTypesBtn"/>
            <img src="images/undo.svg" class="floatright pointer img-fluid rounded hidden ml-2" width='24px' title="Undo changes" id="undoCaseTypesBtn"/>
            <h4 class="header">Users & Groups</h4>
            <div class="row border rounded centered p-0">
                <div class="p-0 m-0 w-100">
                    <div class="row m-0 p-0 dataheader">
                        <div class="col-sm-2">
                            Name
                        </div>
                        <div class="col-sm-4">
                            Description
                        </div>
                        <div class="col-sm text-center">
                            Admin
                        </div>
                        <div class="col-sm text-center">
                            Open
                        </div>
                        <div class="col-sm text-center">
                            Modify
                        </div>
                        <div class="col-sm text-center">
                            Comment
                        </div>
                        <div class="col-sm text-center">
                            Files
                        </div>
                        <div class="col-sm text-center">
                            Active
                        </div>
                    </div>
                </div>
                <div class="p-0 m-0 w-100" >

<?php
foreach($usergroups['results'] as $usergroup) {
?>
                    <div class="row m-0 ml-1 mr-1 p-0 bg-data rounded smallish" style='margin-bottom: 10px !important'>
                        <input type="hidden" name="groupid[]" value="<?php echo $usergroup['group_id'] ?>" />
                        <div class="col-sm-2 p-1">
                            <a class="btn p-0 pr-2 mb-0" data-toggle="collapse" href="#<?php echo preg_replace('/[^\da-z]/i', '', str_replace(" ", "", $usergroup['group_name'])) ?>" role="button" aria-expanded="false" aria-controls="<?php echo str_replace(" ", "", $usergroup['group_name']) ?>"><img src='./images/chevron-right.svg' height='15px' /></a><?php echo $usergroup['group_name'] ?>
                        </div>
                        <div class="col-sm-4 p-1 small">
                            <?php echo $usergroup['group_desc'] ?>
                        </div>
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-s smaller" title="Allow group members to perform administration functions" placeholder="Administration?" id="isadmin<?php echo $usergroup['group_id'] ?>" type="checkbox" name="is_admin[]" <?php if ($usergroup['is_admin']==1) echo "checked" ?> />
                        </div>                        
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-s smaller" title="Allow group members to open cases" placeholder="Can open cases?" id="canopenjobs<?php echo $usergroup['group_id'] ?>" type="checkbox" name="can_open_jobs[]" <?php if ($usergroup['can_open_jobs']==1) echo "checked" ?> />
                        </div>                        
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-s smaller" title="Allow group members to modify cases" placeholder="Can modify cases?" id="canmodifyjobs<?php echo $usergroup['group_id'] ?>" type="checkbox" name="can_modify_jobs[]" <?php if ($usergroup['can_modify_jobs']==1) echo "checked" ?> />
                        </div>                        
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-s smaller" title="Allow group members to add comments to cases" placeholder="Can add comments?" id="canaddcomments<?php echo $usergroup['group_id'] ?>" type="checkbox" name="can_add_comments[]" <?php if ($usergroup['can_add_comments']==1) echo "checked" ?> />
                        </div>                        
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-s smaller" title="Allow group members to add files to cases" placeholder="Can attach files?" id="canattachfiles<?php echo $usergroup['group_id'] ?>" type="checkbox" name="can_attach_files[]" <?php if ($usergroup['can_attach_files']==1) echo "checked" ?> />
                        </div>                        
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-s smaller" title="Group is set as active" placeholder="Group is active?" id="groupopen<?php echo $usergroup['group_id'] ?>" type="checkbox" name="group_open[]" <?php if ($usergroup['group_open']==1) echo "checked" ?> />
                        </div>                        
                    </div>
                    <div class="overflow-auto container-fluid collapse bg-datacontent" id="<?php echo preg_replace('/[^\da-z]/i', '', str_replace(" ", "", $usergroup['group_name'])) ?>" style="max-height: 300px; margin-bottom: 1em;">
                        <div class="row m-0 ml-2 dataheader smaller">
                            <div class="col-sm-2 ">
                                Name
                            </div>
                            <div class="col-sm-1">
                                Username
                            </div>
                            <div class="col-sm-3">
                                Email
                            </div>
                            <div class="col-sm-1 text-center">
                                Default view
                            </div>
                            <div class="col-sm text-center">
                                Notify self
                            </div>
                            <div class="col-sm-1 text-center">
                                Default case group
                            </div>
                            <div class="col-sm text-center">
                                Notify rate
                            </div>
                            <div class="col-sm text-center">
                                Last notification
                            </div>
                            <div class="col-sm-1 text-center">
                                Active
                            </div>
                        </div>
                    
<?php
    foreach($users['results'] as $user) {
        if($user['group_in']==$usergroup['group_id']) {
?>
                        <div class="row m-0 ml-2 p-0 small">    
                            <input type="hidden" name="userid[]" value="<?php echo $user['user_id'] ?>" />
                            <div class="col-sm-2">
                                <?php echo $user['real_name'] ?>
                            </div>            
                            <div class="col-sm-1">
                                <?php echo $user['user_name'] ?>
                            </div>
                            <div class="col-sm-3">
                                <?php echo $user['email_address'] ?>
                            </div>
                            <div class="col-sm">
                                <select id="defaultview[]" title="Default view when entering OpenCaseTracker">
                                    <option>Hello</option>
                                </select>
                            </div>
                            <div class="col-sm">
                                <input class="form-control-s smaller" title="Notify user when they have made a change" placeholder="Notify self?" id="selfnotify<?php echo $user['user_id'] ?>" type="checkbox" name="self_notify[]" <?php if ($user['self_notify']==1) echo "checked" ?> />
                            </div>
                            <div class="col-sm">
                                <select id="defaultversion[]" title="Default case group">
                                    <option>Standard</option>
                                </select>
                            </div>                                                        
                            <div class="col-sm">
                                <select id="notifyrate[]" title="Rate of summary notifications">
                                    <option value="D">Daily</option>
                                    <option value="W">Weekly</option>
                                    <option value="N">Never</option>
                                </select>
                            </div>
                            <div class="col-sm smaller">
                                <?php echo date("d/m/Y h:ia", $user['last_notice']) ?>
                            </div>
                            <div class="col-sm">
                                <input class="form-control-s smaller" title="Account is active" placeholder="account_enabled" id="accountenabled<?php echo $user['user_id'] ?>" type="checkbox" name="account_enabled[]" <?php if ($user['account_enabled']==1) echo "checked" ?> />
                            </div>                                                        

                        </div>
<?php
        }
    }                        
?>                   
                    </div>                    
<?php    
}
?>                
                
                
                
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    //$oct->showArray($usergroups);
    //$oct->showArray($users);
?>