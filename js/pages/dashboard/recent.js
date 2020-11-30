$(function() {
    loadRecent();
    
    $('#filterRecent').keyup(function() {
        console.log($(this).val());
        var search=$(this).val();
        $('.recentitem').each(function() {
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
}) 

function loadRecent() {
    var today=new Date();
    
    var joins={};
    joins['tasks']="INNER JOIN tasks t ON t.task_id = h.task_id";
    joins['member_cache']="INNER JOIN member_cache mem ON mem.member=t.member";
    joins['users']="LEFT JOIN users u ON t.assigned_to=u.user_id"
    
    var parameters={};
    parameters[':user_id']=$('#user_id').val();
    //parameters[':isclosed']=1;
    //parameters[':datedue']=today.getTime() / 1000 | 0;
    
    var select="h.*, t.*, mem.*, u.real_name as assigned_real_name";
    
    var conditions='h.user_id = :user_id';
    
    var order='event_date DESC LIMIT 500';
    
    var start=parseInt($('#recentstart').val()) || 0;
    var end=parseInt($('#recentend').val()) || 9;
    
    if(start<0) {
        start=0;
        $('#recentstart').val(0);
    }
    if(end<9) {
        end=9;
        $('#recentend').val(9);
    }
    console.log(joins);
    console.log('Doing cases, '+start+' to '+end);
    
    $('#recentlist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>");
    
    
    $.when(tableList("history h", joins, select, parameters, conditions, order, start, end)).done(function(cases) {
        //console.log('RECENTS');
        //console.log(cases);
        if(cases.results.length<0) {
            //console.log('Nothing');
            $('#recentlist').html("No cases in recent list");
        } else {
            pagerNumbers('recent', start, end, cases.total);
            $('#recentlist').html('');
            $.each(cases.results, function(i, casedata) {
                /* Put formatting into a standalone script */
                if(!$('#caseCardParent_recentlist'+casedata.task_id).length) {
                    insertCaseCard('recentlist', 'recentlist'+casedata.task_id, casedata);
                }
                //console.log(casedata);
                /* var thisDateDue=timestamp2date(casedata.event_date);

                if(!$('#recentcasecard_'+casedata.task_id).length) {
                    $('#recentlist').append("<div class='card mb-2 recentitem' id='recentcasecard_"+casedata.task_id+"'><div class='card-header small p-2' id='recentcaseheader_"+casedata.task_id+"'></div></div>");
                    $('#recentcaseheader_'+casedata.task_id).append("<div class='float-right mr-2 border rounded pl-1 pr-1 calendar-div pointer'><input type='text' id='recent_date_due_"+casedata.task_id+"' class='datepicker' value='"+thisDateDue+"' /></div>");
                    $('#recentcaseheader_'+casedata.task_id).append("<div class='float-left border rounded pl-1 pr-1 mr-2 case-link'>"+casedata.task_id+"</div>");
                    $('#recentcaseheader_'+casedata.task_id).append("<div class='float-left border rounded pl-1 pr-1 mr-2 client-link'>"+casedata.clientname+"</div>");
                    if(casedata.is_closed==1) {
                        $('#recentcaseheader_'+casedata.task_id).append("<div class='border rounded float-right text-muted small p-1 closed-case' style='bottom: -25px !important; right: -95px !important;'>Date closed: "+timestamp2date(casedata.date_closed, 'dd/mm/yy g:i a')+"</div>");
                    }
                    $('#recentcaseheader_'+casedata.task_id).append("<div class='float-left col p-0 display-7'>"+casedata.item_summary+"</div>");
                    $('#recentcaseheader_'+casedata.task_id).append("<div style='clear: both'></div>");
                } */
            })
            
        }
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers();        
    }).fail(function(output) {
        console.log(output);
        console.log('Nothing found');
        $('#recentlist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
        pagerNumbers('recent', 0, 0, 0);
    });    
}

function recentend_pager() {
    var start=parseInt($('#recentstart').val()) || 0;
    var end=parseInt($('#recentend').val()) || 9;
    var qty=end-start+1;
    console.log('Quantity: '+qty);
    $('#recentstart').val((start+qty));
    $('#recentend').val((end+qty));
    
    loadRecent();
}

function recentstart_pager() {
    var start=parseInt($('#recentstart').val()) || 0;
    var end=parseInt($('#recentend').val()) || 9;
    var qty=end-start+1;
    $('#recentstart').val((start-qty));
    $('#recentend').val((end-qty));
    
    loadRecent();
}