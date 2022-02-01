<?php
require_once("helpers/configsettings.php");  
?>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <img src="images/save.svg" class="floatright pointer img-fluid rounded ml-2" title="Save changes" id="saveDepartmentsBtn"/>
            <img src="images/undo.svg" class="floatright pointer img-fluid rounded hidden ml-2" width='24px' title="Undo changes" id="undoDepartmentsBtn"/>
            <h4 class="header">System Settings</h4>
            <div class="row border rounded">
                <!-- Column 1 -->
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['base_url']['description'] ?>">
                            Base URL
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='base_url' size=30 value='<?php echo $prefs['base_url']['value']; ?>' title='<?php echo $prefs['base_url']['description'] ?>'/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['admin_email']['description'] ?>">
                            Reply email for notifications
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='admin_email' size=30 value='<?php echo $prefs['admin_email']['value']; ?>' />
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['version']['description'] ?>">
                            Database version
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='admin_email' value='<?php echo $prefs['version']['value']; ?>' />
                        </div>                        
                    </div>                     
                </div>
                
                <!-- Column 2 -->
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['allow_billing']['description'] ?>">
                            Allow billing
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control' name='allow_billing'>
                                <option value='0'>No</option>
                                <option value='1' <?php if($prefs['allow_billing']['value']==1) echo " selected" ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['billing_rate']['description'] ?>">
                            Billing rate
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='admin_email' size=5 value='<?php echo $prefs['billing_rate']['value']; ?>' />
                        </div>                        
                    </div> 
                    <div class="row">                    
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['assigned_groups']['description'] ?>">
                            Assignable Groups
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="assign_admin" /> <label for="assign_admin">Admin</label>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['allow_restricted']['description'] ?>">
                            Allow task restriction
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="allow_restricted" checked="<?php if($prefs['allow_restricted']['value']==1) echo "checked" ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['anon_open']['description'] ?>">
                            Allow anonymous to create
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="anon_open" checked="<?php if($prefs['anon_open']['value']==1) echo "checked" ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['restrict_view']['description'] ?>">
                            Restrict views
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="anon_open" checked="<?php if($prefs['restrict_view']['value']==1) echo "checked" ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                        </div>
                    </div>
                </div>
                
                <!-- Other settings -->
                <?php
                foreach($configsettings as $key=>$val) {
                    ?>
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-sm text-capitalize header m-2">
                            <br />
                            <b><?php echo $key ?></b>
                        </div>
                    </div>
                </div>
                    <?php
                    foreach($val as $vkey=>$vval) {
                        ?>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-4 text-right"><?php echo $vkey ?></div>
                                        <div class="col-8">
                                            
                                            <?php
                                            switch($vval['type']) {
                                                case "string":
                                                    ?>
                                                    <input type='text' name='<?php echo $vkey ?>' value='<?php echo $vval['default'] ?>' title='<?php echo $vval['description']; ?>' size='30' />
                                                    <?php
                                                    break;
                                                case "boolean":
                                                    ?>
                                                    <input type='checkbox' style='margin-top: 6px' name='<?php echo $vkey ?>' title='<?php echo $vval['description'] ?>' <?php
                                                        if($vval['default'] === "true") echo "checked";
                                                    ?> />
                                                    <?php
                                                    break;
                                                case "password":
                                                    ?>
                                                    <input type='password' name='<?php echo $vkey ?>' value='<?php echo $vval['default'] ?>' title='<?php echo $vval['description']; ?>' size='30' />
                                                    <?php
                                                    break;
                                                case "integer":
                                                    ?>
                                                    <input type='number' name='<?php echo $vkey ?>' value='<?php echo $vval['default'] ?>' title='<?php echo $vval['description'] ?>' size='5' />
                                                    <?php
                                                    break;
                                                default:
                                                    ?>
                                                    <?php echo $vval['description'] ?>: <?php echo $vval['type'] ?>
                                                    <?php
                                            }
                                            
                                            if(isset($settings[$vkey])) {
                                                
                                            }                                      
                                            ?>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <?php
                    }
                }
                    
                ?>
                
                
                                
            </div>
        </div>
    </div>
</div>