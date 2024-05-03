$(function() {
    loadEnquirycases();
    
    $('#enquirycasesqty').change(function() {
        loadEnquirycases();
    })
    
    $('#enquirycases-inpage_filter').keyup(function(e) {
        var text=$(this).val();
        delay(function() {
            //console.log('Searching '+text+' and using delay');
            searchDivsByText('enquirycaseslist', text, 1);    
        }, 1500);
    })        
}) 

function loadEnquirycases(reset) {
    var onemonthago=new Date();
    onemonthago.setMonth(onemonthago.getMonth() -2);
    console.log(onemonthago);
    onemonthago=Math.floor(onemonthago.getTime() / 1000);
    var parameters={};
    parameters[':is_enquiry']=1;
    parameters[':opened']=onemonthago;
    //parameters[':isclosed']=1; //we reverse this in the conditions, to find cases where is_closed is NOT equal to 1 (ie: open)
    //parameters[':datedue']=today.getTime() / 1000 + (86400*2) | 0;
    
    var conditions='lv.is_enquiry = :is_enquiry';
    conditions += ' AND t.date_opened >= :opened';

    //conditions+='AND is_closed != :isclosed';
    
    var order='date_due DESC';
    var status=getStatus();
    if(status.orders != undefined) {
        if(status.orders.enquirycases !== undefined) {
            const orders=status.orders['enquirycases'];
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
    
    var start=parseInt($('#enquirycasesstart').val()) || 0;   //Get the text values for start & finish from the page
    var end=parseInt($('#enquirycasesend').val()) || 9;       //Get the text values for start & finish from the page
    //console.log('Cases start = '+start);
    if(start<0) { //If start is less than zero, bring it up to zero
        start=0;
        $('#enquirycasesstart').val(0);
    }
    if(end<9) {  //If the end is less than 9, bring it up to 9
        end=9;
        $('#enquirycasesend').val(9);
    }
    
    //console.log('Doing cases, '+start+' to '+end);
    //MANAGE THE PAGER
    var pagerSettings=pagerNumberSettings('enquirycases');  //get the cookie values for this pager if they exist
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
        if(parseInt($('#enquirycasesqty').val())==0 || $('#enquirycasesqty').val()=="") {
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
            var qty=parseInt($('#enquirycasesqty').val());
            var start=parseInt($('#enquirycasesstart').attr("value"));
            var end=qty+start-1;
            //console.log('Reusing web page settings: Qty-'+qty+', Start-'+start+', End-'+end);
        }
        
    }    
    
    
    
    $('#enquirycaseslist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>");
    
    
    $.when(caseList(parameters, conditions, order, start, end)).done(function(cases) {
        //console.log('Cases');
        //console.log(cases);
        if(cases.count===0) {
            //console.log('Nothing');
            $('#enquirycaseslist').html("<center><br />No cases in the enquiry case list<br />&nbsp;</center>");
            $('#enquirycasestotal').html('of 0 cases');
        } else {
            if(end > cases.total) {
                end=cases.total-1;
            }
            pagerNumbers('enquirycases', start, end, cases.total);
            $('#enquirycaseslist').html('');
            $.each(cases.results, function(i, casedata) {
                /* Put formatting into a standalone script */
                
                insertCaseCard('enquirycaseslist', 'enquirycaseslist'+casedata.task_id, casedata);
                $('#caseheadermessage_enquirycaseslist'+casedata.task_id).show().prepend('<div class="col mt-2 m-0 text-center font-weight-bold">'+casedata['version_name']+'</div>');

            })
            
        }
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#enquirycaseslist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
        pagerNumbers('enquirycases', 0, 0, 0);
    });    
}

function enquirycasesend_pager() {
    var pagerSettings=pagerNumberSettings('enquirycases');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start+qty;
    if(start > parseInt($('#enquirycasescount').val())) {
        start=start-qty;
    }

    var end=start+qty-1;
    //console.log('CASESEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#enquirycasesstart').attr("value",(start));
    $('#enquirycasesend').attr("value", (end));
    
    
    savePagerSettings('enquirycases', start, end, qty);
    loadEnquirycases();    

}

function enquirycasesstart_pager() {
    var pagerSettings=pagerNumberSettings('enquirycases');
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
    $('#enquirycasesstart').attr("value",(start));
    $('#enquirycasesend').attr("value", (end));
    
    
    savePagerSettings('enquirycases', start, end, qty);
    loadEnquirycases();
}

function enquirycasesfirst_pager() {
    var pagerSettings=pagerNumberSettings('enquirycases');
    var start=0;
    var qty=parseInt(pagerSettings.qty);
    var end=start+qty-1;
    $('#enquirycasesstart').attr("value",(start));
    $('#enquirycasesend').attr("value", (end));
    
    
    savePagerSettings('enquirycases', start, end, qty);
    loadEnquirycases();    
}

function enquirycaseslast_pager() {
    var pagerSettings=pagerNumberSettings('enquirycases');
    var total=parseInt($('#enquirycasestotal').text().replace(/\D+/g, ''));
    var qty=parseInt(pagerSettings.qty);
    var end=total;
    var start=total-qty;
    $('#enquirycasesstart').attr("value",(start));
    $('#enquirycasesend').attr("value", (end));
    savePagerSettings('enquiry', start, end, qty);
    loadEnquirycases();    
}