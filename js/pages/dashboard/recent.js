$(function() {
    loadRecent();
    
    $('#filterRecent').keyup(function() {
        //console.log($(this).val());
        var search=$(this).val();
        $('.recentitem').each(function() {
            //console.log($(this));
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
    
    $('#recentqty').change(function() {
        loadRecent();
    })       
}) 

function loadRecent(reset) {
    var today=new Date();
    
    var joins={};
    joins['tasks']="INNER JOIN tasks t ON t.task_id = h.task_id";
    joins['member_cache']="INNER JOIN member_cache mem ON mem.member=t.member";
    joins['users']="LEFT JOIN users u ON t.assigned_to=u.user_id"
    
    var parameters={};
    parameters[':user_id']=globals.user_id;
    //parameters[':isclosed']=1;
    //parameters[':datedue']=today.getTime() / 1000 | 0;
    
    //var select="h.*, t.*, mem.surname, mem.pref_name, u.real_name as assigned_real_name";
    
    var conditions='h.user_id = :user_id';
    
    var order='event_date DESC LIMIT 100';
    var status=getStatus();
    if(status.orders != undefined) {
        if(status.orders.recent !== undefined) {
            const orders=status.orders['recent'];
            var counter=0;
            for (const key in orders) {
                if(counter==0) {
                    order='';
                }
                console.log(order);
                //console.log(`${key}: ${orders[key]}`);
                if(counter > 0) {
                    order+=', ';
                }
                order+=key+' '+orders[key]+'';
                counter++;
            }
            if(counter > 0) {
                order+=' LIMIT 100';
            }
            //console.log('ORDERS:');
            //console.log(orders); 
            //console.log(order);       
        }    
    }
    console.log('ORDER: '+order);
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
    //console.log(joins);
    //console.log('Doing cases, '+start+' to '+end);
    
    
    //MANAGE THE PAGER
    var pagerSettings=pagerNumberSettings('recent');
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
        //console.log($('#recentqty').val());
        if(parseInt($('#recentqty').val())==0 || $('#recentqty').val()=="") {
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
            var qty=parseInt($('#recentqty').val());
            var start=parseInt($('#recentstart').attr("value"));
            var end=qty+start-1;
            //console.log('Reusing web page settings: Qty-'+qty+', Start-'+start+', End-'+end);
        }
        
    }        
    
    
    
    $('#recentlist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>");
    
    
    $.when(recentList(parameters, conditions, order, start, end)).done(function(cases) {
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
                    $('#caseheader_recentlist'+casedata.task_id).prepend('<div class="text-muted green-curtain p-0 pl-1 pr-1 m-0 mb-1 smaller" >You modified this case on '+timestamp2date(casedata.event_date, "dd/mm/yy g:i a")+'</div>');
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
        //console.log(output);
        //console.log('Nothing found');
        $('#recentlist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
        pagerNumbers('recent', 0, 0, 0);
    });    
}

function recentend_pager() {
    var pagerSettings=pagerNumberSettings('recent');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start+qty;
    if(start > parseInt($('#recentcount').val())) {
        start=start-qty;
    }

    var end=start+qty-1;
    //console.log('RECENTEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#recentstart').attr("value",(start));
    $('#recentend').attr("value", (end));
    
    
    savePagerSettings('recent', start, end, qty);
    loadRecent();
}

function recentstart_pager() {
    var pagerSettings=pagerNumberSettings('recent');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start-qty;
    if(start < 1) {
        start=1;
    }
    var end=start+qty-1;
    
    
    //console.log('RECENTSTART FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#recentend').attr("value", (end));
    $('#recentstart').attr("value",(start));
    
    
    savePagerSettings('recent', start, end, qty);
    loadRecent();
}

function recentfirst_pager() {
    var pagerSettings=pagerNumberSettings('recent');
    var start=1;
    var qty=parseInt(pagerSettings.qty);
    var end=start+qty-1;
    $('#recentstart').attr("value",(start));
    $('#recentend').attr("value", (end));
    
    
    savePagerSettings('recent', start, end, qty);
    loadRecent();    
}

function recentlast_pager() {
    var pagerSettings=pagerNumberSettings('recent');
    var qty=parseInt(pagerSettings.qty);
    var pages=parseInt($('#recentcount').val())/qty;
    pages=parseInt(pages);
    var start=pages*qty;
    var end=start+qty-1;
    $('#recentstart').attr("value",(start));
    $('#recentend').attr("value", (end));
    
    
    savePagerSettings('recent', start, end, qty);
    loadRecent();    
}