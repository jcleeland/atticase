<script src="js/pages/admin/users.js"></script>
<?php
$users=$oct->userList(array(), null, null, 0, 1000000000);
$usergroups=$oct->userGroupList(array(), null, null, 0, 1000000000);
$issuetypes=$oct->getIssueTypes();
$groupcounts=array();
//$oct->showArray($usergroups);
foreach($usergroups['results'] as $group) {
    $groupcounts[$group['group_id']]=0;    
}
foreach($users['results'] as $user) {
    $groupcounts[$user['group_in']]=$groupcounts[$user['group_in']]+1;
}
$usergroupselectoptions=array();
foreach($usergroups['results'] as $ug) {
    $usergroupselectoptions[$ug['group_id']]=$ug['group_name'];
}
$issuetypes=$oct->getIssueTypes();

//Get a list of case types
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";
 
?>
<div class="col-sm-12 mb-1">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <!--<img src="images/save.svg" class="floatright pointer img-fluid rounded ml-2" title="Save changes" id="saveCaseTypesBtn"/>
            <img src="images/undo.svg" class="floatright pointer img-fluid rounded hidden ml-2" width='24px' title="Undo changes" id="undoCaseTypesBtn"/>-->
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
                        <div class="col-sm text-center smaller" title="Has administration rights">
                            Admin
                        </div>
                        <div class="col-sm text-center smaller" title="Can open new cases">
                            Open
                        </div>
                        <div class="col-sm text-center smaller" title="Can modify cases">
                            Modify
                        </div>
                        <div class="col-sm text-center smaller" title="Can add comments to cases">
                            Comment
                        </div>
                        <div class="col-sm text-center smaller" title="Can attach files to cases">
                            Files
                        </div>
                        <div class="col-sm text-center smaller" title="Restrict access to case groups">
                            Restrict
                        </div>
                        <div class="col-sm text-center smaller" title="Group is activate and enabled">
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
                            <a class="btn p-0 pr-2 mb-0" data-toggle="collapse" href="#<?php echo preg_replace('/[^\da-z]/i', '', str_replace(" ", "", $usergroup['group_name'])) ?>" role="button" aria-expanded="false" aria-controls="<?php echo str_replace(" ", "", $usergroup['group_name']) ?>"><img src='./images/chevron-right.svg' height='15px' title='Group ID <?php echo $usergroup['group_id'] ?>' /><?php echo $usergroup['group_name'] ?></a>
                        </div>
                        <div class="col-sm-4 p-1 small">
                            <?php echo $usergroup['group_desc'] ?>
                        </div>
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-sm smaller changeGroup" action="is_admin" groupid="<?php echo $usergroup['group_id'] ?>" title="Allow group members to perform administration functions" placeholder="Administration?" type="checkbox" name="is_admin[]" <?php if ($usergroup['is_admin']==1) echo "checked" ?> />
                        </div>                        
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-sm smaller changeGroup" action="can_open_jobs" groupid="<?php echo $usergroup['group_id'] ?>" title="Allow group members to open cases" placeholder="Can open cases?" type="checkbox" name="can_open_jobs[]" <?php if ($usergroup['can_open_jobs']==1) echo "checked" ?> />
                        </div>                        
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-sm smaller changeGroup" action="can_modify_jobs" groupid="<?php echo $usergroup['group_id'] ?>" title="Allow group members to modify cases" placeholder="Can modify cases?" type="checkbox" name="can_modify_jobs[]" <?php if ($usergroup['can_modify_jobs']==1) echo "checked" ?> />
                        </div>                        
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-sm smaller changeGroup" action="can_add_comments" groupid="<?php echo $usergroup['group_id'] ?>" title="Allow group members to add comments to cases" placeholder="Can add comments?" type="checkbox" name="can_add_comments[]" <?php if ($usergroup['can_add_comments']==1) echo "checked" ?> />
                        </div>                        
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-sm smaller changeGroup" action="can_attach_files" groupid="<?php echo $usergroup['group_id'] ?>" title="Allow group members to add files to cases" placeholder="Can attach files?" type="checkbox" name="can_attach_files[]" <?php if ($usergroup['can_attach_files']==1) echo "checked" ?> />
                        </div>                       
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-sm smaller changeGroup showrestrictversions" action="restrict_versions" groupid="<?php echo $usergroup['group_id'] ?>" title="Restrict which case group members can see" placeholder="Restrict case groups?" type="checkbox" name="restrict_versions[]" <?php if ($usergroup['restrict_versions']==1) echo "checked" ?> />
                        </div>                       
                        <div class="col-sm p-1 text-center">
                            <input class="form-control-sm smaller changeGroup" action="group_open" title="Group is set as active" placeholder="Group is active?" groupid="<?php echo $usergroup['group_id'] ?>" type="checkbox" name="group_open[]" <?php if ($usergroup['group_open']==1) echo "checked" ?> />
                        </div>                        
                    </div>
                    
                    
                    <div class="overflow-auto container-fluid collapse bg-datacontent" id="<?php echo preg_replace('/[^\da-z]/i', '', str_replace(" ", "", $usergroup['group_name'])) ?>" style="max-height: 300px; margin-bottom: 1em;">
                    <?php 
                        $showrestrict=($usergroup['restrict_versions']==1) ? "" : "hidden"; 
                        $restrictedversions=$oct->restrictVersionList($usergroup['group_id']);
                        //$oct->showArray($restrictedversions);
                    ?> 
                        <div class="row m-0 p-0 bg-data rounded smallish">
                            <div class="col-sm-2">
                                <span id='restrict_version_title_<?php echo $usergroup['group_id'] ?>' class='text-right <?php echo $showrestrict ?>'>Allowed Case Groups:</span>
                            </div>
                            <div class='col-sm-8'>
                                <span id='restrict_version_options_<?php echo $usergroup['group_id'] ?>' class='<?php echo $showrestrict ?>'>
                                <?php
                                    foreach($issuetypes['results'] as $casegroup) {
                                        ?>
                                        <div class="form-control-sm form-check form-check-inline mr-1 float-left">
                                            <input class="form-control-sm form-check-input smaller changerestrictversion mr-0" action="restrict_version" groupid="<?php echo $usergroup['group_id'] ?>" versionid='<?php echo $casegroup['version_id'] ?>' id='restrictversion_<?php echo $usergroup['group_id'] ?>_<?php echo $casegroup['version_id'] ?>' type="checkbox" name="restrict_version[]" <?php
                                                if(in_array($casegroup['version_id'], $restrictedversions)) {
                                                    echo "checked";
                                                }
                                            ?> />
                                            <label for="restrictversion_<?php echo $usergroup['group_id'] ?>_<?php echo $casegroup['version_id'] ?>" class="form-control-sm form-check-label ml-0">
                                                <?php echo $casegroup['version_name'] ?>
                                            </label>
                                        </div>
                                        <?php
                                    }
                                ?>
                                </span>
                            </div>
                            <div class='col-sm-2'>
                                <?php if($groupcounts[$usergroup['group_id']] == 0) { ?>
                                <div class="float-right" id="group<?php  ?>delete"><button class="form-control-sm p-0 btn-sm btn-warning deleteGroup small" groupid="<?php echo $usergroup['group_id'] ?>">Delete Group</button></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row m-0 ml-2 dataheader smaller">
                            <div class="col-sm-2 ">
                                Name
                            </div>
                            <div class="col-sm-1">
                                Username
                            </div>
                            <div class="col-sm-2">
                                Email
                            </div>
                            <div class="col-sm-1">
                                Group
                            </div>
                            <div class="col-sm-1 text-center">
                                Default case view
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
                            <div class="col-sm-2">
                                <input class="form-control-sm changeUser" userid="<?php echo $user['user_id'] ?>" action="real_name" id="real_name" type="text" value="<?php echo $user['real_name'] ?>" />
                            </div>            
                            <div class="col-sm-1" title="This field cannot be modified">
                                <span title="User ID <?php echo $user['user_id'] ?>"><?php echo $user['user_name'] ?></span>
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control-sm changeUser" userid="<?php echo $user['user_id'] ?>" action="email_address" id='email_address' type='text' value='<?php echo $user['email_address'] ?>' />
                            </div>
                            <div class="col-sm-1">
                                <select class="form-control-sm w-100 changeUser" userid="<?php echo $user['user_id'] ?>" action="group_in" id='group_in'>
                                <?php
                                    foreach($usergroupselectoptions as $key=>$val) {
                                        echo "<option value='$key'";
                                        if($user['group_in']==$key) echo " selected";
                                        echo ">$val</option>";
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-sm">
                                <select class="form-control-sm w-100 changeUser" userid="<?php echo $user['user_id'] ?>" action="default_task_view" id="default_task_view" title="Default view when entering OpenCaseTracker">
                                    <option value='' <?php if($user['default_task_view'] == "") echo "selected" ?>>All Cases</option>
                                    <option value='assigned' <?php if($user['default_task_view'] == "assigned") echo "selected" ?>>My Cases</option>
                                </select>
                            </div>
                            <div class="col-sm">
                                <input class="form-control-sm smaller text-center changeUser" userid="<?php echo $user['user_id'] ?>" action="self_notify" title="Notify user when they have made a change" placeholder="Notify self?" id="selfnotify<?php echo $user['user_id'] ?>" type="checkbox" name="self_notify[]" <?php if ($user['self_notify']==1) echo "checked" ?> />
                            </div>
                            <div class="col-sm">
                                <select class="form-control-sm w-100 changeUser" userid="<?php echo $user['user_id'] ?>" action="default_version" id="defaultversion[]" title="Default case group">
                                <?php
                                    foreach($issuetypes['results'] as $issuetype) {
                                        echo "<option value='".$issuetype['version_id']."'";
                                        if($user['default_version']==$issuetype['version_id']) {
                                            echo " selected";
                                        }
                                        echo ">".$issuetype['version_name']."</option>";
                                    }
                                ?>
                                </select>
                            </div>                                                        
                            <div class="col-sm">
                                <select class="form-control-sm w-100 changeUser" userid="<?php echo $user['user_id'] ?>" action="notify_rate" id="notifyrate[]" title="Rate of summary notifications">
                                    <option value="D" <?php if($user['notify_rate'] == "D") echo "selected" ?>>Daily</option>
                                    <option value="W" <?php if($user['notify_rate'] == "W") echo "selected" ?>>Weekly</option>
                                    <option value="N" <?php if($user['notify_rate'] == "N") echo "selected" ?>>Never</option>
                                </select>
                            </div>
                            <div class="col-sm smaller text-center">
                                <?php echo date("d/m/Y h:ia", $user['last_notice']) ?>
                            </div>
                            <div class="col-sm smaller text-center">
                                <input class="form-control-sm smaller changeUser" userid="<?php echo $user['user_id'] ?>" action="account_enabled" title="Account is active" placeholder="account_enabled" id="accountenabled<?php echo $user['user_id'] ?>" type="checkbox" name="account_enabled[]" <?php if ($user['account_enabled']==1) echo "checked" ?> />
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