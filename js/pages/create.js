
    //ORIGINAL CASE.JS FILE

$(function() {
    toggleDatepickers();
    $('#save-case-create').click(function() {
        var userId=globals.user_id;
        //Gather all the values
        var newValues={};
        $('.createCase').each(function(i, obj) {
            if($(this).is(':checkbox')) {
                if($(this).is(':checked')) {
                    newValues[this.id.substr(7)]=1;
                } else {
                    newValues[this.id.substr(7)]=0;
                }
            } else {
                newValues[this.id.substr(7)]=$(this).val();
            }
        });
        
        //Ensure that the minimum required fields exist
        var proceed=1;
        var missing=[];
        //Item Summary
        
        if(newValues['item_summary'].length < 1) {
            proceed=0;
            missing.push('Item Summary cannot be empty');    
        }
        if(newValues['detailed_desc'].length < 1) {
            proceed=0;
            missing.push($('#detailed_description_title').text().trim()+' cannot be empty');
        }
        if(newValues['date_due'].length > 1) {
            console.log(newValues);
            //convert dd/mm/yyyy into unixdatestamp
            newValues['date_due']=date2timestamp(newValues['date_due']);
        }

                
        if(proceed) {
            $.when(caseCreate(newValues, userId)).done(function(changes) {
                var caseId=changes.insertId;
                var createdDate=Date.now()/1000;
                createdDate=timestamp2date(createdDate, "dd/mm/yy g:i a");
                var userName=$('#user_real_name').val();
                var historyMessage='Case opened '+createdDate+' by '+userName;
                
                $.when(historyCreate(caseId, userId, '1', 'Case', null, historyMessage )).done(function(output) {
                    //Now load up the new case
                    showCase(caseId);
                });

            });
            
        } else {
            showMessage('Cannot create case', "<li>"+missing.join("</li>\n<li>")+"</li>");
        }
        //loadCase();
    })       
    
});


function clearCaseForm() {
    $('#caseid_header').html("");
    $('#assignedto').html("<a class='fa-userlink' href=''></a>");
    $('#itemsummary').html("Loading case details...");
    $('#date_due').val("");
    
    $('#assignedto_cover').html("");
    $("#casetype_cover").html("");
    $("#linemanager_cover").html("");
    $('#casegroup_cover').html("");
    $("#department_cover").html("");
    $("#delegate_cover").html("");
    
    $("#detaileddesc_cover").html("");
    $("#resolution_cover").html("");
    
    $("#dateopened_cover").html("");
    $("#openedby_cover").html("");    
}



