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
        //console.log('History');
        //console.log(historys);
        if(historys.count<1) {
            $('#historylist').html("<center><br />No history for this case yet<br />&nbsp;</center>");
            $('#historycount').html('');
        } else {
            $('#historylist').html('');
            $('#historycount').html(historys.total);
            $.each(historys.results, function(i, historydata) {
                //console.log(historydata);  
                var parentDiv='historylist';
                var uniqueId='history'+historydata.history_id;
                var primeBox=historydata.real_name;
                var briefPrimeBox=getInitials(historydata.real_name);
                var dateBox=timestamp2date(historydata.event_date, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(historydata.event_date, 'dd MM YY');
                var actionPermissions=null;
                if(globals.is_admin=='1') {
                    actionPermissions=['delete'];    
                }
                
                
                var header='<b>'+historydata.event_description+'</b>';
                var content='';
                switch(historydata.event_type) {
                    case "0":
                        //Base field change
                        content+='['+historydata.field_changed+'] "'+historydata.old_value+'" to "'+historydata.new_value+'"';
                        break;
                    case "1":
                        //Case opened
                        content+=historydata.new_value;
                    case "2":
                        //Case closed
                        content+='<b>Case closed by '+userNames[historydata.closed_by]+', '+timestamp2date(historydata.date_closed, "dd/mm/yy")+"</b><br />";
                        content+=historydata.old_value;
                        break;
                    case "4":
                        //Note added
                        content+=$('#cardbody_comment'+historydata.new_value).html();
                        break;
                    case "5":
                        //Note deleted
                        content+=historydata.old_value;
                        break;
                    case "6":
                        break;
                    case "13":
                        //Reopened case
                        content+='<b>Case reopened by user '+historydata.real_name+', '+timestamp2date(historydata.event_date, "dd/mm/yy g:i a")+"</b>";
                        break;
                    case "71":
                        //Note changed
                        content+='<b>To:</b> '+historydata.new_value+'<br /><b>From: </b><span class="text-footnote">'+historydata.old_value+'</span>';
                        break;
                    case "8":
                        //Attachment deleted
                        if($('#cardbody_attachment'+historydata.old_value).length) {
                            content+='Deleted file: '+$('#cardbody_attachment'+historydata.old_value).text();
                        } else {
                            content+='Deleted';
                        }
                        break; 
                    case "81":
                        //Attachment modified
                        content+='<b>To:</b> '+$('#cardbody_attachment'+historydata.new_value).text()+'<br /><b>From: </b><span class="text-footnote">'+$('#cardbody_attachment'+historydata.old_value).text()+'</span>';
                        break;
                    case "7":
                        //Attachment added
                        if($('#cardbody_attachment'+historydata.new_value).length > 0) {
                            content+=$('#cardbody_attachment'+historydata.new_value).text()+'<br />('+$('#cardheader_attachment'+historydata.new_value).html()+')';
                        } else {
                            content+=historydata.new_value+' [since deleted]';
                        }
                        break;
                    case "14":
                        //Case assigned
                        //console.log(userNames);
                        content+='From '+userNames[historydata.old_value]+' to '+userNames[historydata.new_value];
                        break;
                    default:
                        content+='<b>Field:</b> '+historydata.field_changed+'<br /><b>Old Value:</b> '+historydata.old_value+'<br /><b>New Value:</b> '+historydata.new_value;
                        break;
                }
                if(historydata.event_type=="0") {
                    
                }
                
                
                
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


