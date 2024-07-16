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
$settinglabels=array(
    "installation"=>"Installation",
    "externaldb"=>"External Database",
    "emailretrieval"=>"Emails: Retrieval",
    "emailsending"=>"Emails: Sending",
    "emailgeneral"=>"Emails: General",
    "billing"=>"Billing",
    "comments"=>"Comments",
    "notifications"=>"Notifications",
    "ldap"=>"LDAP",
    "general"=>"General AttiCase settings",
    "other"=>"Other settings",
);
$usewiths=array(); //We'll use this at the end of the script
ksort($configsettings);
?>
<script src="js/pages/admin/systemsettings.js"></script>
<div class='container-fluid'>
    <div class="row">
        <div class="fixed-top text-right pr-4" style="z-index: 99999; top: 150px;">
        <img src="images/undo.svg" class="pointer img-fluid rounded hidden ml-2" width='24px' title="Undo changes" id="undoSystemSettingsBtn"/>
        <img src="images/save.svg" class="pointer img-fluid rounded ml-2" title="Save changes" id="saveSystemSettingsBtn"/>
        </div>
        <div class="col-sm-12 mb-1 ">
            <div class="row justify-content-sm-center">
                
                <div class="col-sm-12">
                    <h4 class="header">System Settings</h4>
                    <div class="row border rounded">
                        <!-- Column 1 -->

                        <?php
                        //$oct->showArray($configsettings);
                        foreach($configsettings as $key=>$val) {
                            //Check in case this one has subsections - we'll know because there won't be any 
                            ?>
                        <div class="col-xs-12 col-sm-12">
                            <div class="row">
                                <div class="col-sm text-capitalize header m-3">
                                    <br />
                                    <b><?= isset($settinglabels[$key]) ? $settinglabels[$key] : $key ?></b>
                                </div>
                            </div>
                        </div>
                            <?php
                            foreach($val as $vkey=>$vval) {
                                if(isset($vval['usewith']) && !empty($vval['usewith'])) {
                                    $keypair=explode("=", $vval['usewith']);
                                    $usewiths[$vkey]=$keypair;
                                }
                                ?>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row pb-1" id="systemsetting_<?= $vkey ?>">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-5 text-right"">
                                            <span class="helpcursor" title="<?php echo $vval['description'] ?>"><?php echo $vval['title'] ?></span>
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
                                                    <select class='updatesettings form-control <?php echo $extraclass ?>' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' title='Field name: <?php echo $vkey ?>'>
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
                                                    <input type='text' class='updatesettings <?php echo $extraclass ?>' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' value='<?php echo $thisvalue ?>' title='Field name: <?php echo $vkey ?>' size='30' />
                                                    <?php
                                                    }
                                                    break;
                                                case "boolean":
                                                    if(isset($vval['selectoptions']) && !empty($vval['selectoptions']) && is_array($vval['selectoptions'])) {
                                                        ?>
                                                        <select class='updatesettings <?php echo $extraclass ?>' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' title='Field name: <?php echo $vkey ?>'>
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
                                                    <input type='checkbox' class='updatesettings <?php echo $extraclass ?>' style='margin-top: 6px' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' title='Field name: <?php echo $vkey ?>' <?php
                                                        if($thisvalue === "true" || $thisvalue==1) echo "checked";
                                                    ?> />
                                                    <?php
                                                    }
                                                    break;
                                                case "password":
                                                    ?>
                                                    <input type='text' class='updatesettings <?php echo $extraclass ?>' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' value='<?php echo $thisvalue ?>' title='Field name: <?php echo $vkey ?>' size='30' autocomplete="one-time-code" style='text-security: disc; -webkit-text-security: disc; -moz-text-security: disc' />
                                                    <?php
                                                    break;
                                                case "integer":
                                                    ?>
                                                    <input type='number' class='updatesettings <?php echo $extraclass ?>' id='config_<?php echo $vkey ?>' name='<?php echo $vkey ?>' value='<?php echo $thisvalue ?>' title='Field name: <?php echo $vkey ?>' size='7' />
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
            <div class="row justify-content-sm-center mt-4">
                <div class="col-12">
                    <h4 class="header">Setting tests</h4>
                    <div class="row border rounded p-2">
                        <div class="col-3">
                            <button class="btn btn-secondary" title="Sends a test email to the administrator email to test outgoing emails" onClick="testEmailSend()">Send test email</button>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-secondary" title="Attempts to collect and view incoming email using email retrieval settings" onClick="testEmailCheck()">Test email retrieval</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

$usewithcontrollers=array();
echo "<script type='text/javascript'>\n";
foreach($usewiths as $item=>$comparitor) {
    echo "  toggleVisibility('".$item."', '".$comparitor[0]."', '".$comparitor[1]."');\n";
    $usewithcontrollers[$comparitor[0]][]=array("item"=>$item, "value"=>$comparitor[1]);
}
echo "</script>";
echo "<script type='text/javascript'>\n";
foreach($usewithcontrollers as $controllerid=>$controlleditems) {
    echo "  var element = document.getElementById('config_".$controllerid."');\n";
    echo "  var eventType = (element.type === 'checkbox') ? 'click' : 'change';\n";
    echo "  element.addEventListener(eventType, function() {\n";
    echo "    var value = (this.type === 'checkbox') ? (this.checked ? 'true' : 'false') : this.value;\n";
    foreach($controlleditems as $item) {
        echo "    toggleVisibility('".$item['item']."', '".$controllerid."', '".$item['value']."');\n";
    }
    echo "  });\n";
}
echo "</script>";

?>