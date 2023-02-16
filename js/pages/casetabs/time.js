$(function() {
    if($('#nocase').val() != 0) {
        loadTimes();
    }
    
    $('#filterTimes').keyup(function() {
        //console.log($(this).val());
        var search=$(this).val();
        $('.timeitem').each(function() {
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
}) 

function loadTimes() {
    var today=new Date();
    var caseId=$('#caseid').val();
    var parameters={};
    parameters[':taskid']=caseId;
    
    var conditions='time.task_id = :taskid'
    
    var order='time.date DESC';
    
    var start=parseInt($('#timessstart').val()) || 0;
    var end=parseInt($('#timessend').val()) || 90000000;
    
    $('#timelist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Loading times list...</center>");
    
    var totalTime=0;
    var invoicedTime=0;
    var unInvoicedTime=0;
    
    $.when(timeList(parameters, conditions, order, start, end)).done(function(times) {
        //console.log('Times');
        //console.log(times);
        $('#timelist').before("<div class='row bg-light p-2 rounded border m-1'><div class='float-left text-green'>Summary</div><div class='col'></div><div class='col-md-auto text-right text-info'>Total <span id='totaltime'>50:00</span></div><div class='col'></div><div class='col-md-auto text-right text-info'>Invoiced <span id='invoicedTime'>38:34</span></div><div class='col'></div><div class='col-md-auto text-right text-info'>Uninvoiced <span id='unInvoicedTime' >12:26</span></div><div class='col'></div></div>");
        if(times.count<1) {
            $('#timelist').html("<center><br />No times set for this case yet<br />&nbsp;</center>");
            $('#timecount').html('');
        } else {
            $('#timelist').html('');
            $('#timecount').html(times.total);
            $.each(times.results, function(i, timesdata) {
                //console.log(timesdata);
                var timeTaken=minutes2hours(timesdata.time);
                totalTime+=parseInt(timesdata.time);
                if(timesdata.invoiced==1) {
                    invoicedTime+=parseInt(timesdata.time);
                } else {
                    unInvoicedTime+=parseInt(timesdata.time);
                }


                var parentDiv='timelist';
                var uniqueId='time'+timesdata.time_id;
                var primeBox=timesdata.real_name;
                var briefPrimeBox=getInitials(timesdata.real_name);
                var dateBox=timestamp2date(timesdata.date, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(timesdata.date, 'dd MM YY');
                var actionPermissions=null;
                var content=deWordify(timesdata.description);
                var header='Time Spent: '+timeTaken;
                
                
                insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content);
                //console.log('^^^');
            })
            
        }
    }).then(function() {
        //console.log(totalTime);
        $('#totaltime').html(minutes2hours(totalTime));
        $('#invoicedTime').html(minutes2hours(invoicedTime));
        $('#unInvoicedTime').html(minutes2hours(unInvoicedTime));
        //toggleCaseCards();
        //toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#strategyslist').html("<center><img src='images/logo.png' width='50px' /><br />No planning comments for this case yet</center>");
        //pagerNumbers('todo', 0, 0, 0);
    });    
}

