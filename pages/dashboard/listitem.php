<div class="card mb-2">
    <div class="card-body p-0">
        <div class="card-header small p-2">
            <div class="float-right mr-2 border rounded pl-1 pr-1 calendar-div pointer"><input type='text' id='<?php echo $parent ?>_date_due_<?php echo $case_details['task_id'] ?>' class='datepicker' value='12/05/2020' /></div>
            <div class="float-left border rounded pl-1 pr-1 mr-2 case-link"><?php echo $case_details['task_id'] ?></div>
            <div class="float-left border rounded pl-1 pr-1 mr-2 pale-green-link userlink-<?php echo $case_details['member_status'] ?>"">Joe Bloggs<a class='fa-userlink' href=''></a></div>
            <div class='float-left col p-0 display-7'>Case Summary that is fairly long</div>
            <div style='clear: both'></div>
        </div>
    </div>
</div>