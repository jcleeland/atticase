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
        //Gather query if exists
    $Qcasetext=isset($_GET['casetext']) ? $_GET['casetext'] : null;
    $QcaseTypeSelect=isset($_GET['caseTypeSelect']) ? $_GET['caseTypeSelect'] : null;
    $QdepartmentSelect=isset($_GET['departmentSelect']) ? $_GET['departmentSelect'] : null;
    $QstatusSelect=isset($_GET['statusSelect']) ? $_GET['statusSelect'] : null;
    $QuserSelect=isset($_GET['userSelect']) ? $_GET['userSelect'] : $user_id;
    $QcaseGroupSelect=isset($_GET['caseGroupSelect']) ? $_GET['caseGroupSelect'] : null;
    $myCases=($QuserSelect==$user_id) ? "checked" : "";
    
    
?>

        <script src="js/pages/dashboard/filters.js"></script>
        <h4 class="header">Search cases</h4>
        <div class="pager rounded-bottom">
            <div class='form-group m-1 form-check'>&nbsp;
                <div class='float-right pr-2'>
                    <input type='checkbox' class='form-check-input small' id='mycasesOnly' <?php echo $myCases ?> />
                    <label class='form-check-label smaller' for='mycasesOnly'>My Cases</label>
                </div>
            </div>

        </div>
        <div class='row'>
            <div class='form-group m-1 p-1 col-xl'>
                <div class='form-group m-1'>
                    <input class='form-control smaller' type='text' class='form-control-sm filterQuery' id='casetext' aria-describedby='casetextHelp' placeholder='Filter by text'>
                </div>
                <div class='form-group m-1'>
                    <?php
                        $casetypes=$oct->caseTypeList();
                        $caseTypeSelect=$oct->buildSelectList($casetypes['results'], array("id"=>"caseTypeSelect", "class"=>"form-control smaller filterQuery"), "tasktype_id", "tasktype_name", $QcaseTypeSelect, "All case types", null);
                        echo $caseTypeSelect;    
                    ?>      
                </div> 
                <div class='form-group m-1'>
                    <?php
                        $departments=$oct->departmentList(array(), "show_in_list=1");
                        $departmentSelect=$oct->buildSelectList($departments['results'], array("id"=>"departmentSelect", "class"=>"form-control smaller filterQuery"), "category_id", "category_name", $QdepartmentSelect, "All departments");
                        echo $departmentSelect;
                    ?>
                </div>                
            </div>
            <div class='form-group m-1 p-1 col-xl'>        
                <div class='form-group m-1'>
                    <select class='form-control smaller filterQuery' id='statusSelect'>
                        <option value='0' <?php if($QstatusSelect==0) echo "selected=selected" ?>>Open cases only</option>   
                        <option value='1' <?php if($QstatusSelect==1) echo "selected=selected" ?>>Closed cases only</option>
                        <option>All cases</option>
                    </select>        
                </div>
                <div class='form-group m-1'>
                    <?php
                        $users=$oct->userList(array(), "account_enabled = 1", "group_name, real_name");
                        $attributes=array("class"=>"form-control smaller filterQuery", "id"=>"userSelect");
                        $userselect=$oct->buildSelectList($users['results'], $attributes, "user_id", "real_name", $QuserSelect, "All users", "group_name");
                        echo $userselect;
                    ?>                
                </div>
                <div class='form-group m-1'>
                    <?php
                        $caseGroups=$oct->caseGroupList(array(), "show_in_list = 1", "list_position, version_name");
                        $attributes=array("class"=>"form-control smaller filterQuery", "id"=>"caseGroupSelect");
                        $casegroupselect=$oct->buildSelectList($caseGroups['results'], $attributes, "version_id", "version_name", $QcaseGroupSelect, "All case groups");
                        echo $casegroupselect;
                    ?>
                </div>                                         

            </div>
        </div>
        <div class='collapse row' id='filterMore'>
            <div class='form-group m-1 p-1 col-xl border rounded'>
                <div class='form-group m-1'>
                    <div class="smaller">
                        <label for='openedAfterDate' class='mb-0'>Opened after </label>
                        <div class='calendar-div p-1 pointer border rounded float-right'>
                            <input id='openedAfterDate' class='datepicker' style='width: 66px; height: 19px;' type='text' value='01/01/2005' />
                        </div>
                        <div style='clear: both'></div>
                    </div>
                </div>
                <div class='form-group m-1'>
                    <div class="smaller">
                        <label for='openedBeforeDate' class='mb-0'>Opened before </label>
                        <div class='calendar-div p-1 pointer border rounded pl-1 pr-1 float-right'>
                            <input id='openedBeforeDate' class='datepicker' style='width: 66px; height: 19px;' type='text' value='<?php echo date("d/m/Y", $todaystart) ?>' />
                        </div>
                        <div style='clear: both'></div>
                    </div>
                </div>
            </div>

            <div class='form-group m-1 p-1 col-xl border rounded'>
                <div class='form-group m-1'>
                    <div class="smaller">
                        <label for='closedAfterDate' class='mb-0'>Closed after </label>
                        <div class='calendar-div p-1 pointer border rounded pl-1 pr-1 float-right'>
                            <input id='closedAfterDate' class='datepicker' style='width: 66px; height: 19px;' type='text' value='01/01/2005' />
                        </div>
                        <div style='clear: both'></div>
                    </div>
                </div>
                <div class='form-group m-1'>
                    <div class="smaller">
                        <label for='closedBeforeDate' class='mb-0'>Closed before</label>&nbsp;
                        <div class='calendar-div p-1 pointer border rounded pl-1 pr-1 float-right'>
                            <input id='closedBeforeDate' class='datepicker' style='width: 66px; height: 19px;' type='text' value='<?php echo date("d/m/Y", $todaystart) ?>' />
                        </div>
                        <div style='clear: both'></div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="row">
            <div class='form-group m-1 p-1 col-xl text-center'>
                <div class='form-group m-1'>        
                    <button type='submit' class='btn btn-main btn-sm w-100 ml-1' id='FilterSearch'>Search</button>
                </div>
            </div>            
            <div class='form-group m-1 p-1 text-nowrap text-right col-xl'>
                <div class='form-group m-1'> 
                    <a class='btn btn-info btn-sm smaller' id='clearFilter' href='#clear' role='button'>
                        Clear
                    </a>
                    <a class='btn btn-secondary btn-sm smaller' data-toggle='collapse' href='#filterMore' role='button' aria-expanded='false' aria-controls='filterMore' id='filterMoreBtn'>
                        More >>
                    </a>
                </div>
            </div>
 
        </div>
        <p>

        <div class="row">

        </div>  
<?php
  
?>
