$(function() {
    loadRelateds();

    $('#newRelatedBtn').click(function() {
        $('#newRelatedForm').toggle();    
    });
    
    $('#submitRelatedBtn').click(function() {
        var userId=globals.user_id;
        var caseId=$('#caseid').val();
        var relatedCaseId=$('#relatedCaseId').val();
        console.log('Related: '+relatedCaseId);
        var time=Math.floor(Date.now() / 1000);
        $.when(relatedCreate(caseId, userId, relatedCaseId, time)).done(function(insert) {
            if(insert.count=="1") {
                $('#relatedCaseId').val('');
                $('#newRelatedForm').toggle();
                historyCreate(caseId, userId, '15', null, null, relatedCaseId);
                loadRelateds();
                loadHistory();
            }
        })
        //Successfully added
    })


    
    $('#filterRelateds').keyup(function() {
        //console.log($(this).val());
        var search=$(this).val();
        $('.relateditem').each(function() {
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
}) 

function loadRelateds() {
    var today=new Date();
    var caseId=$('#caseid').val();
    var parameters={};
    parameters[':taskid']=caseId;
    
    var conditions='t.task_id = :taskid'
    
    var order='t.date_opened DESC';
    
    var start=parseInt($('#relatedstart').val()) || 0;
    var end=parseInt($('#relatedend').val()) || 90000000;
    
    $('#relatedlist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Loading related items...</center>");
    
    
    $.when(relatedList(parameters, conditions, order, start, end)).done(function(relateds) {
        console.log('Relateds');
        console.log(relateds);
        if(relateds.count<1) {
            $('#relatedlist').html("<center><br />No related items for this case yet<br />&nbsp;</center>");
            $('#relatedcount').html('');
        } else {
            $('#relatedlist').html('');
            $('#relatedcount').html(relateds.results.length);
            $.each(relateds.results, function(i, relateddata) {
                var parentDiv='relatedlist';
                var uniqueId='related'+relateddata.related_id;
                var primeBox='<a href="index.php?page=case&case='+relateddata.task_id+'">#'+relateddata.task_id+'</a>';
                var briefPrimeBox='<a href="index.php?page=case&case='+relateddata.task_id+'">#</a>';
                var dateBox=timestamp2date(relateddata.date_opened, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(relateddata.date_opened, 'dd MM YY');
                var actionPermissions=null;
                var contenttext=deWordify(relateddata.detailed_desc);
                if(relateddata.is_closed==1) {
                    contenttext+='<div class="border rounded float-right text-muted small p-1 closed-case" >Date closed: '+timestamp2date(relateddata.date_closed, 'dd/mm/yy g:i a')+'</div>';
                }
                var header='<div class="float-right card-heading-border border rounded pl-1 pr-1 pale-green-link">'+relateddata.clientname+'</div><span class="d-xs-block dsm-block d-md-none d-lg-none d-xl-none font-weight-bold">#'+relateddata.task_id+': </span>'+relateddata.item_summary;
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

