$(function() {
    loadHistory();
    
    $('#filterTimes').keyup(function() {
        //console.log($(this).val());
        var search=$(this).val();
        $('.historyitem').each(function() {
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
}) 

function loadHistory() {
    var today=new Date();
    var caseId=$('#caseid').val();
    var parameters={};
    parameters[':taskid']=caseId;
    
    var conditions='h.task_id = :taskid'
    
    var order='h.event_date DESC';
    
    var start=parseInt($('#timessstart').val()) || 0;
    var end=parseInt($('#timessend').val()) || 90000000;
    
    $('#historylist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Loading history list...</center>");
    
    $.when(historyList(parameters, conditions, order, start, end)).done(function(historys) {
        console.log('History');
        console.log(historys);
        if(historys.count<1) {
            $('#historylist').html("<center><br />No history for this case yet<br />&nbsp;</center>");
            $('#historycount').html('');
        } else {
            $('#historylist').html('');
            $('#historycount').html(historys.total);
            $.each(historys.results, function(i, historydata) {
                console.log(historydata);  
                var parentDiv='historylist';
                var uniqueId='history'+historydata.history_id;
                var primeBox=historydata.real_name;
                var briefPrimeBox=getInitials(historydata.real_name);
                var dateBox=timestamp2date(historydata.event_date, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(historydata.event_date, 'dd MM YY');
                var actionPermissions=null;
                
                switch(historydata.event_type) {
                    case "0":
                        var content='<b>'+historydata.event_description+'</b>: ['+historydata.field_changed+'] "'+historydata.old_value+'" to "'+historydata.new_value+'"';
                        break;
                    case "2":
                        var content='<b>'+historydata.event_description+'</b>: '+historydata.old_value;
                        break;
                    case "4":
                        var content='<b>'+historydata.event_description+'</b>: '+$('#cardbody_comment'+historydata.new_value).text();
                        break;
                    case "7":
                        var content='<b>'+historydata.event_description+'</b>: '+$('#cardbody_'+historydata.new_value).text();
                        break;
                    default:
                        var content=historydata.event_description;
                        break;
                }
                if(historydata.event_type=="0") {
                    
                }
                var header=null;
                
                
                insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content);
                //console.log('^^^');
            })
            
        }
    }).then(function() {
        //toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#strategyslist').html("<center><img src='images/logo.png' width='50px' /><br />No planning comments for this case yet</center>");
        //pagerNumbers('todo', 0, 0, 0);
    });    
}


