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
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-2">
                            Name
                        </div>
                        <div class="col-sm-2">
                            Subject
                        </div>
                        <div class="col-sm-4">
                            Message
                        </div>                   
                        <div class="col-sm-2">
                            Attachment
                        </div>
                        <div class="col-sm-2">
                            Actions
                        </div>
                    </div>
                </div>
                <div class="overflow-auto p-2 w-100" style="max-height: 600px" >
                <?php
                foreach($emailtemplates['results'] as $template) {
                    $id=$template['template_id'];
                    ?>
                    <div class="row mb-1" id="emailtemplate_<?= $id ?>">
                        <input type="hidden" name="id[]" value="<?= $id ?>" />
                        <div class="col-sm-2">                        
                            <input action="emailtemplate_name" emailtemplateid="<?= $id ?>" class="form-control smaller updateemailtemplatefield <?php echo $indent ?>" placeholder="Email Name" title='Department ID <?php echo $id ?>' id="emailtemplatename_<?php echo $id ?>" type="text" name="emailtemplate_name[]" value="<?php echo $template['name'] ?>" />
                        </div>
                        <div class="col-sm-2">
                            <input action="emailtemplate_subject" emailtemplateid="<?= $id ?>" class="form-control smaller updateemailtemplatefield" placeholder="Email Subject" id="emailtemplatesubject_<?php echo $id ?>" type="text" name="emailtemplate_subject[]" value="<?php echo $template['subject'] ?>" />
                        </div>
                        <div class="col-sm-4">
                            <textarea action="emailtemplate_message" emailtemplateid="<?= $id ?>" class="form-control smaller scrollablediv updateemailtemplatefield" rows="5" placeholder="Email Message" id="emailtemplatemessage_<?php echo $id ?>" type="text" name="emailtemplate_message[]" ><?= $template['message'] ?></textarea>
                        </div>
                        <div class="col-sm-2">
                            <input action="emailtemplate_subject" emailtemplateid="<?= $id ?>" class="form-control smaller updateemailtemplatefield" placeholder="Email Attachment" id="emailtemplateattachment_<?php echo $id ?>" type="text" name="emailtemplate_attachment[]" value="<?php echo $template['attachment'] ?>" />
                        </div>
                        <div class="col-sm-2">
                            <img src='images/save.svg' style='height: 30%' class='btn pale-green-link disabledimage smaller' />
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
//$oct->showArray($emailtemplates);  

?>
