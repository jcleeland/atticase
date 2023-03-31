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
    loadMytodo();
    
    $('#mytodoqty').change(function() {
        loadMytodo();
    })
    
    $('#mytodo-inpage_filter').keyup(function(e) {
        var text=$(this).val();
        delay(function() {
            //console.log('Searching '+text+' and using delay');
            searchDivsByText('mytodolist', text, 1);    
        }, 1500);
    })        
}) 

function loadMytodo(reset) {
    var today=new Date();
    
    var parameters={};
    parameters[':assignedto']=globals.user_id;
    parameters[':isclosed']=1;
    parameters[':datedue']=today.getTime() / 1000 + (86400*2) | 0;
    
    var conditions='assigned_to = :assignedto AND is_closed != :isclosed AND date_due <= :datedue';
    
    var order='date_due ASC';
    var status=getStatus();
    if(status.orders != undefined) {
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
    }
    
    var start=parseInt($('#mytodostart').val()) || 0;
    var end=parseInt($('#mytodoend').val()) || 9;
    //console.log('Todo start = '+start);
    if(start<0) {
        start=0;
        $('#mytodostart').val(0);
    }
    if(end<9) {
        end=9;
        $('#mytodoend').val(9);
    }
    
    //console.log('Doing cases, '+start+' to '+end);
    //MANAGE THE PAGER
    var pagerSettings=pagerNumberSettings('mytodo');
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
        //console.log($('#todoqty').val());
        if(parseInt($('#mytodoqty').val())==0 || $('#mytodoqty').val()=="") {
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
            var qty=parseInt($('#mytodoqty').val());
            var start=parseInt($('#mytodostart').attr("value"));
            var end=qty+start-1;
            //console.log('Reusing web page settings: Qty-'+qty+', Start-'+start+', End-'+end);
        }
        
    }    
    
    
    
    $('#mytodolist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>");
    
    
    $.when(caseList(parameters, conditions, order, start, end)).done(function(cases) {
        //console.log('Cases');
        //console.log(cases);
        if(cases.count===0) {
            //console.log('Nothing');
            $('#mytodolist').html("<center><br />No cases in todo list<br />&nbsp;</center>");
            $('#mytodolisttotal').html('of 0 cases');
        } else {
            if(end > cases.total) {
                end=cases.total-1;
            }
            pagerNumbers('mytodo', start, end, cases.total);
            $('#mytodolist').html('');
            $.each(cases.results, function(i, casedata) {
                /* Put formatting into a standalone script */
                
                insertCaseCard('mytodolist', 'mytodolist'+casedata.task_id, casedata);

            })
            
        }
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#mytodolist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
        pagerNumbers('mytodo', 0, 0, 0);
    });    
}

function mytodoend_pager() {
    var pagerSettings=pagerNumberSettings('mytodo');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start+qty;
    if(start > parseInt($('#mytodocount').val())) {
        start=start-qty;
    }

    var end=start+qty-1;
    //console.log('TODOEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#mytodostart').attr("value",(start));
    $('#mytodoend').attr("value", (end));
    
    
    savePagerSettings('mytodo', start, end, qty);
    loadMytodo();    

}

function mytodostart_pager() {
    var pagerSettings=pagerNumberSettings('mytodo');
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
    $('#mytodostart').attr("value",(start));
    $('#mytodoend').attr("value", (end));
    
    
    savePagerSettings('mytodo', start, end, qty);
    loadMytodo();
}

function mytodofirst_pager() {
    var pagerSettings=pagerNumberSettings('mytodo');
    var start=0;
    var qty=parseInt(pagerSettings.qty);
    var end=start+qty-1;
    $('#mytodostart').attr("value",(start));
    $('#mytodoend').attr("value", (end));
    
    
    savePagerSettings('mytodo', start, end, qty);
    loadMytodo();    
}

function mytodolast_pager() {
    var pagerSettings=pagerNumberSettings('mytodo');
    var total=parseInt($('#mytodototal').text().replace(/\D+/g, ''));
    var qty=parseInt(pagerSettings.qty);
    var end=total;
    var start=total-qty;
    $('#mytodostart').attr("value",(start));
    $('#mytodoend').attr("value", (end));
    savePagerSettings('mytodo', start, end, qty);    
    savePagerSettings('mytodo', start, end, qty);
    loadMytodo();    
}