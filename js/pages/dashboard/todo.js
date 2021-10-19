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
    
    $('#todoqty').change(function() {
        loadTodo();
    })    
}) 

function loadTodo(reset) {
    var today=new Date();
    
    var parameters={};
    parameters[':assignedto']=globals.user_id;
    parameters[':isclosed']=1;
    parameters[':datedue']=today.getTime() / 1000 | 0;
    
    var conditions='assigned_to = :assignedto AND is_closed != :isclosed AND date_due <= :datedue';
    
    var order='date_due ASC';
    var status=getStatus();
    if(status.orders.todo !== undefined) {
        const orders=status.orders['todo'];
        var counter=0;
        for (const key in orders) {
            if(counter==0) {
                order='';
            }
            //console.log(`${key}: ${orders[key]}`);
            if(counter > 0) {
                order+=', ';
            }
            order+=key+' '+orders[key]+'';
            counter++;
        }
        //console.log('ORDERS:');
        //console.log(orders); 
        //console.log(order);       
    }    
    
    
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
    //MANAGE THE PAGER
    var pagerSettings=pagerNumberSettings('todo');
    //console.log('Pager Settings');
    //console.log(pagerSettings);
    if(reset && reset == 1) {
        //IN A NEW SEARCH, RESET THE PAGER VALUES
        //console.log('Resetting pager values');
        var qty=10;
        var start=1;
        var end=10;
    } else {
        //IN AN OLD SEARCH, KEEP THE PAGER VALUES
        //console.log('Reusing old pager values');
        //console.log($('#todoqty').val());
        if(parseInt($('#todoqty').val())==0 || $('#todoqty').val()=="") {
            var qty=parseInt(pagerSettings.qty);
            if(isNaN(qty)) {
                qty=10;
            }       
            var start=pagerSettings.start;
            if(isNaN(start)) {
                start=0;
            }
            var end=pagerSettings.start+qty-1;
            //console.log('Reusing old pager settings: Qty-'+qty+', Start-'+start+', End-'+end);
        } else {
            var qty=parseInt($('#todoqty').val());
            var start=parseInt($('#todostart').attr("value"));
            var end=qty+start-1;
            //console.log('Reusing web page settings: Qty-'+qty+', Start-'+start+', End-'+end);
        }
        
    }    
    
    
    
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
    var pagerSettings=pagerNumberSettings('todo');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start+qty;
    if(start > parseInt($('#todocount').val())) {
        start=start-qty;
    }

    var end=start+qty-1;
    //console.log('TODOEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#todostart').attr("value",(start));
    $('#todoend').attr("value", (end));
    
    
    savePagerSettings('todo', start, end, qty);
    loadTodo();    

}

function todostart_pager() {
    var pagerSettings=pagerNumberSettings('todo');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start-qty;
    if(start < 1) {
        start=1;
    }
    var end=start+qty-1;
    
    
    //console.log('CASELISTEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#todostart').attr("value",(start));
    $('#todoend').attr("value", (end));
    
    
    savePagerSettings('todo', start, end, qty);
    loadTodo();
}

function todofirst_pager() {
    var pagerSettings=pagerNumberSettings('todo');
    var start=1;
    var qty=parseInt(pagerSettings.qty);
    var end=start+qty-1;
    $('#todostart').attr("value",(start));
    $('#todoend').attr("value", (end));
    
    
    savePagerSettings('todo', start, end, qty);
    loadTodo();    
}

function todolast_pager() {
    var pagerSettings=pagerNumberSettings('todo');
    var qty=parseInt(pagerSettings.qty);
    var pages=parseInt($('#todocount').val())/qty;
    pages=parseInt(pages);
    var start=pages*qty;
    var end=start+qty-1;
    $('#todostart').attr("value",(start));
    $('#todoend').attr("value", (end));
    
    
    savePagerSettings('todo', start, end, qty);
    loadTodo();    
}