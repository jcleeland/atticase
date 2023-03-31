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
 
$users=$oct->userList(array(), "account_enabled=1 AND group_open != 0", null);
$userSelect=$oct->buildSelectList($users['results'], array("id"=>"newUserId", "class"=>"formControl"), "user_id", "real_name", null, "Select user", "group_name");

?>
<script src="js/pages/casetabs/notifications.js"></script>

<div class='justify-content-center'>
    <div class='float-right pale-green-link rounded mr-3 mb-1 p-1 small' id='newNotificationBtn'>
        <img src='images/plus.svg' width='12px' /> Note
    </div>
    <div class='float-right m-2'>
        <input type='text' class='roundedcorners ml-4 form-control-sm form-transparent-sm text-muted' id='notifications-inpage_filter' title='Search currently showing results...' />
    </div>
    <div style='clear: both'></div>
    <div class='form-group hidden border rounded p-2 m-2' id='newNotificationForm'>
        <h4 class="header">Add a notification</h4>
        <div class="pager rounded-bottom w-100">&nbsp;</div>
        <div class="row w-100">
            <div class="col-2"></div>
            <div class="col-2 text-right">
                Notify
            </div>
            <div class="col-2">
                <?php echo $userSelect ?>
            </div>
            <div class="col-2">
                when this case changes
            </div>
            <div class="col-2">
                <button class="form-control pale-green-link" id='submitNotificationBtn'>Submit</button>
            </div>
            <div class="col-2"></div>
        </div> 
        
    </div>
</div>
<div style='clear: both'></div>

<div id='notificationlist' class="justify-content-center">
</div>
