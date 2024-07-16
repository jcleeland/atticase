<?php
    if(!isset($_SESSION['administrator']) || !$_SESSION['administrator']==1) {
        die("<div class='d-flex justify-content-center'>Your account does not have permission to see or change Options</div>");
    }
?>
<script src="js/pages/options.js"></script>
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
$options=array(
    //"displaysettings"=>"Display Settings",
    "casetypes"=>"Case Types",
    "casegroups"=>"Case Groups",
    "people"=>"Clients",
    "customfields"=>"Custom Fields",
    "customtexts"=>"Custom Texts",
    "emailtemplates"=>"Email Templates",
    "departments"=>"Departments",
    "noticeboard"=>"Notice Board",
    "notifications"=>"Notifications",
    "poi"=>"People of Interest",
    "resolutions"=>"Resolutions",
    "scheduler"=>"Scheduler",
    "systemsettings"=>"System Settings",
    "users"=>"Users and Groups"
);

if(isset($configsettings['general']['unit_list_use']['value']) && $configsettings['general']['unit_list_use']['value'] > 0) {
    $options['units']="Units";
    
} 

?>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-xs-12 col-sm-2">
            <h4 class="header">Options</h4>
<?php
    if($_SESSION['administrator']!=1) {        
?>            
                <p>Only administrators have access to system options</p>                
<?php
    } else {
?>
            <ul class="list-group">
<?php
    foreach($options as $key=>$value) {
        echo "      <a href='index.php?page=options&option=".$key."' class='list-group-item ";
        if(isset($_GET['option']) && $_GET['option']==$key) echo "active ";
        echo "'>".$value."</a>\n";
    }
?>
            </ul>
        </div>
        <div class="col-xs-12 col-sm-10">
            <?php
                if(isset($_GET['option'])) {
                    //Check if the option page exists
                    if(!file_exists("pages/admin/".$_GET['option'].".php")) {
                        echo "<input type='hidden' id='message' value='The requested option page is not available' />";
                        echo "<input type='hidden' id='messageTitle' value='Page not found' />";
                        //echo "<div class='alert alert-warning'>The requested option page is not currently available</div>";
                    } else {
                        include("pages/admin/".$_GET['option'].".php");
                    }
                }
                
            ?>
        </div>
        <!--<div class="col-xs-12 col-sm-10">
            <pre>
            <?php
                if(isset($prefs) && is_array($prefs)) {
                    foreach($prefs as $name=>$pref) {
                        echo "<b>".$name."</b>: ".$pref['value']."<br /><i>".$pref['description']."</i><br /><br />";
                    }
                }
            ?>
            </pre>
        </div>-->
<?php
    }
?>
</div>