        <script src="js/pages/dashboard/filters.js"></script>
        <h4 class="header">Search cases</h4>
        <div class="pager rounded-bottom">
            <div class='form-group m-1 form-check'>&nbsp;
                <div class='float-right pr-2'>
                    <input type='checkbox' class='form-check-input small' id='mycasesOnly' checked=checked />
                    <label class='form-check-label smaller' for='mycasesOnly'>My Cases</label>
                </div>
            </div>

        </div>
        <div class='row'>
            <div class='form-group m-1 p-1 col-xl'>
                <div class='form-group m-1'>
                    <input class='form-control smaller' type='text' class='form-control-sm' id='casetext' aria-describedby='casetextHelp' placeholder='Filter by text'>
                </div>
                <div class='form-group m-1'>
                    <?php
                        $casetypes=$oct->caseTypeList();
                        $caseTypeSelect=$oct->buildSelectList($casetypes['results'], array("id"=>"caseTypeSelect", "class"=>"form-control smaller"), "tasktype_id", "tasktype_name", null, "All case types", null);
                        echo $caseTypeSelect;    
                    ?>      
                </div> 
                <div class='form-group m-1'>
                    <?php
                        $departments=$oct->departmentList(array(), "show_in_list=1");
                        $departmentSelect=$oct->buildSelectList($departments['results'], array("id"=>"departmentSelect", "class"=>"form-control smaller"), "category_id", "category_name", null, "All departments");
                        echo $departmentSelect;
                    ?>
                </div>                
            </div>
            <div class='form-group m-1 p-1 col-xl'>        
                <div class='form-group m-1'>
                    <select class='form-control smaller' id='statusSelect'>
                        <option value='0'>Open cases only</option>   
                        <option value='1'>Closed cases only</option>
                        <option>All cases</option>
                    </select>        
                </div>
                <div class='form-group m-1'>
                    <?php
                        $users=$oct->userList(array(), "account_enabled = 1", "group_name, real_name");
                        $attributes=array("class"=>"form-control smaller", "id"=>"userSelect");
                        $userselect=$oct->buildSelectList($users['results'], $attributes, "user_id", "real_name", $user_id, "All users", "group_name");
                        echo $userselect;
                    ?>                
                </div>
                <div class='form-group m-1'>
                    <select class='form-control smaller' id='caseGroupSelect'>
                        <option>All case groups</option>
                        <option value=''>...</option>
                    </select>        
                </div>                                         

            </div>
        </div>
        <div class="row">
            <div class='form-group m-1 p-1 col-xl text-center'>
                <div class='form-group m-1'>        
                    <button type='submit' class='btn btn-main btn-sm w-100 ml-1'>Search</button>
                </div>
            </div>            
            <div class='form-group m-1 p-1 text-nowrap text-right col-xl'>
                <div class='form-group m-1'> 
                    <a class='btn btn-info btn-sm smaller' href='#clear' role='button'>
                        Clear
                    </a>
                    <a class='btn btn-secondary btn-sm smaller' data-toggle='collapse' href='#filterMore' role='button' aria-expanded='false' aria-controls='filterMore'>
                        More >>
                    </a>
                </div>
            </div>
 
        </div>
        <p>
        <div class='collapse m-1' id='filterMore'>
            <div class='row'>
                <div class='form-group m-1 w-90'>
                    <select class='form-control smaller' id='dateRange'>
                        <option value='All'></option>
                    </select>
                </div>

            </div>    
            <div class='row'>


            </div> 
        </div>
        <div class="row">

        </div>  
<?php
  
?>
