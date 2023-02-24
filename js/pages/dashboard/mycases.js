$(function() {
    loadMycases();
    
    $('#mycasesqty').change(function() {
        loadMycases();
    })
    
    $('#mycases-inpage_filter').keyup(function(e) {
        var text=$(this).val();
        delay(function() {
            //console.log('Searching '+text+' and using delay');
            searchDivsByText('mycaseslist', text, 1);    
        }, 1500);
    })        
}) 

function loadMycases(reset) {
    var today=new Date();
    
    var parameters={};
    parameters[':assignedto']=globals.user_id;
    parameters[':isclosed']=1;
    //parameters[':datedue']=today.getTime() / 1000 + (86400*2) | 0;
    
    var conditions='assigned_to = :assignedto AND is_closed != :isclosed';
    
    var order='date_due ASC';
    var status=getStatus();
    if(status.orders != undefined) {
        if(status.orders.mycases !== undefined) {
            const orders=status.orders['mycases'];
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
    
    var start=parseInt($('#mycasesstart').val()) || 0;
    var end=parseInt($('#mycasesend').val()) || 9;
    //console.log('Cases start = '+start);
    if(start<0) {
        start=0;
        $('#mycasesstart').val(0);
    }
    if(end<9) {
        end=9;
        $('#mycasesend').val(9);
    }
    
    //console.log('Doing cases, '+start+' to '+end);
    //MANAGE THE PAGER
    var pagerSettings=pagerNumberSettings('mycases');
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
        //console.log($('#casesqty').val());
        if(parseInt($('#mycasesqty').val())==0 || $('#mycasesqty').val()=="") {
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
            var qty=parseInt($('#mycasesqty').val());
            var start=parseInt($('#mycasesstart').attr("value"));
            var end=qty+start-1;
            //console.log('Reusing web page settings: Qty-'+qty+', Start-'+start+', End-'+end);
        }
        
    }    
    
    
    
    $('#mycaseslist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>");
    
    
    $.when(caseList(parameters, conditions, order, start, end)).done(function(cases) {
        //console.log('Cases');
        //console.log(cases);
        if(cases.count===0) {
            //console.log('Nothing');
            $('#mycaseslist').html("<center><br />No cases in you case list<br />&nbsp;</center>");
        } else {
            pagerNumbers('mycases', start, end, cases.total);
            $('#mycaseslist').html('');
            $.each(cases.results, function(i, casedata) {
                /* Put formatting into a standalone script */
                
                insertCaseCard('mycaseslist', 'mycaseslist'+casedata.task_id, casedata);

            })
            
        }
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#mycaseslist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
        pagerNumbers('mycases', 0, 0, 0);
    });    
}

function mycasesend_pager() {
    var pagerSettings=pagerNumberSettings('mycases');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start+qty;
    if(start > parseInt($('#mycasescount').val())) {
        start=start-qty;
    }

    var end=start+qty-1;
    //console.log('CASESEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#mycasesstart').attr("value",(start));
    $('#mycasesend').attr("value", (end));
    
    
    savePagerSettings('mycases', start, end, qty);
    loadMycases();    

}

function mycasesstart_pager() {
    var pagerSettings=pagerNumberSettings('mycases');
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
    $('#mycasesstart').attr("value",(start));
    $('#mycasesend').attr("value", (end));
    
    
    savePagerSettings('mycases', start, end, qty);
    loadMycases();
}

function mycasesfirst_pager() {
    var pagerSettings=pagerNumberSettings('mycases');
    var start=1;
    var qty=parseInt(pagerSettings.qty);
    var end=start+qty-1;
    $('#mycasesstart').attr("value",(start));
    $('#mycasesend').attr("value", (end));
    
    
    savePagerSettings('mycases', start, end, qty);
    loadMycases();    
}

function mycaseslast_pager() {
    var pagerSettings=pagerNumberSettings('mycases');
    var qty=parseInt(pagerSettings.qty);
    var pages=parseInt($('#mycasescount').val())/qty;
    pages=parseInt(pages);
    var start=pages*qty;
    var end=start+qty-1;
    $('#mycasesstart').attr("value",(start));
    $('#mycasesend').attr("value", (end));
    
    
    savePagerSettings('mycases', start, end, qty);
    loadMycases();    
}