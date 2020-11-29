$(function() {
    loadCaselist();
    toggleCaseCards();
    toggleDatepickers();
    

});

function loadCaselist() {
    var today=new Date();
    
    var parameters={};
    parameters[':isclosed']=1;
    
    var conditions='is_closed != :isclosed';
    
    var order='date_due ASC';

    if($('#mycasesOnly').is(":checked")) {
        parameters[':userid']=$('#user_id').val();
        conditions+=' AND u.user_id = :userid';
    }

    
    var qty=9;
    var start=parseInt($('#caseliststart').val()) || 0;
    var end=parseInt($('#caselistend').val()) || qty;
    
    $('#caselist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>");
    
    $.when(caseList(parameters, conditions, order, start, end)).done(function(cases) {
        //console.log('Cases');
        //console.log(cases);
        if(cases.count===0) {
            //console.log('Nothing');
            $('#caselist').html("<center><br />No cases in list<br />&nbsp;</center>");
        } else {
            pagerNumbers('caselist', start, end, cases.total);
            $('#caselist').html('');
            $.each(cases.results, function(i, casedata) {
                
                insertCaseCard('caselist', 'caselist'+casedata.task_id, casedata);
                
                /* Put formatting into a standalone script */
                /* var thisDateDue=timestamp2date(casedata.date_due);
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
                //console.log('Date Due: '+casedata.date_due+' - today_start: '+$('#today_start').val());
                
                
                //NEW
                $('#caselist').append('<div class="row m-1" id="caseCardParent_case'+casedata.task_id+'"></div>');
                
                $('#caseCardParent_case'+casedata.task_id).append('<div class="float-left p-0 col m-0" style="min-width: 56px; max-width: 65px; height: 100%" id="leftCaseCol_case'+casedata.task_id+'"></div>');
                $('#caseCardParent_case'+casedata.task_id).append('<div class="float-left col p-0 " style="border-top: 1px solid #6ab446" id="rightCaseCol_case'+casedata.task_id+'"></div>');
                
                $('#leftCaseCol_case'+casedata.task_id).append('<div id="casePrimeBox_case'+casedata.task_id+'" class="casePrimeBox text-center case-link"></div>');
                $('#rightCaseCol_case'+casedata.task_id).append('<div id="caseMain_case'+casedata.task_id+'" class="card-body p-0"></div>');
                
                $('#rightCaseCol_case'+casedata.task_id).append('<div class="card-header small p-2 ml-1" id="caseheader_'+casedata.task_id+'"></div>');
                $('#rightCaseCol_case'+casedata.task_id).append('<div class="card-body collapse p-1" id="casedetails_'+casedata.task_id+'"></div>');
                //$('#rightCaseCol_case'+casedata.task_id).append('<div class="card-footer small font-italic pt-1 pb-1 text-muted" id="casefooter_'+casedata.task_id+'"></div>');
                
                $('#casePrimeBox_case'+casedata.task_id).append(casedata.task_id);
                
                //Right Col
                $('#caseheader_'+casedata.task_id).append("<div class='float-right mr-2 border rounded pl-1 pr-1 calendar-div pointer "+dateclass+"'><input type='text' id='caselist_date_due_"+casedata.task_id+"' class='datepicker' value='"+thisDateDue+"' /></div>");
                $('#caseheader_'+casedata.task_id).append("<div class='d-md-block d-lg-block d-xl-block d-none d-sm-none d-xs-none officer float-right m-0 mb-1 mr-1 border rounded pl-1 pr-1' id='officer_"+casedata.assigned_to+"'>"+assignedto+"</div>");
                                
                $('#caseheader_'+casedata.task_id).append("<div class='float-left border rounded pl-1 pr-1 mr-2 client-link userlink-"+casedata.member_status+"'>"+client+"<a class='fa-userlink' href=''></a></div>");
                $('#caseheader_'+casedata.task_id).append("<div class='float-left p-0 display-7'><a data-toggle='collapse' href='#case-card' aria-expanded='true' aria-controls='case-card' id='toggle-case-card_"+casedata.task_id+"' onClick='toggleDetails(\""+casedata.task_id+"\")' ><img id='toggledetails_"+casedata.task_id+"' src='images/caret-bottom.svg' class='img-thumbnail float-left mr-2 mt-1 toggledetails' width='20px' title='Show case details' /></a><span  onClick='toggleDetails(\""+casedata.task_id+"\")'>"+casedata.item_summary+"</span></div>");
                $('#caseheader_'+casedata.task_id).append("<div style='clear: both'></div>");
                //Case description
                $('#casedetails_'+casedata.task_id).append("<p class='card-text small overflow-auto' style='max-height: 100px'>"+deWordify(casedata.detailed_desc)+"</p>");
                $('#casedetails_'+casedata.task_id).append("<div class='d-xs-block d-sm-block d-md-none d-lg-none d-xl-none officer float-right m-0 mb-1 border rounded pl-1 pr-1 small' id='officer_"+casedata.assigned_to+"'>"+assignedto+"</div>");
                */
            })
            
        }
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers();        
    }).fail(function() {
        console.log('Nothing found');
        $('#todolist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
        pagerNumbers('todo', 0, 0, 0);        
    });    
}

function caselistend_pager() {
    var start=parseInt($('#caseliststart').val()) || 0;
    var end=parseInt($('#caselistend').val()) || 9;
    var qty=end-start+1;
    console.log('Quantity: '+qty);
    $('#caseliststart').val((start+qty));
    $('#caselistend').val((end+qty));
    
    loadCaselist();
}

function caseliststart_pager() {
    var start=parseInt($('#caseliststart').val()) || 0;
    var end=parseInt($('#caselistend').val()) || 9;
    var qty=end-start+1;
    $('#caseliststart').val((start-qty));
    $('#caselistend').val((end-qty));
    
    loadCaselist();
}

