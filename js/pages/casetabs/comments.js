$(function() {
    loadComments();
    
    $('#newCommentBtn').click(function() {
        $('#newCommentForm').toggle();    
    });
    
    $('#submitCommentBtn').click(function() {
        var userId=globals.user_id;
        var caseId=$('#caseid').val();
        var commentText=$('#newComment').val();
        console.log('COmment: '+commentText);
        var time=Math.floor(Date.now() / 1000);
        $.when(commentCreate(caseId, userId, commentText, time)).done(function(insert) {
            if(insert.count=="1") {
                $('#newComment').val('');
                $('#newCaseForm').toggle();
                historyCreate(caseId, userId, '4', null, null, commentText);
                loadComments();
                loadHistory();
            }
        })
        //Successfully added
    })
    
    $('#filterComments').keyup(function() {
        //console.log($(this).val());
        var search=$(this).val();
        $('.commentitem').each(function() {
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
}) 

function loadComments() {
    var today=new Date();
    var caseId=$('#caseid').val();
    
    var parameters={};
    parameters[':taskid']=caseId;
    //parameters[':assignedto']=$('#user_id').val();
    //parameters[':isclosed']=1;
    //parameters[':datedue']=today.getTime() / 1000 | 0;
    
    //var conditions='assigned_to = :assignedto AND is_closed != :isclosed AND date_due <= :datedue';
    
    var conditions='task_id = :taskid'
    
    var order='date_added DESC';
    
    var start=parseInt($('#commentstart').val()) || 0;
    var end=parseInt($('#commentend').val()) || 90000000;
    
    /* if(start<0) {
        start=0;
        $('#todostart').val(0);
    }
    if(end<9) {
        end=9;
        $('#todoend').val(9);
    }  */
    
    //console.log('Doing comments, '+start+' to '+end);
    
    $('#commentlist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Loading comments...</center>");
    
    
    $.when(commentList(parameters, conditions, order, start, end)).done(function(comments) {
        //console.log('Comments');
        //console.log(comments);
        if(comments.count<1) {
            //console.log('Nothing');
            $('#commentlist').html("<center><br />No comments for this case yet<br />&nbsp;</center>");
            $('#commentcount').html('');
        } else {
            //pagerNumbers('todo', start, end, cases.total);
            $('#commentlist').html('');
            $('#commentcount').html(comments.results.length);
            var counter=0;
            var divid=1;
            $.each(comments.results, function(i, commentdata) { 
                //console.log('Commentdata');
                //console.log(commentdata);
                
                
                var parentDiv='commentlist';
                var uniqueId='comment'+commentdata.comment_id;
                var dateBox=timestamp2date(commentdata.date_added, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(commentdata.date_added, 'dd MM YY');
                var primeBox=commentdata.real_name;
                var briefPrimeBox=getInitials(commentdata.real_name);
                var actionPermissions=null;
                //Permissions
                // Owner: edit, delete
                // Admin: edit, delete
                if(globals.user_id==commentdata.user_id || globals.is_admin=='1') {
                    actionPermissions=['edit', 'delete'];    
                }

                var header=null
                var content=deWordify(commentdata.comment_text);
                
                insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content)
                /* Put formatting into a standalone script */
                /* counter++; //Counter will be either 1 or 2 (reset at 2)
                if(counter==1) {
                    $('#commentlist').append('<div class="row-md-12" id="parent_'+divid+'"></div>');
                }
                $('#parent_'+divid).append("<div class='card commentitem col-md m-2 p-0' id='commentcard_"+commentdata.comment_id+"'><div class='card-header' id='commentheader_"+commentdata.comment_id+"'></div><div class='card-body comment-card small'><div class='overflow-auto' style='max-height: 130px' id='commentbody_"+commentdata.comment_id+"'></div></div></div>");
                var deleteclass='disabledimage';
                if($('#user_id').val()==commentdata.user_id) {
                    var deleteclass='enabledimage';
                }
                $('#commentheader_'+commentdata.comment_id).append("<div class='float-right pl-2 "+deleteclass+"' title='Delete comment'><img src='images/trash.svg' alt='Delete comment' width='20px'></div>");
                $('#commentheader_'+commentdata.comment_id).append("<div class='float-right pl-2 "+deleteclass+"' title='Edit comment'><img src='images/edit.svg' alt='Edit comment' width='20px'></div>");
                $('#commentheader_'+commentdata.comment_id).append("<div class='float-right card-heading-border card-heading-inverse border rounded pl-1 pr-1 mr-2'>"+commentdata.real_name+"</div>Note added "+dateAdded)
                $('#commentbody_'+commentdata.comment_id).append(deWordify(commentdata.comment_text));
                $('#commentheader_'+commentdata.comment_id).append("<div style='clear: both'></div>");
                if(counter==2) {
                    counter=0;
                    divid++;
                }*/
                
            })
            
        }
    }).then(function() {
        //toggleCaseCards();
        //toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#commentlist').html("<center><img src='images/logo.png' width='50px' /><br />No comments for this case yet</center>");
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