$(function() {
    loadCase();

    $('#hideCaseDetails').click(function() {
        toggleCaseDetails();
    })
    
    $('#editCaseDetails').click(function() {
        //alert('Stub for editing a case');
        toggleCaseEdit(); 
    })
    
    $('#closeCase').click(function() {
        alert('Stub for closing a case');
    })    

    $('.nav-link-tab').click(function () {
        if($('#case-card').first().is(":visible")) {
            //toggleCaseDetails('hide');
        }
    }); 
    
    $('#cancel-case-edits').click(function() {
        toggleCaseEdit();
        loadCase();  
    });
    
    $('#save-case-edits').click(function() {
        var caseId=$('#caseid').val();
        var userId=$('#userid').val();
        //Gather all the values
        var newValues={};
        $('.updateCase').each(function(i, obj) {
            if($(this).is(':checkbox')) {
                if($(this).is(':checked')) {
                    newValues[this.id.substr(5)]=1;
                } else {
                    newValues[this.id.substr(5)]=0;
                }
            } else {
                newValues[this.id.substr(5)]=$(this).val();
            }
        });
        
        $.when(caseUpdate(caseId, newValues)).done(function(changes) {
            console.log('Update completed');
            console.log(changes);
            console.log('Create History');
            console.log('Case ID: '+caseId+', User ID: '+userId);
            for (var key in changes) {
                if(changes.hasOwnProperty(key)) {
                    historyCreate(caseId, userId, '0', 'Case Details: '+key, changes[key]["old"], changes[key]["new"]);
                }
            }            
            loadCase();
            loadHistory();
        });
        //loadCase();
    })
    
    $('#save-close-case-edits').click(function() {
        var caseId=$('#caseid').val();
        var userId=$('#userid').val();
        //Gather all the values
        var newValues={};
        $('.updateCase').each(function(i, obj) {
            if($(this).is(':checkbox')) {
                if($(this).is(':checked')) {
                    newValues[this.id.substr(5)]=1;
                } else {
                    newValues[this.id.substr(5)]=0;
                }
            } else {
                newValues[this.id.substr(5)]=$(this).val();
            }
        });
        
        $.when(caseUpdate(caseId, newValues)).done(function(changes) {
            console.log(changes);
            console.log('Create History');
            console.log('Case ID: '+caseId+', User ID: '+userId);
            for (var key in changes) {
                if(changes.hasOwnProperty(key)) {
                    historyCreate(caseId, userId, '0', 'Case Details: '+key, changes[key]["old"], changes[key]["new"]);
                }
            }
            toggleCaseEdit();
            loadCase();
            loadHistory();
        });
        //loadCase();
    })       
    
});

function loadCase() {
    var today=new Date();
    
    var caseId=$('#caseid').val();
    console.log('Reloading');
    $.when(getCase(caseId)).done(function(caseDetails) {
        console.log('Cases');
        casedata=caseDetails.results;
        //console.log(caseDetails);
        clearCaseForm();
        
        var thisDateDue=timestamp2date(casedata.date_due);
        var dateOpened=timestamp2date(casedata.date_opened);
        if(typeof casedata.pref_name !== 'undefined') {
            var client=casedata.pref_name+' '+casedata.surname;
        } else {
            var client=casedata.member;
        }
        var dateclass='date-future';
        var lasteditedby=(casedata.last_edited_real_name) ? casedata.last_edited_real_name : "Unknown";
        var assignedto=(casedata.assigned_real_name) ? casedata.assigned_real_name : 'Unassigned';
        if(casedata.date_due < $('#today_start').val()) {dateclass='date-overdue';}
        if(casedata.date_due >= $('#today_start').val() && casedata.date_due <= $('#today_end').val()) {dateclass='date-due';}

        //console.log(casedata.results[0]);
        
        $('#caseid_header').html(casedata.task_id);
        //toggleCaseCards();
        $('#clientname').html(casedata.clientname+"<a class='fa-userlink' href='"+casedata.member+"'></a>");
        $('#itemsummary').html(casedata.item_summary);
        //console.log(thisDateDue);
        
        $('#date_due').val(thisDateDue);
        $('#date_due_parent').addClass(dateclass);
        
        $('#assignedto_cover').html(casedata.assigned_real_name);
        $("#casetype_cover").html(casedata.tasktype_name);
        if(casedata.line_manager) {
            $("#linemanager_cover").html(casedata.line_manager+" ("+casedata.line_manager_ph+") <a class='fa-phone' href='tel:"+casedata.line_manager_ph+"'></a><a class='fa-chat' href='sms:"+casedata.line_manager_ph+"'></a>");
        }
        $('#casegroup_cover').html(casedata.version_name);
        $("#department_cover").html(casedata.category_name);
        $("#unit_cover").html(casedata.unit);
        if(casedata.local_delegate) {
            $("#delegate_cover").html(casedata.local_delegate+" ("+casedata.local_delegate_ph+") <a class='fa-phone' href='tel:'"+casedata.local_delegate_ph+"'></a><a class='fa-chat' href='sms:"+casedata.local_delegate_ph+"'></a>");
        }
        
        $("#detaileddesc_cover").html(deWordify(casedata.detailed_desc).replace(/\n/g, "<br />"));
        $("#resolution_cover").html(deWordify(casedata.resolution_sought).replace(/\n/g, "<br />"));
        
        $("#dateopened_cover").html(dateOpened);
        $("#openedby_cover").html(casedata.openedby_real_name);
        
        //Now insert the custom fields
        if(typeof casedata.customlist != "undefined") {
            $.each(casedata.customlist, function(i, name) {
                console.log(name);
                var type=$('#'+name+'_cover').prop('nodeName');
                console.log($('#'+name+'_cover').prop('nodeName'));
                console.log(casedata[name]);
                if(type=="INPUT") {
                    if($('#'+name+'_cover').is(':checkbox')) {
                        if(casedata[name]!="0") {
                            $('#'+name+'_cover').prop('checked', true);
                            $('#edit_'+name).prop('checked', true);
                        } else {
                            $('#'+name+'_cover').prop('checked', false);
                            $('#edit_'+name).prop('checked', false);
                        }
                    } else {
                        $('#'+name+'_cover').val(casedata[name]);
                        $('#edit_'+name).val(casedata[name]);
                    }
                } else {
                    $("#"+name+"_cover").html(casedata[name]);
                    $("#edit_"+name).val(casedata[name]);
                }
            })
        }

        //Fill out the edit form
        $('#edit_item_summary').val(casedata.item_summary);
        $('#edit_member').val(casedata.member);
        $('#edit_assigned_to').val(casedata.assigned_to);
        $('#edit_product_version').val(casedata.version_id);
        $('#edit_task_type').val(casedata.task_type);
        $('#edit_product_category').val(casedata.category_id);
        $('#edit_line_manager').val(casedata.line_manager);
        $('#edit_line_manager_ph').val(casedata.line_manager_ph);
        $('#edit_local_delegate').val(casedata.local_delegate);
        $('#edit_local_delegate_ph').val(casedata.local_delegate_ph);
        $('#edit_detailed_desc').val(casedata.detailed_desc);
        $('#edit_resolution_sought').val(casedata.resolution_sought);
        
        //
            
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers();        
    }).fail(function() {
        console.log('Nothing found');
        $('#todolist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
        pagerNumbers('todo', 0, 0, 0);        
    });    
}

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

function toggleCaseDetails(action) {
    if($('#case-card').is(":visible") || action=="hide") {
        $('#case-card').hide();
        $('#hideCaseDetails').html($('#hideCaseDetails').html().replace(' Hide', ' Show'));
    } else {
        $('#case-card').show();
        $('#hideCaseDetails').html($('#hideCaseDetails').html().replace(' Show', ' Hide'));

    }    
}

function toggleCaseEdit() {
    
    if($('#case-edit').is(":visible")) {
        $('#case-edit').hide();
        $('#case-card').show();
        $('#case-tabs').show();
        
    } else {
        //Fill out all the fields
        
        $('#case-edit').show();
        $('#case-card').hide();
        $('#case-tabs').hide();
    }
    
}

