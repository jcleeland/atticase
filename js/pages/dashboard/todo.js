$(function() {
    loadTodo();
    
    $('#filterTodo').keyup(function() {
        //console.log($(this).val());
        var search=$(this).val();
        $('.todoitem').each(function() {
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
}) 

function loadTodo() {
    var today=new Date();
    
    var parameters={};
    parameters[':assignedto']=$('#user_id').val();
    parameters[':isclosed']=1;
    parameters[':datedue']=today.getTime() / 1000 | 0;
    
    var conditions='assigned_to = :assignedto AND is_closed != :isclosed AND date_due <= :datedue';
    
    var order='date_due ASC';
    
    var start=parseInt($('#todostart').val()) || 0;
    var end=parseInt($('#todoend').val()) || 9;
    
    if(start<0) {
        start=0;
        $('#todostart').val(0);
    }
    if(end<9) {
        end=9;
        $('#todoend').val(9);
    }
    
    //console.log('Doing cases, '+start+' to '+end);
    
    $('#todolist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>");
    
    
    $.when(caseList(parameters, conditions, order, start, end)).done(function(cases) {
        //console.log('Cases');
        //console.log(cases);
        if(cases.count===0) {
            //console.log('Nothing');
            $('#todolist').html("<center><br />No cases in todo list<br />&nbsp;</center>");
        } else {
            pagerNumbers('todo', start, end, cases.total);
            $('#todolist').html('');
            $.each(cases.results, function(i, casedata) {
                /* Put formatting into a standalone script */
                
                insertCaseCard('todolist', 'todolist'+casedata.task_id, casedata);
                /* var thisDateDue=timestamp2date(casedata.date_due);
                if(typeof casedata.pref_name !== 'undefined') {
                    var client=casedata.pref_name+' '+casedata.surname;
                } else {
                    var client=casedata.member;
                }
                var dateclass='date-future';
                if(casedata.date_due/1000 < $('#today_start').val()) {dateclass='date-overdue';}
                if(casedata.date_due/1000 >= $('#today_start').val() && casedata.date_due/1000 <= $('#today_end').val()) {dateclass='date-due';}
                $('#todolist').append("<div class='card mb-2 todoitem' id='casecard_"+casedata.task_id+"'><div class='card-header small p-2' id='caseheader_"+casedata.task_id+"'></div></div>");
                $('#caseheader_'+casedata.task_id).append("<div class='float-right mr-2 border rounded pl-1 pr-1 calendar-div pointer "+dateclass+"'><input type='text' id='todo_date_due_"+casedata.task_id+"' class='datepicker' value='"+thisDateDue+"' /></div>");
                $('#caseheader_'+casedata.task_id).append("<div class='float-left border rounded pl-1 pr-1 mr-2 case-link'>"+casedata.task_id+"</div>");
                $('#caseheader_'+casedata.task_id).append("<div class='float-left border rounded pl-1 pr-1 mr-2 pale-green-link userlink-"+casedata.member_status+"'>"+client+"<a class='fa-userlink' href=''></a></div>");
                $('#caseheader_'+casedata.task_id).append("<div class='float-left col p-0 display-7'>"+casedata.item_summary+"</div>");
                $('#caseheader_'+casedata.task_id).append("<div style='clear: both'></div>");
                */
            })
            
        }
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#todolist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
        pagerNumbers('todo', 0, 0, 0);
    });    
}

function todoend_pager() {
    var start=parseInt($('#todostart').val()) || 0;
    var end=parseInt($('#todoend').val()) || 9;
    var qty=end-start+1;
    //console.log('Quantity: '+qty);
    $('#todostart').val((start+qty));
    $('#todoend').val((end+qty));
    
    loadTodo();
}

function todostart_pager() {
    var start=parseInt($('#todostart').val()) || 0;
    var end=parseInt($('#todoend').val()) || 9;
    var qty=end-start+1;
    $('#todostart').val((start-qty));
    $('#todoend').val((end-qty));
    
    loadTodo();
}