$(function() {
    if($('#nocase').val != 0) {
        loadAttachments();
        //console.log('Loading attachments');
    } else {
        //console.log('No Case!');
        //console.log($('#nocase').length);
        //console.log($('#nocase').val());
    }

    $('#newAttachmentBtn').click(function() {
        $('#newAttachmentForm').toggle();    
    });
    
    $("#attachmentForm").on('submit',(function(e) {
        e.preventDefault();
        var caseId=$('#caseid').val();
        var userId=$('#user_id').val();
        var commentText=$('#attachmentFileDesc').val();
        $.ajax({
            url: "ajax.php?method=attachmentUpload",
            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend : function()
            {
                // $("#preview").fadeOut();
                // $("#err").fadeOut();
            },
            success: function(data)
            {
                //console.log('Success');
                var updated=JSON.parse(data);
                //console.log(JSON.parse(data));
                
                $('#attachmentFile').val('');
                $('#attachmentFileDesc').val('');
                historyCreate(caseId, userId, '7', updated.insertId, null, commentText);            
                loadAttachments();
                loadHistory();
                $('#attachmentFileDesc').trigger("reset");
                $('#attachmentFile').trigger("reset");
                $('#newAttachmentForm').toggle();
            },
            error: function(e) 
            {
                //console.log(e);
                //$("#err").html(e).fadeIn();
            }          
        });
    }));        
    
    $('#attachments-inpage_filter').keyup(function(e) {
        var text=$(this).val();
        delay(function() {
            //console.log('Searching '+text+' and using delay');
            searchDivsByText('attachmentlist', text);    
        }, 1500);
    }) 
}) 

function loadAttachments() {
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
    
    var start=parseInt($('#attachmentstart').val()) || 0;
    var end=parseInt($('#attachmentend').val()) || 90000000;
    
    /* if(start<0) {
        start=0;
        $('#todostart').val(0);
    }
    if(end<9) {
        end=9;
        $('#todoend').val(9);
    }  */
    
    //console.log('Doing comments, '+start+' to '+end);
    
    $('#attachmentlist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Loading attachments...</center>");
    
    
    $.when(attachmentList(parameters, conditions, order, start, end)).done(function(attachments) {
        //console.log('Attachments');
        //console.log(attachments);
        if(attachments.count<1) {
            //console.log('Nothing');
            $('#attachmentlist').html("<center><br />No attachments for this case yet<br />&nbsp;</center>");
            $('#attachmentcount').html('');
        } else {
            //pagerNumbers('todo', start, end, cases.total);
            $('#attachmentlist').html('');
            $('#attachmentcount').html(attachments.results.length);
            $.each(attachments.results, function(i, attachmentdata) {
                /* Put formatting into a standalone script */
                console.log('Showing Attachment');
                console.log(attachmentdata);
                var parentDiv='attachmentlist';
                var uniqueId='attachment'+attachmentdata.attachment_id;
                var primeBox=attachmentdata.real_name;
                var briefPrimeBox=getInitials(attachmentdata.real_name);
                var dateBox=timestamp2date(attachmentdata.date_added, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(attachmentdata.date_added, 'dd MM YY');
                var actionPermissions=null;
                var fileExtensionIndex=attachmentdata.orig_name.lastIndexOf('.');
                fileExtension='';
                if(fileExtensionIndex >0) {
                    var fileExtension=attachmentdata.orig_name.substring(fileExtensionIndex +1);
                } 
                if(globals.user_id==attachmentdata.user_id || globals.is_admin=='1') {
                    actionPermissions=['edit', 'delete'];    
                }                
                var header='<a href="download.php?attachmentid='+attachmentdata.attachment_id+'" class="link" id="loadattachment_'+attachmentdata.attachment_id+'">'+attachmentdata.orig_name+'</a>';
                var content=deWordify(attachmentdata.file_desc);
                if(fileExtension=='eml' || fileExtension == 'msg') {
                    header+='&nbsp;<span class="btn btn-light border rounded mb-0" style="padding: 0; font-size: 1.5rem" title="Show email" onClick="showEmail(\''+attachmentdata.attachment_id+'\')">&#x2709;</span>';
                    content+='<div id="emailContent_'+attachmentdata.attachment_id+'" class="email-content hidden"></div>';
                }

                
    
                insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content);
                
    
                /*
                $('#attachmentlist').append("<div class='card attachmentitem col-lg-4 m-2 p-0' id='attachmentcard_"+attachmentdata.attachment_id+"'><div class='card-header' id='attachmentheader_"+attachmentdata.attachment_id+"'></div><div class='card-body attachment-card small'><div class='overflow-auto' style='max-height: 130px' id='attachmentbody_"+attachmentdata.attachment_id+"'></div></div></div>");
                var deleteclass='disabledimage';
                if($('#user_id').val()==attachmentdata.user_id) {
                    var deleteclass='enabledimage';
                }
                $('#attachmentheader_'+attachmentdata.attachment_id).append("<div class='float-right pl-2 "+deleteclass+"' title='Delete attachment'><img src='images/trash.svg' alt='Delete attachment' width='20px'></div>");
                $('#attachmentheader_'+attachmentdata.attachment_id).append("<div class='float-right pl-2 "+deleteclass+"' title='Edit attachment'><img src='images/edit.svg' alt='Edit attachment' width='20px'></div>");
                $('#attachmentheader_'+attachmentdata.attachment_id).append("<div class='float-right card-heading-border card-heading-inverse border rounded pl-1 pr-1 mr-2'>"+attachmentdata.real_name+"</div>Attachment added "+dateAdded)
                //$('#attachmentbody_'+attachmentdata.attachment_id).append('<span class="link" id="loadattachment_'+attachmentdata.attachment_id+'" onClick="loadAttachment(\''+attachmentdata.orig_name+'\', \''+attachmentdata.file_name+'\', \''+attachmentdata.file_type+'\')">'+attachmentdata.orig_name+'</span><br />');
                $('#attachmentbody_'+attachmentdata.attachment_id).append("<a href='download.php?attachmentid="+attachmentdata.attachment_id+"'>"+attachmentdata.orig_name+"</a><br />");
                $('#attachmentbody_'+attachmentdata.attachment_id).append(deWordify(attachmentdata.file_desc));
                $('#attachmentheader_'+attachmentdata.attachment_id).append("<div style='clear: both'></div>");
                */
            })
            
        }
    }).then(function() {
        //toggleCaseCards();
        //toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#attachmentlist').html("<center><img src='images/logo.png' width='50px' /><br />No attachments for this case yet</center>");
        //pagerNumbers('todo', 0, 0, 0);
    });    
}

function showEmail(attachmentId) {

    if($('#emailContent_'+attachmentId).is(':visible')) {
        $('#emailContent_'+attachmentId).hide('slow');
    } else {
        var output= $.ajax({
            url: 'helpers/showEmail.php',
            method: 'POST',
            data: {attachmentId: attachmentId},
            dataType: 'html'
        }).done(function(response) {
            //var currentHeight=$('#emailContent_'+attachmentId).height();
            $('#cardbody_attachment'+attachmentId).css('max-height', '400px').css('display', 'flex').css('flex-direction', 'column'); //Make the parent div bigger for easier viewing of an email
    
            $('#emailContent_'+attachmentId).css('flex-grow', '1').css('overflow-y', 'auto').html(response).show('slow');
            
        }).fail(function(jqXHR, textStatus) {
            console.log("Request failed: "+textStatus);
        }) 
    }


}