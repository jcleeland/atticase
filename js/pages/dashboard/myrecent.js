/*
 * Copyright [2022] [Jason Alexander Cleeland, Melbourne Australia]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
$(function() {
    loadMyrecent();

    $('#myrecentqty').change(function() {
        loadMyrecent();
    }) 
    
    $('#myrecentFocus').change(function(){loadMyrecent(true);});  
    
    $('#myrecent-inpage_filter').keyup(function(e) {
        var text=$(this).val();
        delay(function() {
            //console.log('Searching '+text+' and using delay');
            searchDivsByText('myrecentlist', text, 1);  
            console.log('Toggline datepickers');  
            toggleDatepickers();
        }, 1500);
    })           
}) 

/**
* Load recent case list (ie: cases that have been modified recently)
* 
* @param reset - set to true if you want to reset the pager and have a new search
*/
function loadMyrecent(reset) {
    var today=new Date();
    var focus=$('#myrecentFocus').val();
    //console.log(focus);
    var joins={};
    joins['tasks']="INNER JOIN tasks t ON t.task_id = h.task_id";
    joins['member_cache']="INNER JOIN member_cache mem ON mem.member=t.member";
    joins['users']="LEFT JOIN users u ON t.assigned_to=u.user_id";
    
    //console.log('Joins:');
    //console.log(joins);
    var parameters={};
    if(focus=="Mine") {
        parameters[':user_id']=globals?.user_id ?? null;
    } else {
        parameters[':user_id']="%";
        //parameters['1']=1;
    }
    //parameters[':isclosed']=1;
    //parameters[':datedue']=today.getTime() / 1000 | 0;
    //console.log(parameters);
    //var select="h.*, t.*, mem.surname, mem.pref_name, u.real_name as assigned_real_name";
    
    if(focus=="Mine") {
        var conditions='h.user_id = :user_id';
    } else {
        var conditions='h.user_id = "%"';
    }
    
    var order='event_date DESC LIMIT 50';
    var status=getStatus();
    if(status.orders != undefined) {
        if(status.orders.recent !== undefined) {
            const orders=status.orders['recent'];
            var counter=0;
            for (const key in orders) {
                if(counter==0) {
                    order='';
                }
                //console.log(order);
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
    //console.log('ORDER: '+order);
    var start=parseInt($('#myrecentstart').val()) || 0;
    var end=parseInt($('#myrecentend').val()) || 9;
    
    if(start<0) {
        start=0;
        $('#myrecentstart').val(1);
    }
    if(end<9) {
        end=9;
        $('#myrecentend').val(10);
    }
    //console.log(joins);
    //console.log('Doing cases, '+start+' to '+end);
    
    
    //MANAGE THE PAGER
    var pagerSettings=pagerNumberSettings('myrecent');
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
        //console.log($('#recentqty').val());
        if(parseInt($('#myrecentqty').val())==0 || $('#myrecentqty').val()=="") {
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
            var qty=parseInt($('#myrecentqty').val());
            var start=parseInt($('#myrecentstart').attr("value"));
            var end=qty+start-1;
            //console.log('Reusing web page settings: Qty-'+qty+', Start-'+start+', End-'+end);
        }
        
    }        
    
    
    
    $('#myrecentlist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>");
    
    //console.log('Searching for recents with a start of '+start+' and an end of '+end);
    $.when(recentList(parameters, conditions, order, start, end)).done(function(cases) {
        //console.log('RECENTS');
        //console.log(cases);
        if(cases.results.length<0) {
            //console.log('Nothing');
            $('#myrecentlist').html("No cases in recent list");
            $('#myrecenttotal').html('of 0 cases');
        } else {
            if(end > cases.total) {
                end=cases.total-1;
            }
            pagerNumbers('myrecent', start, end, cases.total);
            $('#myrecentlist').html('');
            $.each(cases.results, function(i, casedata) {
                if(!$('#caseCardParent_myrecentlist'+casedata.task_id).length) {
                    insertCaseCard('myrecentlist', 'myrecentlist'+casedata.task_id, casedata);
                    $('#caseheadermessage_myrecentlist'+casedata.task_id).show().prepend('<div class="col mt-2 m-0">This case was modified by '+casedata.changedby_real_name+' on '+timestamp2date(casedata.event_date, "dd/mm/yy g:i a")+'</div>');
                }

            })
            
        }
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers();        
    }).fail(function(output) {
        //console.log(output);
        //console.log('Nothing found');
        $('#myrecentlist').html("<center><br />No recently changed cases found<br />&nbsp;</center>");
        pagerNumbers('myrecent', 0, 0, 0);
    });    
}

function myrecentend_pager() {
    var pagerSettings=pagerNumberSettings('myrecent');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start+qty;
    if(start > parseInt($('#myrecentcount').val())) {
        start=start-qty;
    }

    var end=start+qty-1;
    //console.log('RECENTEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#myrecentstart').attr("value",(start));
    $('#myrecentend').attr("value", (end));
    
    
    savePagerSettings('myrecent', start, end, qty);
    loadMyrecent();
}

function myrecentstart_pager() {
    var pagerSettings=pagerNumberSettings('myrecent');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start-qty;
    if(start < 0) {
        start=0;
    }
    var end=start+qty-1;
    
    
    //console.log('RECENTSTART FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#myrecentend').attr("value", (end));
    $('#myrecentstart').attr("value",(start));
    
    
    savePagerSettings('myrecent', start, end, qty);
    loadMyrecent();
}

function myrecentfirst_pager() {
    var pagerSettings=pagerNumberSettings('myrecent');
    var start=0;
    var qty=parseInt(pagerSettings.qty);
    var end=start+qty-1;
    $('#myrecentstart').attr("value",(start));
    $('#myrecentend').attr("value", (end));
    
    
    savePagerSettings('myrecent', start, end, qty);
    loadMyrecent();    
}

function myrecentlast_pager() {
    var pagerSettings=pagerNumberSettings('myrecent');
    var total=parseInt($('#myrecenttotal').text().replace(/\D+/g, ''));
    var qty=parseInt(pagerSettings.qty);
    var end=total;
    var start=total-qty;
    $('#myrecentstart').attr("value",(start));
    $('#myrecentend').attr("value", (end));
    savePagerSettings('myrecent', start, end, qty);
    loadMyrecent();    
}