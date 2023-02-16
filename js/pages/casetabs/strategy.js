$(function() {
    if($('#nocase').val() != 0) {
        loadStrategys();
    }
    
    $('#filterStrategys').keyup(function() {
        //console.log($(this).val());
        var search=$(this).val();
        $('.strategyitem').each(function() {
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
}) 

function loadStrategys() {
    var today=new Date();
    var caseId=$('#caseid').val();
    var parameters={};
    parameters[':taskid']=caseId;
    
    var conditions='st.task_id = :taskid'
    
    var order='st.comment_date DESC';
    
    var start=parseInt($('#strategysstart').val()) || 0;
    var end=parseInt($('#strategyssend').val()) || 90000000;
    
    $('#strategylist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Loading planning discussions list...</center>");
    
    
    $.when(strategyList(parameters, conditions, order, start, end)).done(function(strategys) {
        //console.log('Strategys');
        //console.log(strategys);
        if(strategys.count<1) {
            $('#strategylist').html("<center><br />No planning comments set for this case yet<br />&nbsp;</center>");
            $('#strategycount').html('');
        } else {
            $('#strategylist').html('');
            $('#strategycount').html(strategys.total);
            $.each(strategys.results, function(i, strategysdata) {
                //console.log(strategysdata);
                var parentDiv='strategylist';
                var uniqueId='strategy'+strategysdata.strategy_id;
                var primeBox=strategysdata.real_name;
                var briefPrimeBox=getInitials(strategysdata.real_name);
                var dateBox=timestamp2date(strategysdata.comment_date, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(strategysdata.comment_date, 'dd MM YY');
                var actionPermissions=null;
                var content=deWordify(strategysdata.comment);
                
                if(strategysdata.acknowledged==1) {
                    var ackdate=timestamp2date(strategysdata.acknowledged_date, 'dd/mm/yy g:i a');
                    var header='Acknowledged on '+ackdate;
                } else {
                    var header='Unacknowledged';
                }
                
                insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content);
                //console.log('^^^');
                if(strategysdata.acknowledged != 1) {
                    $('#rightTabCol_strategy'+strategysdata.strategy_id).addClass('oct-warning');
                }
            })
            
        }
    }).then(function() {
        //toggleCaseCards();
        //toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#strategyslist').html("<center><img src='images/logo.png' width='50px' /><br />No planning comments for this case yet</center>");
        //pagerNumbers('todo', 0, 0, 0);
    });    
}

