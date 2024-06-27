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
 
$notices=$oct->fetchMany("SELECT * FROM ".$oct->dbprefix."noticeboard as nb INNER JOIN ".$oct->dbprefix."users as users ON nb.created_by=users.user_id ORDER BY publish_date DESC", null, 0, 10000);

?>
<script src="js/pages/admin/noticeboard.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Noticeboard</h4>
            <div class="row border rounded centered w-100">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-5 text-center">
                            <span class="admin-headers">Title</span>
                        </div>
                        <div class="col-sm-1 text-center">
                            <span class="admin-headers">Published</span>
                        </div>
                        <div class="col-sm-2 text-center">
                            <span class="admin-headers">Publish Date</span>
                        </div>
                        <div class="col-sm-2 text-center">
                            <span class="admin-headers">Expiry Date</span>
                        </div>
                        <div class="col-sm-2 text-center">
                            <span class="admin-headers">Allow comments</span>
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto m-2 p-2 w-100" style="max-height: 400px">
                <?php
                    //$oct->showArray($notices);
                    foreach($notices['output'] as $notice) {
                        //$oct->showArray($notice);
                        
                        ?>
                    <div id="noticeboarditem_<?= $notice['id'] ?>" class="border rounded p-1 mb-2">
                        <div class="row mb-1">
                            <div class="col-sm-5 text-center">
                                <input type="text" class="form-control smaller" id="title_<?php echo $notice['id'] ?>" value="<?php echo $notice['title']; ?>" />
                            </div>
                            <div class="col-sm-1 text-center">
                                <input type="checkbox" class="form-control smaller" id="published_<?php echo $notice['id'] ?>" <?php if($notice['published']==1) {echo " checked=checked";} ?> >
                            </div>
                            <div class="col-sm-2 text-center">
                                <input type="text" class="form-control smaller datepicker datepicker_extras" id="publish_date_<?php echo $notice['id'] ?>" value="<?php echo DateTime::createFromFormat('Y-m-d', $notice['publish_date'])->format('d/m/Y'); ?>" />
                            </div>
                            <div class="col-sm-2 text-center">
                                <input type="text" class="form-control smaller datepicker datepicker_extras" id="expiry_date_<?php echo $notice['id'] ?>" value="<?php echo DateTime::createFromFormat('Y-m-d', $notice['expiry_date'])->format('d/m/Y'); ?>" />
                            </div>
                            <div class="col-sm-2 text-center">
                                <input type="checkbox" class="form-control smaller" id="allow_comments_<?php echo $notice['id'] ?>" <?php if($notice['allow_comments']==1) {echo " checked=checked";} ?> >  
                            </div>
                            <input type="hidden" id="created_by_<?php echo $notice['id'] ?>" value="<?php echo $notice['created_by']; ?>" />
                        </div>
                        <div class="row mb-1">
                            <div class="col-sm-10">
                                <textarea class="form-control smaller summernoteme" placeholder="Notice" id="message_<?php echo $notice['id'] ?>" name="message"><?php echo $notice['message']; ?></textarea>    
                            </div>
                            <div class="col-sm-2 d-flex flex-column justify-content-end text-center">
                                <p>
                                    <span class="smallest italic">Created by</span><br />
                                    <?= $notice['real_name']; ?><br />
                                    <span class="smaller italic"><?= $notice['created_at']; ?></span>
                                </p>                            
                                <span class="btn btn-sm btn-main updatenoticeboarditem mb-1" data-noticeboardid="<?php echo $notice['id'] ?>">Update</span>
                                <span class="btn btn-sm btn-danger deletenoticeboarditem mb-1" data-noticeboardid="<?php echo $notice['id'] ?>">Delete</span>
                            </div>
                        </div>
                    </div>                            
                <?php
                    }
                ?>
                </div>

                <div class="form-group overflow-auto p-2">
                    <h4 class="header">Add Notice</h4>
                    <div class="row border rounded centered">
                        <div class="p-2 w-100">
                            <div class="row mb-1 text-center">
                                <div class="col-sm-5 text-center">
                                    <span class="admin-headers">Title</span>
                                </div>
                                <div class="col-sm-1 text-center">
                                    <span class="admin-headers">Published</span>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <span class="admin-headers">Publish Date</span>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <span class="admin-headers">Expiry Date</span>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <span class="admin-headers">Allow comments</span>
                                </div> 
                            </div>   
                        </div>
                        <div class="form-group overflow-auto m-2 p-2 w-100">
                            <div class="row mb-1">
                                <div class="col-sm-5">
                                    <input action="title" class="form-control smaller" placeholder="Notice title" id="newtitle" type="text" name="new_title" />
                                </div>
                                <div class="col-sm-1">
                                    <input action="published" class="form-control smaller" placeholder="Publish?" id="newpublished" type="checkbox" size="2" name="published" />
                                </div>
                                <div class="col-sm-2 text-center">
                                    <input action="publish_date" class="form-control smaller datepicker datepicker_extras" placeholder="Date to publish" id="newpublish_date" type="text" name="publish_date" value="<?php echo date("d/m/Y", time()); ?>" />
                                </div>
                                <div class="col-sm-2 text-center">
                                    <input action="expiry_date" class="form-control smaller datepicker datepicker_extras" placeholder="Date to expire" id="newexpiry_date" type="text" name="expiry_date" value="<?php echo date("d/m/Y", strtotime('1 month')); ?>" />
                                </div>
                                <div class="col-sm-2 text-center">
                                    <input action="allow_comments" class="form-control smaller" placeholder="Allow comments?" id="newallow_comments" type="checkbox" size="2" name="allow_comments" checked=checked />
                                </div>      
                            </div>
                            <div class="row mb-1">
                                <div class="col-sm-10">
                                    <textarea action='message' class="form-control smaller summernoteme" placeholder="Notice" id="newmessage" name="message"></textarea>
                                </div>
                                <div class="col-sm-2 d-flex flex-column text-center justify-content-end">
                                    <input type="hidden" id="newcreated_by" value="<?= $_SESSION['user_id']; ?>" />
                                    <span class="btn btn-sm btn-main createnoticeboarditem mb-2">Add</span>
                                </div>
                            </div>         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
?>