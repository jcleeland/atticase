$(function() {
    loadCase();
    

});

function loadCase() {
    var today=new Date();
    
    var caseId=$('#caseid').val();
    
    $.when(getCase(caseId)).done(function(caseDetails) {
        //console.log('Cases');
        casedata=caseDetails.results;
        //console.log(casedata);
        clearCaseForm();
        
        var thisDateDue=timestamp2date(casedata.date_due);
        var dateOpened=timestamp2date(casedata.date_opened);
        if(typeof casedata.pref_name !== 'undefined') {
            var client=casedata.pref_name+' '+casedata.surname;
        } else {
            var client=casedata.member;
        }
        var dateclass='date-future';
        var lasteditedby=(casedata.last_edited_real_name) ? casedata.last_edited_real_name : "Unknown";
        var assignedto=(casedata.assigned_real_name) ? casedata.assigned_real_name : 'Unassigned';
        if(casedata.date_due < $('#today_start').val()) {dateclass='date-overdue';}
        if(casedata.date_due >= $('#today_start').val() && casedata.date_due <= $('#today_end').val()) {dateclass='date-due';}

        //console.log(casedata.results[0]);
        
        $('#caseid_header').html(casedata.task_id);
        //toggleCaseCards();
        $('#clientname').html(casedata.clientname+"<a class='fa-userlink' href='"+casedata.member+"'></a>");
        $('#itemsummary').html(casedata.item_summary);
        //console.log(thisDateDue);
        
        $('#date_due').val(thisDateDue);
        $('#date_due_parent').addClass(dateclass);
        
        $('#assignedto_cover').html(casedata.assigned_real_name);
        $("#casetype_cover").html(casedata.tasktype_name);
        if(casedata.line_manager) {
            $("#linemanager_cover").html(casedata.line_manager+" ("+casedata.line_manager_ph+") <a class='fa-phone' href='tel:"+casedata.line_manager_ph+"'></a><a class='fa-chat' href='sms:"+casedata.line_manager_ph+"'></a>");
        }
        $('#casegroup_cover').html(casedata.version_name);
        $("#department_cover").html(casedata.category_name);
        $("#unit_cover").html(casedata.unit);
        if(casedata.local_delegate) {
            $("#delegate_cover").html(casedata.local_delegate+" ("+casedata.local_delegate_ph+") <a class='fa-phone' href='tel:'"+casedata.local_delegate_ph+"'></a><a class='fa-chat' href='sms:"+casedata.local_delegate_ph+"'></a>");
        }
        
        $("#detaileddesc_cover").html(deWordify(casedata.detailed_desc));
        $("#resolution_cover").html(deWordify(casedata.resolution_sought));
        
        $("#dateopened_cover").html(dateOpened);
        $("#openedby_cover").html(casedata.openedby_real_name);

            
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers();        
    }).fail(function() {
        console.log('Nothing found');
        $('#todolist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
        pagerNumbers('todo', 0, 0, 0);        
    });    
}

function clearCaseForm() {
    $('#caseid_header').html("");
    $('#assignedto').html("<a class='fa-userlink' href=''></a>");
    $('#itemsummary').html("Loading case details...");
    $('#date_due').val("");
    
    $('#assignedto_cover').html("");
    $("#casetype_cover").html("");
    $("#linemanager_cover").html("");
    $('#casegroup_cover').html("");
    $("#department_cover").html("");
    $("#delegate_cover").html("");
    
    $("#detaileddesc_cover").html("");
    $("#resolution_cover").html("");
    
    $("#dateopened_cover").html("");
    $("#openedby_cover").html("");    
}

