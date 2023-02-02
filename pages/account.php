<?php

?>
<script src="js/pages/account.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-8">
            <h4 class="header">Account Details</h4>
            <div class="row border rounded">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Name
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='username' value='Roger Officer' />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Email
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='email' value='rofficer@casetracker.org' />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Password
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='password' class='form-control' name='password' value='password' />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Group
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control' name='group'>
                                <option value=''>Choose...</option>
                                <option value'longname'>Something quite long</option>
                            </select>
                        </div>
                    </div>                    

                </div>
                <div class="col-xs-12 col-sm-6">    
                    <div class="row">                    
                        <div class="subSection-label col-xs-4 m-1">
                            Username
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='username' value='rofficer' />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Phone
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='phone' value='(03) 1234 5676' />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Confirm Password
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='password' class='form-control' name='confirmpassword' value='password' />
                        </div>
                    </div> 
                    <div class="form-row">
                        <div class="col-3 m-1">
                            <input type='checkbox' class='form-check-input checkbox' name='admin' id='admin'>
                            <label class='form-check-label' for='admin'>Admin</label>
                        </div>
                        <div class="col-4 m-1">
                            <input type='checkbox' class='form-check-input checkbox' name='enabled' id='enabled'>
                            <label class='form-check-label' for='admin'>Account enabled</label>                            
                        </div>
                        <div class="col-4 m-1">
                            <input type='checkbox' class='form-check-input checkbox' name='strategy' id='strategy'>
                            <label class='form-check-label' for='admin'>Can add strategy</label>
                        </div>

                    </div>                                      
                </div>
            </div>
            
            <div class="row border rounded mt-2">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Default view
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control' name='defaultView'>
                                <option value=''>Default</option>
                                <option value='dashboard'>Dashboard</option>
                                <option value='cases'>All open Cases</option>
                                <option value='mycases'>Only my open cases</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Summary emails
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control' name='defaultView'>
                                <option value='never'>Never</option>
                                <option value='daily'>Daily</option>
                                <option value='weekley'>Weekly</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Notify own actions
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control' name='notifyOwnActions'>
                                <option value='no'>No</option>
                                <option value='yes'>Yes</option>
                            </select>
                        </div>
                    </div>                    
                </div>
            
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Default case type
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control' name='defaultView'>
                                <option value=''>Standard Case</option>
                                <option value='dashboard'>Others</option>
                                <option value='cases'>All open Cases</option>
                                <option value='mycases'>Only my open cases</option>
                            </select>
                        </div>                    
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Last summary email sent:
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <div class='float-left border'>
                                01 Jul 2020
                            </div>
                        </div>                    
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class='subSection-field w-100 m-1'>
                            <button class='btn btn-primary float-right m-2'>Update</button>
                            <button class='btn btn-secondary float-right m-2' id='logout' onClick='window.location.href="index.php?logout=true"'>Logout</button>
                            <button class='btn btn-secondary float-right m-2' id='logoutcookies' onClick='logoutCookies()'>Clear cookies and logout</button>
                        </div>
                    </div>            
                </div>
        </div>

    </div>

</div>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-8">
            <h4 class="header">Session</h4>
            <div class="row border rounded">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Case Viewing History
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <div id="case_viewing_history">
                            </div>
                            <button id="clear_case_history">Clear Case History</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

?>
