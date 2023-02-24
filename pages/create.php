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
?>
<script src="js/pages/create.js"></script>

<?php

    $createType=isset($_GET['type']) ? $_GET['type'] : "case";
    if($createType=="case") {
        $caseGroupConditions="is_enquiry=0 AND show_in_list=1";
        $detailed_descTitle="Case Outline";
        $resolution_soughtTitle="Resolution Sought";
        $assigned_toTitle="Assigned to";
        $user_select=null;
        $reviewDate=date("d/m/Y", strtotime("7 days"));
        $isclosed=0;
    } else {
        $caseGroupConditions="is_enquiry=1 AND show_in_list=1";
        $detailed_descTitle="Enquiry Outline";
        $resolution_soughtTitle="Advice Provided";
        $assigned_toTitle="Handled by";
        $user_select=$user_id;
        $reviewDate=date("d/m/Y", strtotime("today"));
        $isclosed=1;
    }
    //Gather lists & select html
    $casegroups=$oct->caseGroupList(array(), $caseGroupConditions);
    //$oct->showArray($casegroups);
    $caseGroupSelect=$oct->buildSelectList($casegroups['results'], array("id"=>"create_task_type", "class"=>"createCase"), "version_id", "version_name", null, "Select group", null);
    $casetypes=$oct->caseTypeList();
    $caseTypeSelect=$oct->buildSelectList($casetypes['results'], array("id"=>"create_task_type", "class"=>"createCase"), "tasktype_id", "tasktype_name", null, "Select case", null);
    $departments=$oct->departmentList(array(), "show_in_list=1");
    $departmentSelect=$oct->buildSelectList($departments['results'], array("id"=>"create_product_category", "class"=>"createCase"), "category_id", "category_name", null, "Select department");
    $users=$oct->userList(array(), "account_enabled=1 AND group_in NOT IN ('9')", null);
    $userSelect=$oct->buildSelectList($users['results'], array("id"=>"create_assigned_to", "class"=>"createCase"), "user_id", "real_name", $user_select, "Select user", "group_name");
    
    $customfields=$oct->customFieldList(null, "custom_field_visible=1");
    
    
    
    
    
    //echo "<pre>"; print_r($customfields['results']); echo "</pre>";
?>
<input type='hidden' name='userid' id='userid' value='<?php echo $user_id ?>' />
<input type='hidden' name='create_is_closed' id='create_is_closed' class='createCase' value='<?php echo $isclosed ?>' />
<input type='hidden' name='create_opened_by' id='create_opened_by' class='createCase' value='<?php echo $user_id ?>' />
<input type='hidden' name='create_date_opened' id='create_date_opened' class='createCase' value='<?php echo time() ?>' />




<div class='col-sm-12 mb-1'>
    <div class="card">
        <div class="card-header card-heading border rounded" >
            <div class="float-right card-heading-border border rounded pr-1 pl-1 mr-2 calendar-div pointer date-future" id='dateDueField'>
                <label for="create_date_due" class="mb-0">Date due:</label>
                <input type='text' id='create_date_due' class='createCase subSection-field datepicker form-input white' style='width: 66px; height: 19px' value='<?php echo $reviewDate ?>' />
            </div>
            <div class="float-left card-heading-border border rounded pl-1 pr-1 mr-2 case-link" id='caseid_header'>#</div>

            <div class="float-left display-6" id='itemsummary'>
                Create <?php echo $createType ?>
            </div>
            &nbsp;
        </div>
    </div>
    
    
    
    
    
    
    
    <!-- EDIT FORM -->
    <div class="card mb-2" id="case-edit">
        <div class="card-body p-0">
            <div class="card-header border rounded">
                <div class="row">
                    <!-- Column 1 -->
                    <div class="col-xs-12 col-sm-6">
                        <div class="row mb-1">
                            <div class='subSection-label col-xs-4'>
                                Item Summary
                            </div>
                            <div class='subSection-field col-xs-8 item_summary'>
                                <input type='text' id='create_item_summary' value='' class='createCase w-100' />
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="subSection-label col-xs-4">
                                <?php echo $assigned_toTitle ?>
                            </div>
                            <div class="subSection-field col-xs-8 assigned_to">
                                <?php echo $userSelect ?>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-4">
                                Case Type
                            </div>
                            <div class="subSection-field col-xs-8 task_type">
                                <?php echo $caseTypeSelect ?>
                            </div>
                        </div>
                        <?php if($createType=="case") { ?>
                            <div class="row mb-1">
                                <div class="subSection-label col-xs-4">
                                    Line Manager
                                </div>
                                <div class="subSection-field col-xs-8 line_manager">
                                    <input type="text" id="create_line_manager" class='createCase w-75' />
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="subSection-label col-xs-4">
                                    Delegate
                                </div>
                                <div class="subSection-field col-xs-8 delegate">
                                    <input type="text" id="create_local_delegate" class='createCase w-75' />
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php
                            foreach($customfields['results'] as $key=>$field) {
                                if(!$key %2) {  //Odd numbered entries
                                    ?>
                                    <div class="row mb-1">
                                        <div class="subSection-label col-xs-4">
                                            <?php echo $field['custom_field_name'] ?>
                                        </div>
                                        <div class="subSection-field col-xs-8 custom_field_<?php echo $field['custom_field_definition_id'] ?>">
                                        <?php
                                            switch($field['custom_field_type']) {

                                                case "d":
                                                    ?>
                                                    <input type="text" size="10" id="create_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='createCase w-100' />
                                                    <?php
                                                    break;
                                                case "c":
                                                    ?>
                                                    <input type="checkbox" id="create_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='createCase' />
                                                    <?php
                                                    break;
                                                default:
                                                    ?>
                                                    <input type="text" id="create_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='createCase w-100' />
                                                    <?php
                                                    break;                                                
                                            }
                                        ?>
                                            
                                            
                                        </div>
                                    </div>    
                                    <?php
                                }
                            }
                        ?>
                    </div>
                    
                    
                    
                    
                    
                    
                    <!-- Column 2 -->
                    <div class="col-xs-12 col-sm-6">
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-4">
                                Client
                            </div>
                            <div class="subSection-field col-xs-8 member">
                                <input type='text' id="create_member" class='createCase' />
                            </div>
                        </div>                    
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-4">
                                Case Group
                            </div>
                            <div class="subSection-field col-xs-8 case_group">
                                <select id="create_product_version" class='createCase'>
                                    <?php
                                        foreach($casegroups['results'] as $casegroup) {
                                            echo "\t\t\t\t\t\t\t<option value='".$casegroup['version_id']."'>".$casegroup['version_name']."</option>";
                                        }
                                    ?>
                                
                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-4">
                                Department
                            </div>
                            <div class="subSection-field col-xs-8 product_category">
                                <?php echo $departmentSelect ?>
                            </div>
                        </div>
                        <?php if($createType=="case") { ?>
                            <div class="row mb-1">
                                <div class="subsection-label col-xs-4">
                                    Line Manager Ph
                                </div>
                                <div class="subSection-field col-xs-8 line_manager_ph">
                                    <input type="text" id="create_line_manager_ph" class='createCase' />
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="subsection-label col-xs-4">
                                    Delegate Ph
                                </div>
                                <div class="subSection-field col-xs-8 local_delegate_ph">
                                    <input type="text" id="create_local_delegate_ph" class='createCase' />
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php
                            foreach($customfields['results'] as $key=>$field) {
                                if($key %2) {  //Even numbered entries
                                    ?>
                                    <div class="row mb-1">
                                        <div class="subSection-label col-xs-4">
                                            <?php echo $field['custom_field_name'] ?>
                                        </div>
                                        <div class="subSection-field col-xs-8 custom_field_<?php echo $field['custom_field_definition_id'] ?>">
<?php
                                            switch($field['custom_field_type']) {

                                                case "d":
                                                    ?>
                                                    <input type="text" size="10" id="create_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='createCase' />
                                                    <?php
                                                    break;
                                                case "c":
                                                    ?>
                                                    <input type="checkbox" id="create_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='createCase' />
                                                    <?php
                                                    break;
                                                default:
                                                    ?>
                                                    <input type="text" id="create_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='createCase' />
                                                    <?php
                                                    break;                                                
                                            }
                                        ?>                                        
                                        </div>
                                    </div>    
                                    <?php
                                }
                            }
                        ?>                        
                    </div>
                </div>
                
                <div class="row">
                    <!-- Column 1 -->
                    <div class="col-xs-12 col-sm-6">
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-12" id='detailed_description_title'>
                                <?php echo $detailed_descTitle ?>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="subSection-field col-xs-12 w-100 pr-2">
                                <div class="form-group w-100">
                                    <textarea class="w-100 createCase" rows="8" id="create_detailed_desc"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column 2 -->
                    <div class="col-xs-12 col-sm-6">
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-12" id='resolution_sought_title'>
                                <?php echo $resolution_soughtTitle ?>
                            </div>
                        </div>
                        <div class="row mb-1">                        
                            <div class="subSection-field col-xs-12 w-100 pr-2">
                                <div class="form-group w-100">
                                    <textarea class="w-100 createCase" rows="8" id="create_resolution_sought"></textarea>
                                </div>
                            </div>
                        </div>                    
                    </div>
                </div>                
                
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                            <div class='float-right xs-4 ml-2'>
                                <button id='cancel-case-create' class='float-right form-control pale-red-link'>Cancel</button>
                            </div>
                            <div class='float-right xs-4 ml-2'>
                                <button id='save-case-create' class='form-control pale-green-link'>Create</button>
                            </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div> 
</div>
