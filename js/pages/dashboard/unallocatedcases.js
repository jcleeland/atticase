$(function() {
    loadUnallocatedcases();
    
    $('#unallocatedcasesqty').change(function() {
        loadUnallocatedcases();
    })
    
    $('#unallocatedcases-inpage_filter').keyup(function(e) {
        var text=$(this).val();
        delay(function() {
            //console.log('Searching '+text+' and using delay');
            searchDivsByText('unallocatedcaseslist', text, 1);    
        }, 1500);
    })        
}) 

function loadUnallocatedcases(reset) {
    var today=new Date();
    
    var parameters={};
    parameters[':assignedto']=null;
    parameters[':isclosed']=1;
    //parameters[':datedue']=today.getTime() / 1000 + (86400*2) | 0;
    
    var conditions='assigned_to = :assignedto AND is_closed != :isclosed';
    
    var order='date_due ASC';
    var status=getStatus();
    if(status.orders != undefined) {
        if(status.orders.unallocatedcases !== undefined) {
            const orders=status.orders['unallocatedcases'];
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
    }
    
    var start=parseInt($('#unallocatedcasesstart').val()) || 0;   //Get the text values for start & finish from the page
    var end=parseInt($('#unallocatedcasesend').val()) || 9;       //Get the text values for start & finish from the page
    console.log('Cases start = '+start);
    if(start<0) { //If start is less than zero, bring it up to zero
        start=0;
        $('#unallocatedcasesstart').val(0);
    }
    if(end<9) {  //If the end is less than 9, bring it up to 9
        end=9;
        $('#unallocatedcasesend').val(9);
    }
    
    //console.log('Doing cases, '+start+' to '+end);
    //MANAGE THE PAGER
    var pagerSettings=pagerNumberSettings('unallocatedcases');  //get the cookie values for this pager if they exist
    //console.log('Pager Settings');
    //console.log(pagerSettings);
    if(reset && reset == 1) {
        //IN A NEW SEARCH, RESET THE PAGER VALUES
        //console.log('Resetting pager values');
        var qty=50;
        var start=0;
        var end=49;
    } else {
        //IN AN OLD SEARCH, KEEP THE PAGER VALUES
        //console.log('Reusing old pager values');
        //console.log($('#casesqty').val());
        if(parseInt($('#unallocatedcasesqty').val())==0 || $('#unallocatedcasesqty').val()=="") {
            var qty=parseInt(pagerSettings.qty);
            if(isNaN(qty)) {
                qty=50;
            }       
            var start=pagerSettings.start;
            if(isNaN(start)) {
                start=0;
            }
            var end=pagerSettings.start+qty-1;
            //console.log('Reusing old pager settings: Qty-'+qty+', Start-'+start+', End-'+end);
        } else {
            var qty=parseInt($('#unallocatedcasesqty').val());
            var start=parseInt($('#unallocatedcasesstart').attr("value"));
            var end=qty+start-1;
            //console.log('Reusing web page settings: Qty-'+qty+', Start-'+start+', End-'+end);
        }
        
    }    
    
    
    
    $('#unallocatedcaseslist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>");
    
    
    $.when(caseList(parameters, conditions, order, start, end)).done(function(cases) {
        //console.log('Cases');
        //console.log(cases);
        if(cases.count===0) {
            //console.log('Nothing');
            $('#unallocatedcaseslist').html("<center><br />No cases in the unallocated case list<br />&nbsp;</center>");
            $('#unallocatedcasestotal').html('of 0 cases');
        } else {
            if(end > cases.total) {
                end=cases.total-1;
            }
            pagerNumbers('unallocatedcases', start, end, cases.total);
            $('#unallocatedcaseslist').html('');
            $.each(cases.results, function(i, casedata) {
                /* Put formatting into a standalone script */
                
                insertCaseCard('unallocatedcaseslist', 'unallocatedcaseslist'+casedata.task_id, casedata);

            })
            
        }
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#unallocatedcaseslist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
        pagerNumbers('unallocatedcases', 0, 0, 0);
    });    
}

function unallocatedcasesend_pager() {
    var pagerSettings=pagerNumberSettings('unallocatedcases');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start+qty;
    if(start > parseInt($('#unallocatedcasescount').val())) {
        start=start-qty;
    }

    var end=start+qty-1;
    //console.log('CASESEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#unallocatedcasesstart').attr("value",(start));
    $('#unallocatedcasesend').attr("value", (end));
    
    
    savePagerSettings('unallocatedcases', start, end, qty);
    loadUnallocatedcases();    

}

function unallocatedcasesstart_pager() {
    var pagerSettings=pagerNumberSettings('unallocatedcases');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start-qty;
    if(start < 0) {
        start=0;
    }
    var end=start+qty-1;
    
    
    //console.log('CASELISTEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#unallocatedcasesstart').attr("value",(start));
    $('#unallocatedcasesend').attr("value", (end));
    
    
    savePagerSettings('unallocatedcases', start, end, qty);
    loadUnallocatedcases();
}

function unallocatedcasesfirst_pager() {
    var pagerSettings=pagerNumberSettings('unallocatedcases');
    var start=0;
    var qty=parseInt(pagerSettings.qty);
    var end=start+qty-1;
    $('#unallocatedcasesstart').attr("value",(start));
    $('#unallocatedcasesend').attr("value", (end));
    
    
    savePagerSettings('unallocatedcases', start, end, qty);
    loadUnallocatedcases();    
}

function unallocatedcaseslast_pager() {
    var pagerSettings=pagerNumberSettings('unallocatedcases');
    var total=parseInt($('#unallocatedcasestotal').text().replace(/\D+/g, ''));
    var qty=parseInt(pagerSettings.qty);
    var end=total;
    var start=total-qty;
    $('#unallocatedcasesstart').attr("value",(start));
    $('#unallocatedcasesend').attr("value", (end));
    savePagerSettings('myrecent', start, end, qty);
    loadUnallocatedcases();    
}