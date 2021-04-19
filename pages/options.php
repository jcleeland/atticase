<script src="js/pages/options.js"></script>
<?php

$options=array(
    "systemsettings"=>"System Settings",
    "displaysettings"=>"Display Settings",
    "departments"=>"Departments",
    "casegroups"=>"Case Groups",
    "emailtemplates"=>"Email Templates"
);  
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
                    include("pages/admin/".$_GET['option'].".php");
                }
                
            ?>
        </div>
        <!--<div class="col-xs-12 col-sm-10">
            <pre>
            <?php
                foreach($prefs as $name=>$pref) {
                    echo "<b>".$name."</b>: ".$pref['value']."<br /><i>".$pref['description']."</i><br /><br />";
                }
            ?>
            </pre>
        </div>-->
<?php
    }
?>

</div>