$(function() {
    if($('#nocase').val() != 0) {
        loadLinkeds();
    }
    
    $('#filterLinkeds').keyup(function() {
        //console.log($(this).val());
        var search=$(this).val();
        $('.linkeditem').each(function() {
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
}) 

function loadLinkeds() {
    var today=new Date();
    var caseId=$('#caseid').val();
    
    var parameters={};
    parameters[':taskid']=caseId;
    //parameters[':assignedto']=$('#user_id').val();
    //parameters[':isclosed']=1;
    //parameters[':datedue']=today.getTime() / 1000 | 0;
    
    //var conditions='assigned_to = :assignedto AND is_closed != :isclosed AND date_due <= :datedue';
    
    var conditions='mas.master_task = :taskid'
    
    var order='t.date_opened DESC';
    
    var start=parseInt($('#relatedstart').val()) || 0;
    var end=parseInt($('#relatedend').val()) || 90000000;
    
    /* if(start<0) {
        start=0;
        $('#todostart').val(0);
    }
    if(end<9) {
        end=9;
        $('#todoend').val(9);
    }  */
    
    //console.log('Doing comments, '+start+' to '+end);
    
    $('#linkedlist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Loading related items...</center>");
    
    
    $.when(linkedList(parameters, conditions, order, start, end)).done(function(linkeds) {
        //console.log('Comments');
        //console.log(comments);
        if(linkeds.count<1) {
            //console.log('Nothing');
            $('#linkedlist').html("<center><br />No linked items for this case yet<br />&nbsp;</center>");
            $('#linkedcount').html('');
        } else {
            //pagerNumbers('todo', start, end, cases.total);
            $('#linkedlist').html('');
            $('#linkedcount').html(linkeds.count);
            var counter=0;
            var divid=1;
            $.each(linkeds.results, function(i, linkeddata) {
                var parentDiv='linkedlist';
                var uniqueId=linkeddata.link_id;
                var primeBox='<a href="index.php?page=case&case='+linkeddata.task_id+'">#'+linkeddata.task_id+'</a>';
                var briefPrimeBox='#';
                var dateBox=timestamp2date(linkeddata.date_opened, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(linkeddata.date_opened, 'dd MM YY');
                var actionPermissions=null;
                var contenttext=deWordify(linkeddata.detailed_desc);
                if(linkeddata.is_closed==1) {
                    contenttext+='<div class="border rounded float-right text-muted small p-1 closed-case">Date closed: '+timestamp2date(linkeddata.date_closed, 'dd/mm/yy g:i a')+'</div>';
                }
                var header='<div class="float-right card-heading-border border rounded pl-1 pr-1 pale-green-link">'+linkeddata.clientname+'</div><span class="d-xs-block dsm-block d-md-none d-lg-none d-xl-none font-weight-bold">#'+linkeddata.task_id+': </span>'+linkeddata.item_summary;
                var content=contenttext;
    
                insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content);
            })
            
        }
    }).then(function() {
        //toggleCaseCards();
        //toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#relatedlist').html("<center><img src='images/logo.png' width='50px' /><br />No related items for this case yet</center>");
        //pagerNumbers('todo', 0, 0, 0);
    });    
}

function todoend_pager() {
    var start=parseInt($('#todostart').val()) || 0;
    var end=parseInt($('#todoend').val()) || 9;
    var qty=end-start+1;
    //console.log('Quantity: '+qty);
    $('#todostart').val((start+qty));
    $('#todoend').val((end+qty));
    
    loadTodo();
}

function todostart_pager() {
    var start=parseInt($('#todostart').val()) || 0;
    var end=parseInt($('#todoend').val()) || 9;
    var qty=end-start+1;
    $('#todostart').val((start-qty));
    $('#todoend').val((end-qty));
    
    loadTodo();
}