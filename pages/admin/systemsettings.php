<?php
    //$oct->showArray($oct->config);
?>
<script src="js/pages/admin/systemsettings.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <img src="images/save.svg" class="floatright pointer img-fluid rounded ml-2" title="Save changes" id="saveSystemSettingsBtn"/>
            <img src="images/undo.svg" class="floatright pointer img-fluid rounded hidden ml-2" width='24px' title="Undo changes" id="undoSystemSettingsBtn"/>
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
                                <div class="col-5 text-right">
                                    <span title="Field name: <?php echo $vkey ?>"><?php echo $vval['title'] ?></span>
                                    <?php 
                                    $extraclass="";
                                    if(!$vval['set']) {
                                        //echo "<span style='cursor: pointer' title='Using default value'>*</span>";
                                        $extraclass="new";    
                                    } 
                                    ?>
                                </div>
                                <div class="col-7">
                                    <?php
                                    $thisvalue=$vval['value'];
                                    switch($vval['type']) {
                                        case "string":
                                            if(isset($vval['select']) && is_array($vval['select'])) {
                                            ?>    
                                            <select class='updatesettings form-control <?php echo $extraclass ?>' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' title='<?php echo $vval['description']; ?>'>
                                            <?php
                                                foreach($vval['select'] as $key=>$val) {
                                                    echo "<option value='$key'";
                                                    if($thisvalue==$key) echo " selected";
                                                    echo ">$val</option>\n";
                                                }
                                            ?>
                                            </select>
                                            <?php    
                                            } else {
                                            ?>
                                            <input type='text' class='updatesettings <?php echo $extraclass ?>' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' value='<?php echo $thisvalue ?>' title='<?php echo $vval['description']; ?>' size='30' />
                                            <?php
                                            }
                                            break;
                                        case "boolean":
                                            if(isset($vval['selectoptions']) && !empty($vval['selectoptions']) && is_array($vval['selectoptions'])) {
                                                ?>
                                                <select class='updatesettings <?php echo $extraclass ?>' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' title='<?php echo $vval['description'] ?>'>
                                                <?php
                                                foreach($vval['selectoptions'] as $skey=>$sval) {
                                                    echo "<option value='".$skey."'";
                                                    if($thisvalue==$skey) echo " selected";
                                                    echo ">".$sval."</option>\n";
                                                }
                                                ?>
                                                </select>
                                                <?php
                                            } else {
                                            ?>
                                            <input type='checkbox' class='updatesettings <?php echo $extraclass ?>' style='margin-top: 6px' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' title='<?php echo $vval['description'] ?>' <?php
                                                if($thisvalue === "true" || $thisvalue==1) echo "checked";
                                            ?> />
                                            <?php
                                            }
                                            break;
                                        case "password":
                                            ?>
                                            <input type='text' class='updatesettings <?php echo $extraclass ?>' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' value='<?php echo $thisvalue ?>' title='<?php echo $vval['description']; ?>' size='30' autocomplete="one-time-code" style='text-security: disc; -webkit-text-security: disc; -moz-text-security: disc' />
                                            <?php
                                            break;
                                        case "integer":
                                            ?>
                                            <input type='number' class='updatesettings <?php echo $extraclass ?>' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' value='<?php echo $thisvalue ?>' title='<?php echo $vval['description'] ?>' size='7' />
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