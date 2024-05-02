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
//TODO: Check to see if there is already a config.php file
// =dirname(__FILE__)."/../config/config.php";
// If there is, then alert the user
// If there isn't, check the permissions on the directory to see if the config.php file is allowed to be created
//  - if there is no permission, alert the user  

$dbname=$oct->dbname ? $oct->dbname : "";
$dbuser=$oct->dbuser ? $oct->dbuser : "";
$dbpass=$oct->dbpass ? $oct->dbpass : "";
$dbhost=$oct->dbhost ? $oct->dbhost : "localhost";
$dbprefix=$oct->dbprefix ? $oct->dbprefix : "atticase_";

if(isset($message)) {
    ?>
    <div id='alertBox' class='alert'>Error!<br /><?= $message ?></div>
    <script type='text/javascript'>
        // Script to fade out the alert after 1 minute (60000 milliseconds)
        window.onload = function() {
        setTimeout(function() {
            var alertBox = document.getElementById('alertBox');
            if (alertBox) {
            alertBox.classList.add('fade-out');
            }
        }, 10000); // Change 60000 to however many milliseconds you want (60000ms = 1 minute)
        };
    </script>
    <?php
}
?>

<div class="row h-50 justify-content-center align-items-center">
    <form class="col-5" method="post">
        <input type='hidden' name='initialise' value='true' />
        <div class='col mb-3 p-0'>
            <h3 style="font-weight: bold"><img src='images/logo.png'>AttiCase</h3>
        </div>
        <div class="col header mb-3">
            Local Database Setup
        </div>
        <div class="mb-3 smaller">
            AttiCase needs to be connected to a database. Your system administrator should have created an empty database on your local server and given you the name, username and password for it. Enter those details below.
        </div>
        <div class='form-group'>
            <div class='w-75 floatright'><input type='text' class='form-control' name='dbname' id='dbname' placeholder='Database name' value='<?= $dbname ?>' /></div>
            <div class='floatright w-25'>DB Name:</div><div style='clear: both'></div>
        </div> 
        <div class='form-group'>
            <div class='w-75 floatright'><input type='text' class='form-control' name='dbhost' id='dbhost' placeholder='Database Host IP' value='<?= $dbhost ?>' /></div>
            <div class='floatright w-25'>DB Host IP:</div><div style='clear: both'></div>       
        </div>
        <div class='form-group'>
            <div class='w-75 floatright'><input type='text' class='form-control' name='dbuser' id='dbuser' placeholder='Database User Name' value='<?= $dbuser ?>' /></div>
            <div class='floatright w-25'>DB Username:</div><div style='clear: both'></div>       
        </div>
        <div class='form-group'>
            <div class='w-75 floatright'><input type='password' class='form-control' name='dbpass' id='dbpass' placeholder='Database Password' value='<?= $dbpass ?>' /></div>
            <div class='floatright w-25'>DB Password:</div><div style='clear: both'></div>       
        </div>
        <div class='form-group'>
            <div class='w-75 floatright'><input type='' class='form-control' name='dbprefix' id='dbprefix' placeholder='Database Prefix' value='<?= $dbprefix ?>' /></div>
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
            <div class='floatright w-25'>Use External Database?:</div><div style='clear: both'></div>
        </div> 
        <div class='form-group'>
            <div class='w-75 floatright'>
                <select class='form-control' name='externaldb' id='externaldb' placeholder='External DB Model' />
                    <option value=''>None</option>
                    <option value='oms'>OMS (Open Membership System)</option>
                </select>
            </div>
            <div class='floatright w-25'>External DB Model:</div><div style='clear: both'></div>       
        </div>
        

        <div class='form-group'>
            <button class='btn btn-primary'>Initialise</button>
        </div><div style='clear: both'></div>
    </form> 
</div>