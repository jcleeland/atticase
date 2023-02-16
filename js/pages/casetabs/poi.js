$(function() {
    if($('#nocase').val() != 0) {
        loadPois();
    }
    
    $('#newPoiBtn').click(function() {
        $('#newPoiForm').toggle();    
    });
    
    $('#submitPoiBtn').click(function() {
        var userId=globals.user_id;
        var caseId=$('#caseid').val();
        var poiComment=$('#poiComment').val();
        console.log('POI: '+poiComment);
        var time=Math.floor(Date.now() / 1000);
        $.when(attachmentCreate(caseId, userId, poiComment, time)).done(function(insert) {
            if(insert.count=="1") {
                $('#fileDesc').val('');
                $('#newAttachmentForm').toggle();
                historyCreate(caseId, userId, '60', null, null, poiComment);
                loadPois();
                loadHistory();
            }
        })
        //Successfully added
    })
    
    $('#filterPois').keyup(function() {
        //console.log($(this).val());
        var search=$(this).val();
        $('.poiitem').each(function() {
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
}) 

function loadPois() {
    var today=new Date();
    var caseId=$('#caseid').val();
    
    var parameters={};
    parameters[':taskid']=caseId;
    //parameters[':assignedto']=$('#user_id').val();
    //parameters[':isclosed']=1;
    //parameters[':datedue']=today.getTime() / 1000 | 0;
    
    //var conditions='assigned_to = :assignedto AND is_closed != :isclosed AND date_due <= :datedue';
    
    var conditions='task_id = :taskid'
    
    var order='p.created DESC';
    
    var start=parseInt($('#poistart').val()) || 0;
    var end=parseInt($('#poiend').val()) || 90000000;
    
    /* if(start<0) {
        start=0;
        $('#todostart').val(0);
    }
    if(end<9) {
        end=9;
        $('#todoend').val(9);
    }  */
    
    //console.log('Doing comments, '+start+' to '+end);
    
    $('#poilist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Loading people of interest...</center>");
    
    
    $.when(poiList(parameters, conditions, order, start, end)).done(function(pois) {
        //console.log('POIs');
        //console.log(pois);
        if(pois.count<1) {
            //console.log('Nothing');
            $('#poilist').html("<center><br />No people of interest for this case yet<br />&nbsp;</center>");
            $('#poicount').html('');
        } else {
            //pagerNumbers('todo', start, end, cases.total);
            $('#poilist').html('');
            $('#poicount').html(pois.count);
            $.each(pois.results, function(i, poidata) {
                
                var parentDiv='poilist';
                var uniqueId='poi'+poidata.poi_id;
                var name=poidata.firstname+" "+poidata.lastname
                var primeBox=name;
                var briefPrimeBox=getInitials(name);
                var dateBox=timestamp2date(poidata.created, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(poidata.created, 'dd MM YY');
                var actionPermissions=null;
                if(globals.user_id==poidata.user_id || globals.is_admin=='1') {
                    actionPermissions=['edit', 'delete'];    
                }                
                var header='<span class="d-xs-block dsm-block d-md-none d-lg-none d-xl-none font-weight-bold">'+name+': </span>'+poidata.organisation;
                var content=deWordify(poidata.comment);
    
                insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content);                
                /* Put formatting into a standalone script */
                
                /* $('#poilist').append("<div class='card poiitem col-lg-3 m-2 p-0' id='poicard_"+poidata.poi_id+"'><div class='card-header' id='poiheader_"+poidata.poi_id+"'></div><div class='card-body poi-card small'><div class='overflow-auto' style='max-height: 130px' id='poibody_"+poidata.poi_id+"'></div></div></div>");
                var deleteclass='disabledimage';
                if($('#user_id').val()==poidata.user_id) {
                    var deleteclass='enabledimage';
                }
                $('#poiheader_'+poidata.poi_id).append("<div class='float-right pl-2 "+deleteclass+"' title='Delete person of interest'><img src='images/trash.svg' alt='Delete person of interest' width='20px'></div>");
                $('#poiheader_'+poidata.poi_id).append("<div class='float-right pl-2 "+deleteclass+"' title='Edit person of interest'><img src='images/edit.svg' alt='Edit person of interest' width='20px'></div>");
                $('#poiheader_'+poidata.poi_id).append("<div class='float-left card-heading-border card-heading-inverse border rounded pl-1 pr-1 mr-2'>"+poidata.firstname+" "+poidata.lastname+"</div><div style='clear: both'></div><div class='smaller font-italic'>Person of interest added "+dateAdded+"</div>")
                $('#poibody_'+poidata.poi_id).append("<a href='download.php?poiid="+poidata.poi_id+"'>"+poidata.organisation+"</a><br />");
                $('#poibody_'+poidata.poi_id).append(deWordify(poidata.comment));
                $('#poiheader_'+poidata.poi_id).append("<div style='clear: both'></div>"); */
            })
            
        }
    }).then(function() {
        //toggleCaseCards();
        //toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#poilist').html("<center><img src='images/logo.png' width='50px' /><br />No people of interest for this case yet</center>");
        //pagerNumbers('todo', 0, 0, 0);
    });    
}