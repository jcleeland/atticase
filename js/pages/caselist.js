$(function() {
    loadCaselist();
    toggleCaseCards();
    toggleDatepickers();
    
    var status=getStatus();
    //console.log('Status');
    //console.log(status);
    
    var settings=getSettings();
    //console.log('Settings');
    //console.log(settings);
});

function loadCaselist() {
    var today=new Date();
    
    var parameters={};
    var conditions='';
    //parameters[':isclosed']=1;
    
    var conditions='1=1';
    
    var order='date_due ASC';

    // Check for filter settings
    
    if($('#userSelect').val() != '') {
        parameters[':userid']=$('#userSelect').val();
        conditions+=' AND u.user_id = :userid';
    }
    
    if($('#caseTypeSelect').val() != '') {
        //console.log('Case Type: '+$('#caseTypeSelect').val());
        parameters[':casetype']=$('#caseTypeSelect').val();
        conditions+=' AND t.task_type = :casetype';
    }
    
    if($('#departmentSelect').val() != '') {
        parameters[':department']=$('#departmentSelect').val();
        conditions+=' AND t.product_category = :department';
    }
    
    if($('#caseGroupSelect').val() != '') {
        parameters[':casegroup']=$('#caseGroupSelect').val();
        conditions += ' AND t.product_version = :casegroup';
    }
    
    if($('#statusSelect').val() != '') {
        if($('#statusSelect').val()=='1') {
            parameters[':isClosed']=$('#statusSelect').val();
            conditions+=' AND t.is_closed = :isClosed';
        }
        if($('#statusSelect').val()=='0') {
            conditions+=' AND t.is_closed != 1';
        }
    }
 
 
 
    //console.log(conditions);
    
    var qty=9;
    var start=parseInt($('#caseliststart').val()) || 0;
    var end=parseInt($('#caselistend').val()) || qty;
    //console.log('END: '+end);
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

function caselistend_pager() {
    var start=parseInt($('#caseliststart').val()) || 0;
    var end=parseInt($('#caselistend').val()) || 9;
    var qty=end-start+1;
    //console.log('Quantity: '+qty);
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

