$(function() {
    if($('#nocase').val() != 0) {
        loadLinkeds();
    }
    
    $('#newLinkBtn').click(function() {
        $('#newLinkForm').toggle();    
    }); 
    
    $('#submitLinkBtn').click(function() {
        var thisCase=$('#caseid').val();
        var linkType=$('#linkType').val();
        var linkCase=$('#linkCase').val();
        console.log(linkType, linkCase);
        $.when(linkedCreate(linkType, thisCase, linkCase)).done(function(output) {
            console.log(output);
            loadLinkeds();
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
    
    var conditions='';
    
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
        //console.log('Linked Cases');
        //console.log(linkeds);
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
            var isParent=0;
            var isChild=0;
            $.each(linkeds.results, function(i, linkeddata) {
                var parentDiv='linkedlist';
                var uniqueId='master'+linkeddata.link_id;
                var icon='';
                switch(linkeddata.linktype) {
                    case 'parent':
                        isChild=1;
                        icon='<img src="images/linked-parent.svg" class="mr-2" width="25px" title="This case is the master case to the one you are viewing. You should add all comments and make other changes to this case rather the one you are currently viewing." />';
                        var uniqueId='master'+linkeddata.link_id;
                        break;
                    case 'child':
                        isParent=1;
                        icon='<img src="images/linked-child.svg" class="mr-2" width="25px" title="This case is a dependent case of the one you are viewing. Changes you make to the current case will be copied to this one as well." />';
                        var uniqueId='master'+linkeddata.link_id;
                        break;
                    case 'companion':
                        icon='<img src="images/linked-companion.svg" class="float mr-2" width="25px" title="This case is a companion to the one you are viewing. Users will see updates from this case when they visit it." />';
                        var uniqueId='companion'+linkeddata.link_id;
                        break;
                        
                }
                var primeBox=icon+'<a href="index.php?page=case&case='+linkeddata.task_id+'">#'+linkeddata.task_id+'</a>';
                var briefPrimeBox='#';
                var dateBox=timestamp2date(linkeddata.date_opened, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(linkeddata.date_opened, 'dd MM YY');
                var actionPermissions=null;
                if(globals.is_admin=='1') { //Owner of either the current case or the linked case should be able to detach
                    actionPermissions=['delete'];    
                }                
                var contenttext=deWordify(linkeddata.detailed_desc);
                if(linkeddata.is_closed==1) {
                    contenttext+='<div class="border rounded float-right text-muted small p-1 closed-case">Date closed: '+timestamp2date(linkeddata.date_closed, 'dd/mm/yy g:i a')+'</div>';
                }

                var header='<div class="float-right card-heading-border border rounded pl-1 pr-1 pale-green-link">'+linkeddata.clientname+'</div><span class="d-xs-block dsm-block d-md-none d-lg-none d-xl-none font-weight-bold">#'+linkeddata.task_id+': </span>'+linkeddata.item_summary;
                var content=contenttext;
    
                insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content);
            })
            
            if(isParent > 0) {
                //Remote the option to make this case a child of another case
                console.log('Parent: '+isParent);
                $('#linkType option:contains("Dependent Of Another Case")').attr("disabled", "disabled");
            } else {
                console.log('Not parent: '+isParent);
                $('#linkType option:contains("Dependent Of Another Case")').attr("disabled", "");
            }
            if(isChild > 0) {
                console.log('Child: '+isChild);
                //Remote the option to make this case a child of another case
                $('#linkType option:contains("Dependent Of Another Case")').attr("disabled", "disabled");
                $('#linkType option:contains("Master To Another Case")').attr("disabled", "disabled");
            } else {
                console.log('Not child: '+isChild);
                $('#linkType option:contains("Dependent Of Another Case")').attr("disabled", "");
                $('#linkType option:contains("Master To Another Case")').attr("disabled", "");
            }
            
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