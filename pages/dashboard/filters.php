        <script src="js/pages/dashboard/filters.js"></script>
        <h4 class="header">Search cases</h4>
        <div class='row'>
            <div class='form-group m-2 w-45'>
                <div class='form-group m-1'>
                    <input type='text' class='form-control-sm' id='casetext' aria-describedby='casetextHelp' placeholder='Search case texts'>
                </div>
                <div class='form-group m-1'>
                    <input type='text' class='form-control-sm' id='casetext' aria-describedby='tabtextHelp' placeholder='Search case tabs'>
                </div>
            </div>
            <div class='form-group m-2 w-45'>        
                <div class='form-group m-1 form-check'>
                    <input type='checkbox' class='form-check-input small' id='mycasesOnly' checked=checked />
                    <label class='form-check-label smaller' for='mycasesOnly'>My Cases</label>
                </div>
                <div class='form-group m-1 form-check'>
                    <input type='checkbox' class='form-check-input small' id='opencasesOnly' checked=checked />
                    <label class='form-check-label smaller' for='opencasesOnly'>Open Cases</label>        
                </div>
            </div>            
        </div>
        <div class="row">
            <div class='form-group m-2 w-45'>
            </div>
            <div class='form-group m-1 w-45 text-nowrap text-right'>
                <a class='btn btn-secondary btn-sm smaller' data-toggle='collapse' href='#filterMore' role='button' aria-expanded='false' aria-controls='filterMore'>
                    More >>
                </a>
            </div>

        </div>
        <p>
        <div class='collapse m-1' id='filterMore'>
            <div class='row'>
                <div class='form-group m-1 w-45'>
                    <select class='form-control smaller' id='caseTypeSelect'>
                        <option>All case types</option>
                        <option>etc</option>
                    </select>        
                </div>        
                <div class='form-group m-1 w-45'>
                    <select class='form-control smaller' id='userSelect'>
                        <option>All users</option>   
                        <option>etc</option>
                    </select>        
                </div>
            </div>    
            <div class='row'>
                <div class='form-group m-1 w-45'>
                    <select class='form-control smaller' id='caseGroupSelect'>
                        <option>All case groups</option>
                        <option>etc</option>
                    </select>        
                </div> 
                <div class='form-group m-1 w-45'>
                    <select class='form-control smaller' id='departmentSelect'>
                        <option>All departments</option>
                        <option>Dept Health & Human Services</option>
                    </select>        
                </div>
            </div> 
        </div>
        <div class="row">
            <div class='form-group m-2 w-100 text-center'>        
                <button type='submit' class='btn btn-main mb-2 w-75'>Search</button>
            </div>
        </div>  
<?php
  
?>
