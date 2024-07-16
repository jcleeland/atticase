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
$users=$oct->userList(array(), null, null, 0, 1000000000);

//Get a list of case types
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";
$emailtemplates=$oct->emailTemplateList(array(), null, null, 0, 10000000);



?>
<script src="js/pages/admin/emailtemplates.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Email Templates</h4>
            <div class="row border rounded centered">
                <div class="p-1 w-100">
                    <div class="row mb-1 m-1 rounded align-items-center card-heading-inverse">
                        <div class="col-sm-5 p-0 text-right">
                            Email Template Group: 
                        </div>
                        <div class="col-sm-3 p-0 ">
                            <select class="form-control float smaller m-1" id="emailtemplatefilter" name="emailtemplatefilter">
                                <option value="all">All</option>
                                <option value="client">Client Emails</option>
                                <option value="user">System Emails</option>
                            </select>
                        </div>
                    </div>
                </div>
                <form class="w-100">
                <div class="overflow-auto p-2 w-100" style="max-height: 600px" >
                <?php
                foreach($emailtemplates['results'] as $template) {
                    $indent="";
                    $id=$template['template_id'];
                    if(!isset($template['event_type'])) $template['event_type']="";
                    $groupid=$template['project_id']; //Todo - change the database name to email_template_group_id and add event_type field
                    if ($groupid==999) {
                        $parentKey = searchArray($defaultSystemEmails, 'name', $template['name']);
                        if ($parentKey !== null) {
                            // Key-value pair found
                            unset($defaultSystemEmails[$parentKey]);
                        }
                    }
                    ?>
                    <div class="row mb-1 emailtemplate border rounded" data-group-id="<?= $groupid ?>" id="emailtemplate_<?= $id ?>">
                        <input type="hidden" name="id[]" value="<?= $id ?>" />
                        <div class="col-sm-11">                        
                            <input action="emailtemplate_name" title="Click here to change name" emailtemplateid="<?= $id ?>" class="mb-1 form-control form-control-like-heading updateemailtemplatefield <?php echo $indent ?>" placeholder="Email Template Name" title='Department ID <?php echo $id ?>' id="emailtemplatename_<?php echo $id ?>" type="text" name="emailtemplate_name[]" value="<?php echo $template['name'] ?>" />
                            <div class="row">
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <div class="text-right w-100 row m-0 p-0 mb-1">
                                        <div class="text-right w-100 row m-0 p-0 mb-1">
                                            <div class="col-2">
                                                Event Type:
                                            </div>
                                            <div class="col-10 pr-0">
                                                <select action="emailtemplate_event_type" emailtemplateid="<?= $id ?>" class="form-control smaller updateemailtemplatefield" id="emailtemplateeventtype_<?php echo $id ?>" name="emailtemplate_event_type[]" >
                                                    <option value="">**Case / Client emails</option>
                                                    <?php
                                                    foreach($oct->eventTypes as $key=>$val) {
                                                        echo "<option value='$key'";
                                                        if($key==$template['event_type']) echo " selected";
                                                        echo ">$val</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>                                        
                                        <div class="col-2">
                                            Subject:
                                        </div>
                                        <div class="col-10 pr-0">
                                            <input action="emailtemplate_subject" emailtemplateid="<?= $id ?>" class="form-control smaller updateemailtemplatefield" placeholder="Email Template Subject" id="emailtemplatesubject_<?php echo $id ?>" type="text" name="emailtemplate_subject[]" value="<?php echo $template['subject'] ?>" />
                                        </div>
                                    </div>
                                    <div class="text-right w-100 row m-0 p-0 mb-1">
                                        <div class="col-2">
                                            Attachment:
                                        </div>
                                        <div class="col-10 pr-0">
                                            <input action="emailtemplate_attachment" emailtemplateid="<?= $id ?>" class="form-control smaller updateemailtemplatefield"  placeholder="Email Template Attachment" id="emailtemplateattachment_<?php echo $id ?>" type="text" name="emailtemplate_attachment[]" value="<?php echo $template['attachment'] ?>" />
                                    
                                        </div>
                                    </div>
                                    <textarea action="emailtemplate_message" rows=8 emailtemplateid="<?= $id ?>" class="form-control smaller scrollablediv updateemailtemplatefield emailmessagebody" rows="5" placeholder="Email Template Message" id="emailtemplatemessage_<?php echo $id ?>" type="text" name="emailtemplate_message[]" ><?= $template['message'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <img src='images/save.svg' height='30px' title='Save changes' class='btn pale-green-link disabledimage smaller m-1' />
                            <img src='images/ban.svg' height='30px' title='Delete template' class='btn red-link disabledimage smaller m-1' />
                        </div>                        
                    </div>
                    <?php
                }

                foreach($defaultSystemEmails as $template) { 
                    $id=0;
                    $groupid=$template['project_id'];
                    ?>
                    <div class="row mb-1 emailtemplate border rounded" data-group-id="<?= $groupid ?>" id="emailtemplate_<?= $id ?>">
                        <input type="hidden" name="id[]" value="<?= $id ?>" />
                        <div class="col-sm-11">                        
                            <span class='form-control form-control-like-heading' title='This is a system email and cannot be renamed'><?= $template['name'] ?></span>
                            <div class="row">
                                <div class="col-1"></div>
                                <div class="col-11">
                                    <div class="text-right w-100 row m-0 p-0 mb-1">
                                        <div class="col-2">
                                            Event Type:
                                        </div>
                                        <div class="col-10 pr-0">
                                            <select action="emailtemplate_event_type" emailtemplateid="<?= $id ?>" class="form-control smaller updateemailtemplatefield" id="emailtemplateeventtype_<?php echo $id ?>" name="emailtemplate_event_type[]" >
                                                <option value="">Please choose...</option>
                                                <?php
                                                foreach($oct->eventTypes as $key=>$val) {
                                                    echo "<option value='$key'";
                                                    if($key==$template['event_type']) echo " selected";
                                                    echo ">$val</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-right w-100 row m-0 p-0 mb-1">
                                        <div class="col-2">
                                            Subject:
                                        </div>
                                        <div class="col-10 pr-0">
                                            <input action="emailtemplate_subject" emailtemplateid="<?= $id ?>" class="form-control smaller updateemailtemplatefield" placeholder="Email Template Subject" id="emailtemplatesubject_<?php echo $id ?>" type="text" name="emailtemplate_subject[]" value="<?php echo $template['subject'] ?>" />
                                        </div>
                                    </div>
                                    <div class="text-right w-100 row m-0 p-0 mb-1">
                                        <div class="col-2">
                                            Attachment:
                                        </div>
                                        <div class="col-10 pr-0">
                                            <input action="emailtemplate_attachment" emailtemplateid="<?= $id ?>" class="form-control smaller updateemailtemplatefield"  placeholder="Email Template Attachment" id="emailtemplateattachment_<?php echo $id ?>" type="text" name="emailtemplate_attachment[]" value="<?php echo $template['attachment'] ?>" />
                                    
                                        </div>
                                    </div>
                                    <textarea action="emailtemplate_message" rows=8 emailtemplateid="<?= $id ?>" class="form-control smaller scrollablediv updateemailtemplatefield emailmessagebody" rows="5" placeholder="Email Template Message" id="emailtemplatemessage_<?php echo $id ?>" type="text" name="emailtemplate_message[]" ><?= $template['message'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <img src='images/save.svg' height='30px' title='Save changes' class='btn pale-green-link disabledimage smaller m-1' />
                            <img src='images/ban.svg' height='30px' title='Delete template' class='btn red-link disabledimage smaller m-1' />
                        </div>                        
                    </div>
                    <?php
                }
                ?>
                </div>
        </div>
    </div>
</div>


<?php
//$oct->showArray($defaultsystememails);  

function searchArray($array, $key, $value) {
    foreach ($array as $parentKey => $subarray) {
        if (isset($subarray[$key]) && $subarray[$key] == $value)
            return $parentKey;
        elseif (is_array($subarray)) {
            $result = searchArray($subarray, $key, $value);
            if ($result !== null)
                return $result;
        }
    }
    return null;
}

?>
