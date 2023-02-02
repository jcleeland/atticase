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

                <?php
                //$oct->showArray($configsettings);
                foreach($configsettings as $key=>$val) {
                    ?>
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-sm text-capitalize header m-3">
                            <br />
                            <b><?php echo $key ?></b>
                        </div>
                    </div>
                </div>
                    <?php
                    foreach($val as $vkey=>$vval) {
                        ?>
                <div class="col-xs-12 col-sm-6">
                    <div class="row pb-1">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-5 text-right"><?php echo $vval['title'] ?></div>
                                <div class="col-7">
                                    <?php
                                    $thisvalue=isset($prefs[$vkey]['value']) ? $prefs[$vkey]['value'] : $vval['default'];
                                    switch($vval['type']) {
                                        case "string":
                                            ?>
                                            <input type='text' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' value='<?php echo $thisvalue ?>' title='<?php echo $vval['description']; ?>' size='30' />
                                            <?php
                                            break;
                                        case "boolean":
                                            ?>
                                            <input type='checkbox' style='margin-top: 6px' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' title='<?php echo $vval['description'] ?>' <?php
                                                if($thisvalue === "true" || $thisvalue==1) echo "checked";
                                            ?> />
                                            <?php
                                            break;
                                        case "password":
                                            ?>
                                            <input type='password' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' value='<?php echo $thisvalue ?>' title='<?php echo $vval['description']; ?>' size='30' />
                                            <?php
                                            break;
                                        case "integer":
                                            ?>
                                            <input type='number' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' value='<?php echo $thisvalue ?>' title='<?php echo $vval['description'] ?>' size='7' />
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
                
                
                
                
                
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-sm text-capitalize header m-3">
                            <br />
                            <b>Other</b>
                        </div>
                    </div>
                </div>                
                <?php 
                foreach($prefs as $prefname=>$prefvalue) {
                    //Extract any settings which are covered in configsettings
                    switch($prefname) {
                        case "allow_billing":
                        case "billing_rate":
                        case "lang_code":
                        case "allow_restricted":
                        case "admin_email":
                        case "additional_admins_p4":
                        case "project_title":
                        case "base_url":
                        case "restrict_view":
                        case "theme_style":
                        case "version":
                            break;
                        default:
                            ?>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row pb-1">                
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-5 text-right"><?php echo $prefname ?></div>
                                            <div class="col-7"><input type='text' name='pref_<?php echo $prefname ?>' value='<?php echo $prefvalue['value'] ?>' title='<?php echo $prefvalue['description'] ?>'/></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
                    }
                }
                ?>
                                
            </div>
        </div>
    </div>
</div>