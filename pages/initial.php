<?php
  
?>
<div class="row h-50 justify-content-center align-items-center">
    <form class="col-5" method="post">
        <input type='hidden' name='initialise' value='true' />
        <div class='col mb-3 p-0'>
            <h3 style="font-weight: bold"><img src='images/logo.png'>OpenCaseTracker</h3>
        </div>
        <div class="col header mb-3">
            Local Database Setup
        </div>
        <div class='form-group'>
            <div class='w-75 floatright'><input type='text' class='form-control' name='dbname' id='dbname' placeholder='Database name' /></div>
            <div class='floatright w-25'>DB Name:</div><div style='clear: both'></div>
        </div> 
        <div class='form-group'>
            <div class='w-75 floatright'><input type='text' class='form-control' name='dbhost' id='dbhost' placeholder='Database Host IP' /></div>
            <div class='floatright w-25'>DB Host IP:</div><div style='clear: both'></div>       
        </div>
        <div class='form-group'>
            <div class='w-75 floatright'><input type='text' class='form-control' name='dbuser' id='dbuser' placeholder='Database User Name' /></div>
            <div class='floatright w-25'>DB Username:</div><div style='clear: both'></div>       
        </div>
        <div class='form-group'>
            <div class='w-75 floatright'><input type='password' class='form-control' name='dbpass' id='dbuser' placeholder='Database Password' /></div>
            <div class='floatright w-25'>DB Password:</div><div style='clear: both'></div>       
        </div>
        <div class='form-group'>
            <div class='w-75 floatright'><input type='' class='form-control' name='dbprefix' id='dbprefix' placeholder='Database Prefix' value='oct_' /></div>
            <div class='floatright w-25'>DB Prefix:</div><div style='clear: both'></div>       
        </div>
        
        <div class="col header mb-3">
            External Database Setup
        </div>
        <div class='form-group'>
            <div class='w-75 floatright'>
                <select name='useexternaldb' class='form-control' id='useexternaldb' placeholder='Use external DB?'>
                    <option value='false'>No</option>
                    <option value='true'>Yes</option>
                </select>
            </div>
            <div class='floatright w-25'>Use External DB?:</div><div style='clear: both'></div>
        </div> 
        <div class='form-group'>
            <div class='w-75 floatright'>
                <select class='form-control' name='externaldb' id='externaldb' placeholder='External DB Model' />
                    <option value='oms'>OMS (Open Membership System)</option>
                </select>
            </div>
            <div class='floatright w-25'>External DB Model:</div><div style='clear: both'></div>       
        </div>
        

        <div class='form-group'>
            <button class='btn btn-primary'>Initialise</button>
        </div>
    </form> 
</div>