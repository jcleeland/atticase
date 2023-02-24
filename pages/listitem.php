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
?>

<div class="card mb-2">
    <div class="card-body p-0">
        <div class="card-header p2">
            <div class="float-right mr-2 border rounded pl-1 pr-1 calendar-div pointer"><input type='text' id='date_due'  class='datepicker status-<?php echo $case_details['date_status'] ?>' value='<?php echo $case_details['date_due'] ?>' /></div>
            <div class="float-left border rounded pl-1 pr-1 mr-2 case-link"><?php echo $case_details['task_id'] ?></div>
            <div class="float-left border rounded pl-1 pr-1 mr-2 pale-green-link userlink-<?php echo $case_details['member_status'] ?>"><?php echo $case_details['name'] ?><a class='fa-userlink' href=''></a></div>
            <div class='float-left p-0 display-5'><?php echo $case_details['item_summary'] ?></div>
            <div style='clear: both'></div>
        </div>
        <div class="card-body collapse" id="case-card">
            <p class="card-text overflow-auto" style='max-height: 100px'><?php echo $case_details['detailed_desc'] ?></p>
        </div>
        <div class="card-footer small font-italic pt-1 pb-1">
            <div class="float-right border rounded pl-1 pr-1 mr-2 pale-grey-link"><?php echo $case_details['assigned_to'] ?></div>
            Last edited: 13 April 2020
        </div>
        
    </div>
</div>