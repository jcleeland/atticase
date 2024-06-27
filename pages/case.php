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
<script src="js/pages/case.js"></script>

<?php
    //$settings=$oct->getCookies('OpenCaseTrackerSystem');
    //Gather lists & select html
    $casegroups=$oct->caseGroupList();
    $casetypes=$oct->caseTypeList();
    $caseTypeSelect=$oct->buildSelectList($casetypes['results'], array("id"=>"edit_task_type", "class"=>"updateCase w-100"), "tasktype_id", "tasktype_name", null, "Select case", null);
    $departments=$oct->departmentList(array(), $oct->dbprefix."list_category.show_in_list=1");
    $departmentSelect=$oct->buildSelectList($departments['results'], array("id"=>"edit_product_category", "class"=>"updateCase"), "category_id", "category_name", null, "Select department", "parent_name");
    $users=$oct->userList(array(), "account_enabled=1 and group_open=1", null);
    $userSelect=$oct->buildSelectList($users['results'], array("id"=>"edit_assigned_to", "class"=>"updateCase"), "user_id", "real_name", $user_id, "Select user", "group_name");
    $resolutions=$oct->resolutionList(array(), "show_in_list=1");
    $resolutionsSelect=$oct->buildSelectList($resolutions['results'], array("id"=>"close_resolution_reason", "class"=>"closeCase"), "resolution_id", "resolution_name", null, "Select reason");
    
    $customfields=$oct->customFieldList(null, "custom_field_visible=1");
    
?>
<input type='hidden' name='caseid' id='caseid' value='<?php echo $_GET['case'] ?>' />
<input type='hidden' name='userid' id='userid' value='<?php echo $_SESSION['user_id'] ?>' />

<!-- CLOSE CASE FORM -->
<div class="modal fade" id="closeCaseWindow" tabindex="-1" role="dialog" aria-labelledby="closeCaseWindowTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header card-header">
        <h5 class="modal-title" id="closeCaseWindowTitle">Close Case</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      
      
      
      
      <div class="modal-body" id="closeCaseWindowMessage">
        <div class="row">
            <div class="col-xl-4">
                Closure reason:
            </div>
            <div class="col-xl-8">
                <?php echo $resolutionsSelect ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                Closure date: 
            </div>
            <div class="col-xl-8 calendar-div">                
                <input type='text' class='datepicker pointer' id='close_closure_date' style='width: 88px; height: 24px; z-index: 1000' class='closeCaseWindowMessage' value='<?php echo date("d/m/Y") ?>' />
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">                
                Additional comments: 
            </div>
            <div class="col-xl-8">                 
                <textarea class='form-input w-100' id='close_closure_comment'></textarea>
            </div>
        </div>
        <div class="row hidden">
            <div class="col-xl-12">
                Checklist:
                <div class="ml-5">
            <?php
                $checklists=$oct->closureCheckList();
                foreach($checklists as $key=>$val) {
                    ?>
                    <div class="smaller">
                        <input type=checkbox id='close_checklist_<?php echo $key ?>' />
                        <label for='close_checklist_<?php echo $key ?>'><?php echo $val ?></label>
                        
                    </div>
                    <?php
                }
            ?>
                </div>
            </div>
        </div>
      </div>




      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="close_case_btn" data-dismiss="modal">Close case</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
      
      
    </div>
  </div>
</div>


<div class='col-sm-12 mb-1' id='case-cover-sheet'>
    <div class="card">
        <div class="card-header card-heading border rounded" >
            <div class="float-left card-heading-border border rounded pl-1 pr-1 mr-2 case-link" id='caseid_header'></div>
            
            <!-- Case Menu -->
            <div class="dropdown dropleft">
                <button class="btn btn-secondary dropdown-toggle float-right p-0 m-0 pl-1" style='background-color: #6ab446' type="button" id="caseMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img id="case-menu" src="images/ellipsis-vertical.svg" style='background-color: #6ab446; border: none' class="img-thumbnail p-0 m-o" width="20px" title="Case menu" />
                </button>
                <div class="dropdown-menu pl-0" aria-labelledby="caseMenuButton">
                    <a class="dropdown-item pl-1 ml-0" href="#" id="hideCaseDetails"><img id="case-card-toggle-image" src='images/caret-top.svg' class='p-1' width='28px' title="Hide case details" /> Hide case details</a>
                    <a class="dropdown-item pl-1 ml-0" href="#" id="editCaseDetails"><img id="case-edit-image" src="images/edit.svg" class='p-1' width='28px' title='Edit case details' /> Edit case details</a>
                    <a class="dropdown-item pl-1 ml-0" href="#" id="emailCase"><img id="case-email-image" src="images/mail.svg" class="p-1" width="28px" title="Send email" /> Send email</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item pl-1 ml-0" href="#" id="transferCase"><img id="case-transfer-image" src="images/user.svg" class="p-1" width="28px" title="Transfer case " /> Transfer case</a>
                    <a class="dropdown-item pl-1 ml-0" href="#" id="exportCase"><img id="case-export-image" src="images/export.svg" class="p-1" width="28px" title="Export case " />Export case</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item pl-1 ml-0" href="#" id="closeCase"><img id="case-close-image" src="images/end.svg" class='rounded p-1 red-link ml-0' width='28px' title='Close case' /> Close case</a>
                    <a class="dropdown-item pl-1 ml-0 hidden" href="#" id="reopenCase"><img id="case-reopen-image" src="images/end.svg" class="rounded p-1 pale-green-link ml-0" width="28px" title="Reopen case" /> Reopen case</a>
                    <a class="dropdown-item pl-1 ml-0 disabled" href="#" id="deleteCase"><img id="case-delete-image" src="images/trash.svg" class="p-1" width="28px" title="Delete case" /> Delete case</a>
                </div>
            </div>            

            
            
            <div class="float-right mr-2 card-heading-border border rounded pl-1 pr-1 calendar-div pointer" id='date_due_parent'><input type='text' id='date_due' class='datepicker' value='' /></div>
            <div class="float-right card-heading-border border rounded pl-1 pr-1 mr-2 pale-green-link" id="clientname">
                <a class='fa-userlink' href=''></a>
            </div>
            <div class="float-left display-6" id='itemsummary'>
                Loading...
            </div>
            &nbsp;
        </div>
    </div>
    
    
    
    
    
    
    
    <!-- EDIT FORM -->
    <div class="card mb-2 collapse hide" id="case-edit">
        <div class="card-body p-0">
            <div class="card-header border rounded">
                <div class="float-right">
                    <span class='btn btn-light border rounded' title='Access is not restricted' id='is_restricted'><img src='images/unlock.svg' id='is_restricted_image' />
                    <input type='hidden' class='updateCase' id='edit_is_restricted' value='' />
                    </span>
                    <!--<button class='btn btn-danger' title='Access is restricted'><img src='images/lock.svg' /></button>-->
                </div>            
                <div class="row">
                    <!-- Column 1 -->
                    <div class="col-xs-12 col-sm-6">
                        <div class="row mb-1">
                            <div class='subSection-label col-xs-4'>
                                Item Summary
                            </div>
                            <div class='subSection-field col-xs-8 item_summary'>
                                <input type='text' id='edit_item_summary' value='' class='updateCase w-100' />
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="subSection-label col-xs-4">
                                Assigned To
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
                        <div class="row mb-1"><!--Intentionally left blank-->
                            <div class="subSection-label col-xs-4">&nbsp;
                            </div>
                            <div class="subSection-field col-xs-8">&nbsp;
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-4">
                                Line Manager
                            </div>
                            <div class="subSection-field col-xs-8 line_manager">
                                <input type="text" id="edit_line_manager" class='updateCase' />
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-4">
                                Delegate
                            </div>
                            <div class="subSection-field col-xs-8 delegate">
                                <input type="text" id="edit_local_delegate" class='updateCase' />
                            </div>
                        </div>
                        
                        <?php
                            foreach($customfields['results'] as $key=>$field) {
                                if(!($key %2)) {  //Odd numbered entries
                                    ?>
                                    <div class="row align-items-center mb-1">
                                        <div class="subSection-label col-xs-4">
                                            <?php echo $field['custom_field_name'] ?>
                                        </div>
                                        <div class="subSection-field col-xs-8 custom_field_<?php echo $field['custom_field_definition_id'] ?>">
                                        <?php
                                            switch($field['custom_field_type']) {

                                                case "d":
                                                    ?>
                                                    <input type="text" style="min-width: 10em; min-height: 2em; background-color: white; border:1px solid #8f8f9d" id="edit_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='updateCase datepicker' />
                                                    <?php
                                                    break;
                                                case "c":
                                                    ?>
                                                    <input type="checkbox" id="edit_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='updateCase' />
                                                    <?php
                                                    break;
                                                case "l":
                                                    ?>
                                                    <select id="edit_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='updateCase'>
                                                        <?php
                                                            $listitems=$oct->fetchMany("SELECT * FROM ".$oct->dbprefix."custom_field_lists WHERE custom_field_definition_id=? ORDER BY custom_field_order", array($field['custom_field_definition_id']), 0, 10000);
                                                            echo "<option value=''></option>";
                                                            foreach($listitems['output'] as $listitem) {
                                                                echo "<option>".$listitem['custom_field_value']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                    <?php
                                                    break;                                                    
                                                default:
                                                    ?>
                                                    <input type="text" id="edit_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='updateCase w-100' />
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
                                <input type='text' id="edit_member" class='updateCase' />
                            </div>
                        </div>                    
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-4">
                                Case Group
                            </div>
                            <div class="subSection-field col-xs-8 case_group">
                                <select id="edit_product_version" class='updateCase'>
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
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-4">
                                Unit
                            </div>
                            <div class="subSection-field col-xs-8 unit">
                                <?php
                                if($configsettings['general']['unit_list_use']['value'] > 0) {
                                        $unitlist=$oct->unitList();
                                }
                                switch($configsettings['general']['unit_list_use']['value']) {
                                    case "1": //Suggested units
                                        echo "<div class='lookahead-input-wrapper'>";
                                        echo "<input type='text' id='edit_unit' class='updateCase lookahead-input-field w-100' />";
                                        echo "<ul class='lookahead-input-dropdown w-100'></ul>";
                                        echo "</div><ul id='options' style='display: none;'>";
                                        foreach($unitlist['results'] as $unititem) {
                                            echo "<li>".$unititem['unit_descrip']."</li>";
                                        }
                                        echo "</ul>";  
                                        break;
                                    case "2": //Required units
                                        //echo "<pre>"; print_r($unitlist); echo "</pre>";
                                        
                                        echo "<select id='edit_unit' class='updateCase'>";
                                        echo "<option value=''>Select a unit location...</option>";
                                        foreach($unitlist['results'] as $unititem) {
                                            if($unititem['parent_id'] != '0' && $unititem['parent_id'] != "") {
                                                echo "<option style='font-size: 0.9em'><span style='padding-left: 20px;'>&nbsp;&nbsp;&nbsp;&nbsp;".$unititem['unit_descrip']."</span></option>";
                                            } else {
                                                echo "<option>".$unititem['unit_descrip']."</option>";
                                            }
                                        }                                        

                                        echo "</select>";                                        
                                        break;
                                    default: //Free text
                                        ?>
                                            <input type="text" id="edit_unit" class="updateCase w-100" />
                                        <?php
                                        break;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="subsection-label col-xs-4">
                                Line Manager Ph
                            </div>
                            <div class="subSection-field col-xs-8 line_manager_ph">
                                <input type="text" id="edit_line_manager_ph" class='updateCase' />
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="subsection-label col-xs-4">
                                Delegate Ph
                            </div>
                            <div class="subSection-field col-xs-8 local_delegate_ph">
                                <input type="text" id="edit_local_delegate_ph" class='updateCase' />
                            </div>
                        </div>
                        
                        <?php
                            foreach($customfields['results'] as $key=>$field) {
                                if($key %2) {  //Even numbered entries
                                    ?>
                                    <div class="row align-items-center mb-1">
                                        <div class="subSection-label col-xs-4">
                                            <?php echo $field['custom_field_name'] ?>
                                        </div>
                                        <div class="subSection-field col-xs-8 custom_field_<?php echo $field['custom_field_definition_id'] ?>">
<?php
                                            switch($field['custom_field_type']) {

                                                case "d":
                                                    ?>
                                                    <input type="text" style="min-width: 10em; min-height: 2em; background-color: white; border:1px solid #8f8f9d" id="edit_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='datepicker updateCase' />
                                                    <?php
                                                    break;
                                                case "c":
                                                    ?>
                                                    <input type="checkbox" id="edit_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='updateCase' />
                                                    <?php
                                                    break;
                                                case "l":
                                                    ?>
                                                    <select id="edit_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='updateCase'>
                                                        <?php
                                                            $listitems=$oct->fetchMany("SELECT * FROM ".$oct->dbprefix."custom_field_lists WHERE custom_field_definition_id=? ORDER BY custom_field_order", array($field['custom_field_definition_id']), 0, 10000);
                                                            echo "<option value=''></option>";
                                                            foreach($listitems['output'] as $listitem) {
                                                                echo "<option>".$listitem['custom_field_value']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                    <?php
                                                    break;
                                                default:
                                                    ?>
                                                    <input type="text" id="edit_custom_field_<?php echo $field['custom_field_definition_id'] ?>" class='updateCase w-100' />
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
                            <div class="subSection-label col-xs-12">
                                Case Outline
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="subSection-field col-xs-12 w-100 pr-2">
                                <div class="form-group w-100">
                                    <textarea class="w-100 updateCase" rows="8" id="edit_detailed_desc"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column 2 -->
                    <div class="col-xs-12 col-sm-6">
                        <div class="row mb-1">
                            <div class="subSection-label col-xs-12">
                                Resolution Sought
                            </div>
                        </div>
                        <div class="row mb-1">                        
                            <div class="subSection-field col-xs-12 w-100 pr-2">
                                <div class="form-group w-100">
                                    <textarea class="w-100 updateCase" rows="8" id="edit_resolution_sought"></textarea>
                                </div>
                            </div>
                        </div>                    
                    </div>
                </div>                
                
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                            <div class='float-right xs-4 ml-2'>
                                <button id='cancel-case-edits' class='float-right form-control pale-red-link'>Cancel</button>
                            </div>
                            <div class='float-right xs-4 ml-2'>
                                <button id='save-close-case-edits' class='float-right form-control pale-green-link'>Save and close</button>
                            </div>
                            <div class='float-right xs-4 ml-2'>
                                <button id='save-case-edits' class='form-control pale-green-link'>Save</button>
                            </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div> 
    
    
    
    
    
    
   
    <!-- VIEW CASE -->
    <div class="card mb-2 collapse show" id="case-card">
        <div class="card-body p-0" onDblClick="toggleCaseEdit()">
            <div class="card-header border rounded">
                <div class="float-right">
                    <div class='btn btn-light border rounded mb-1' title='Access is not restricted' id='isrestricted_cover'><img src='images/unlock.svg' id='isrestricted_image' /></div>
                    <div class='btn btn-light border rounded mb-1 hidden' title='This case is a master case' id='isparent_cover'><img src='images/linked-parent.svg' id='isparent_image' width='32px' height='32px' /></div>
                    <div class='btn btn-light border rounded mb-1 hidden' title='This case is a dependent' id='isdependent_cover'><img src='images/linked-child.svg' id='isdependent_image' width='32px' height='32px' /></div>
                    <div class='btn btn-light border rounded mb-1 hidden' title='This case is a companion' id='iscompanion_cover'><img src='images/linked-companion.svg' id='iscompanion_image' width='32px' height='32px' /></div>
                    <!--<button class='btn btn-danger' title='Access is restricted'><img src='images/lock.svg' /></button>-->
                </div>            
                <div class="row">
                    <!-- COLUMN 1 -->
                    <div class="col-xs-12 col-sm-6">
                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Assigned To
                            </div>
                            <div class="subSection-field cols-xs-8 assigned_to" id='assignedto_cover'>
                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Case Type
                            </div>
                            <div class="subSection-field col-xs-8 case_type" id="casetype_cover">
                                
                            </div>
                        </div>

                        <div class="row"><!-- intentionally left blank -->
                            <div class="subSection-label col-xs-4">
                                &nbsp;
                            </div>
                            <div class="subSection-field col-xs-8 case_type" style="padding-bottom: 30px" >
                                &nbsp;
                            </div>
                        </div>

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Line Manager
                            </div>
                            <div class="subSection-field col-xs-8 line_manager" id="linemanager_cover">
                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Local Delegate
                            </div>
                            <div class="subSection-field col-xs-8 local_delegate" id="delegate_cover">
                                
                            </div>
                        </div>

                        <?php
                            foreach($customfields['results'] as $key=>$field) {
                                if(!($key %2)) { //Odd numbered entries
                                    ?>
                                        <div class="row align-items-center">
                                            <div class="subSection-label col-xs-4">
                                                <?php echo $field['custom_field_name'] ?>
                                            </div>
                                    <?php
                                    switch($field['custom_field_type']) {
                                        case "d":
                                            ?>
                                            <div class="subSection-field col-xs8 custom_field_<?php echo $field['custom_field_definition_id'] ?>" id="custom_field_<?php echo $field['custom_field_definition_id'] ?>_cover">
                                            </div>                                            
                                            <?php
                                            break;
                                        case "c":
                                            ?>
                                            <div class="subSection-field col-xs8 custom_field_<?php echo $field['custom_field_definition_id'] ?>">
                                                <input type="checkbox" disabled="disabled" id="custom_field_<?php echo $field['custom_field_definition_id']?>_cover" />
                                            </div>
                                            <?php
                                            break;
                                        default:
                                            ?>
                                            <div class="subSection-field col-xs8 custom_field_<?php echo $field['custom_field_definition_id'] ?>" id="custom_field_<?php echo $field['custom_field_definition_id'] ?>_cover">
                                            </div>                                            
                                            <?php
                                            break;
                                    }
                                    ?>

                                        </div>
                                    <?php
                                }
                            }
                        ?>
                        
                    </div>
                    
                    
                    <!-- COLUMN 2 -->
                    <div class="col-xs-12 col-sm-6">

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Case Group
                            </div>
                            <div class="subSection-field col-xs-8 case_group" id="casegroup_cover">
                                
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Department
                            </div>
                            <div class="subSection-field col-xs-8 department" id="department_cover">
                                
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Unit
                            </div>
                            <div class="subSection-field col-xs-8 unit" style="line-height: 1.2em; max-height: 1.2em; padding-bottom: 30px; overflow-x: auto; white-space: nowrap" id="unit_cover">
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="subSection-label col-xs-4">
                            &nbsp;    
                            </div>
                            <div class="subSection-field col-xs-8" id="">
                                
                            </div>
                        </div>                        
                        <?php
                            foreach($customfields['results'] as $key=>$field) {
                                if($key %2) { //Even numbered entries
                                    ?>
                                        <div class="row align-items-center">
                                            <div class="subSection-label col-xs-4">
                                                <?php echo $field['custom_field_name'] ?>
                                            </div>
                                    <?php
                                    switch($field['custom_field_type']) {
                                        case "d":
                                            ?>
                                            <div class="subSection-field col-xs8 custom_field_<?php echo $field['custom_field_definition_id'] ?>" id="custom_field_<?php echo $field['custom_field_definition_id'] ?>_cover">
                                            </div>                                            
                                            <?php
                                            break;
                                        case "c":
                                            ?>
                                            <div class="subSection-field col-xs8 custom_field_<?php echo $field['custom_field_definition_id'] ?>">
                                                <input type="checkbox" disabled="disabled" id="custom_field_<?php echo $field['custom_field_definition_id']?>_cover" />
                                            </div>
                                            <?php
                                            break;
                                        default:
                                            ?>
                                            <div class="subSection-field col-xs8 custom_field_<?php echo $field['custom_field_definition_id'] ?>" id="custom_field_<?php echo $field['custom_field_definition_id'] ?>_cover">
                                            </div>                                            
                                            <?php
                                            break;
                                    }
                                    ?>
                                        </div>
                                    <?php
                                }
                            }
                        ?>
                        
                        
                    </div>
                </div>
            </div>
            <div class="card-body row">
                <div class="col-lg">
                    <div class="subSection-label col-xs-2">
                        Case Outline
                    </div>
                    <div class="subSection-field col-xs-10 detailed_desc mb-1 pl-2 overflow-auto small" id="detaileddesc_cover" style="min-height: 100px; max-height: 200px">
                        
                    </div>
                </div>    

                <div class="col-lg">
                    <div class="subSection-label col-xs-2">
                        Resolution Sought
                    </div>
                    <div class="subSection-field col-xs-10 resolution_sought mb-1 pl-2 overflow-auto small" id="resolution_cover" style="min-height: 100px; max-height: 200px">
                        
                    </div>
                </div>                  
            </div>
    
            <div class="stamp-container">    
                <div class="stamp stamp-red-double" style="display: none" id="closedStamp">
                    <span class="stamp-title">Closed</span>
                    <span class="stamp-details" id="closedStampDetails"></span>
                </div>     
            </div>        


            <div class="card-footer text-footnote row m-0">
                <div class="col-lg">
                    Case created on <span id="dateopened_cover"></span> by <span id="openedby_cover"></span>
                </div>
                <div class="col-lg hidden" id="case_closed_details">
                    <b>Closed <span id='case_closed_date'></span> by <span id='case_closed_name'></span></b><br />
                    <span id='case_closed_reason' class='title'></span><br />
                    <span id='case_closed_comments'></span>
                </div>
            </div>
        </div>
    </div>
</div>






<?php
    include("pages/casetabs.php");
?>