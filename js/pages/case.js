/*
 * Copyright [2022] [Jason Alexander Cleeland, Melbourne Australia]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
$(function() {
    loadCase();

    const inputField = $('.lookahead-input-field');
    const inputDropdown = $('.lookahead-input-dropdown');
    const options = $('#options li').map(function() { return $(this).text(); }).get();
    
    function updateOptions(query) {
      const filteredOptions = options.filter(option => option.toLowerCase().includes(query.toLowerCase()));
      inputDropdown.html(filteredOptions.map(option => `<li>${option}</li>`).join(''));
      inputDropdown.css('display', filteredOptions.length > 0 ? 'block' : 'none');
    }
    
    inputField.on('input', function() {
      updateOptions($(this).val());
    });
    
    inputDropdown.on('click', 'li', function() {
      inputField.val($(this).text());
      inputDropdown.css('display', 'none');
    });    
    
    
    $('#hideCaseDetails').click(function() {
        toggleCaseDetails();
    })
    
    $('#editCaseDetails').click(function() {
        //alert('Stub for editing a case');
        toggleCaseEdit(); 
    })
    
    $('#closeCase').click(function() {
        //console.log('Closing case');
        toggleCloseCase();
    });
    
    $('#reopenCase').click(function() {
        if (confirm('Are you sure you want to reopen this case?')) {
            var caseId=$('#caseid').val();
            var userId=$('#user_id').val();
            
            var newValues={};
            newValues['is_closed']=0;
            
            $.when(caseUpdate(caseId, newValues)).done(function(output) {
                
                $.when(historyCreate(caseId, userId, 13, null, null, null)).done(function(hOutput) {
                    loadCase();
                    loadHistory();
                });
            })
                
        }
    })
    
    $('#deleteCase').click(function() {
        var settings=getSettings('OpenCaseTrackerSystem');
        //Check that this is an administrator
        //Check that the case is closed
        //Confirm to delete case, with warnings that it is irreversible
        //Delete the case
        if(settings.administrator==1) {
            if($('#closedStamp').is(':visible')) {
                if(confirm('Are you sure you want to delete this case? Deleting a case is irreversible and removes all details about the case including comments, attachments and history. The case will no longer be included in reports or be available for historical data.')) {
                    var caseId=$('#caseid').val();
                    $.when(caseDelete(caseId)).done(function() {
                        alert('Case, and all its data, has been deleted');
                        window.location.href="?page=cases";    
                    })
                    
                }
                
            }
            
        }
        
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
                if(typeof $(this).val() !== "undefined" && $(this).val() != null && $(this).val() != "") {
                    newValues[this.id.substr(5)]=$(this).val();
                }                
            }
        });
        
        $.when(caseUpdate(caseId, newValues)).done(function(changes) {
            //console.log('Update completed');
            //console.log(changes);
            //console.log('Create History');
            //console.log('Case ID: '+caseId+', User ID: '+userId);
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
        //console.log('Saving and closing');
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
                if(typeof $(this).val() !== "undefined" && $(this).val() != null) {  //Removed && $(this).val() != "" to allow updating to empty values
                    newValues[this.id.substr(5)]=$(this).val();
                }
            }
        });
        //console.log(newValues);
        $.when(caseUpdate(caseId, newValues)).done(function(changes) {
            //console.log(changes);
            //console.log('Create History');
            //console.log('Case ID: '+caseId+', User ID: '+userId);
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
    
    $('#close_case_btn').click(function() {
        var caseId=$('#caseid').val();
        var closeDate=date2timestamp($('#close_closure_date').val());
        var closeReason=$('#close_resolution_reason').val();
        var closeNotes=$('#close_closure_comment').val();
        
        var newValues={};
        newValues['date_closed']=closeDate;
        newValues['resolution_reason']=closeReason;
        newValues['closure_comment']=closeNotes;
        newValues['closed_by']=globals.user_id;
        newValues['is_closed']=1;
        //console.log(newValues);
        $.when(caseUpdate(caseId, newValues)).done(function(changes) {
            historyCreate(caseId, globals.user_id, '2', closeNotes, closeReason);
            loadCase();
            loadHistory();
        })
    })
        
    $('.nav-link-tab').click(function() {
        //Find the tab which has just been displayed and store it in the cookie against the case number
        var status=getStatus();
        //console.log($(this).attr("href"));
        var caseId=$('#caseid').val();
        var lasttab=$(this).attr("href");
        var timestamp=parseInt(Date.now()/1000);
        saveCaseView(caseId, timestamp, lasttab);
        
    })   
    
    $('#is_restricted').click(function() {
        //console.log($('#edit_is_restricted').val())
        if($('#edit_is_restricted').val()==1) {
            $('#edit_is_restricted').val('0');
            $('#is_restricted').removeClass('bg-danger');
            $('#is_restricted').addClass('bg-light');
            $('#is_restricted').attr('title', 'This case is not restricted');
            $('#is_restricted_image').attr('src', 'images/unlock.svg'); 
        } else {
            $('#edit_is_restricted').val('1');
            $('#is_restricted').removeClass('bg-light');
            $('#is_restricted').addClass('bg-danger');
            $('#is_restricted').attr('title', 'This case has restricted access to only administrators and the user it is assigned to');
            $('#is_restricted_image').attr('src', 'images/lock.svg');  
        }
    })  
         
    //Set the tab according to the lasttab setting in the cookie (if it exists)
    var octStatus=getStatus();
    var thisCase=octStatus.caseviews['case'+$('#caseid').val()];
    console.log(thisCase);
    //$('#case-tabs').tabs();
    if(thisCase && thisCase.lasttab) {
        console.log('Selected by '+thisCase.lasttab.substring(1));
        selectCaseTabByName(thisCase.lasttab.substring(1));
        console.log('Completed selection by '+thisCase.lasttab.substring(1));
    }      
});

function loadCase() {
    var today=new Date();
    var caseId=$('#caseid').val();
    var settings=getSettings('OpenCaseTrackerSystem');
    
    //console.log('SETTINGS');
    //console.log(settings);
    //console.log('Reloading');
    $.when(getCase(caseId)).done(function(caseDetails) {
        //console.log('Cases');
        casedata=caseDetails.results;
        //console.log(caseDetails);
        clearCaseForm();
        //console.log(caseDetails);
        if(caseDetails.count > 0) {
            if(caseDetails.results.is_closed==1) {
                //Case is closed, show appropriate options
                //console.log('Showing closed stamp');
                $('#closeCase').hide();
                $('#reopenCase').show();
                $('#closedStamp').show();
                //$('#closedStampDetails').html('<br /><b>'+caseDetails.results.resolution_name+'</b><br />Closed '+timestamp2date(caseDetails.results.date_closed)+' | '+caseDetails.results.closedby_real_name);
                $('#case_closed_details').show();
                $('#case_closed_date').html(timestamp2date(caseDetails.results.date_closed));
                $('#case_closed_name').html(caseDetails.results.closedby_real_name);
                $('#case_closed_comments').html(caseDetails.results.closure_comment);
                $('#case_closed_reason').html(caseDetails.results.resolution_name);
                
                //If this is an administrator, enable the "Delete case" button
                if(settings.administrator == 1) {
                    $('#deleteCase').removeClass('disabled');
                }
                
            } else {
                $('#closeCase').show();
                $('#reopenCase').hide();
                $('#closedStamp').hide();
                $('#case_closed_details').hide();
            }
            
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




            //Update Cookie (caseviews)
            var octStatus=getStatus();
            //octStatus.caseviews['#'+casedata.task_id] = {};

            if(!octStatus.caseviews) {
                delete octStatus.caseviews;
                octStatus.caseviews={};
            }
            let newProperty='case'+caseId;
            let newValue={
                    userid: $('#userid').val(),
                    caseid: casedata.task_id, 
                    title: casedata.item_summary, 
                    client: casedata.clientname, 
                    viewed: parseInt(Date.now()/1000),
                    lasttab: '#'+$('#case-tabs .nav-link.active').text().trim(),
            };

            //Save case in caseviews cookie
            let updatedCaseviews = {};
            for (let prop in octStatus.caseviews) {
              let encodedProp = encodeURIComponent(prop);
              let encodedValue = encodeURIComponent(JSON.stringify(octStatus.caseviews[prop]));
              updatedCaseviews[encodedProp] = JSON.parse(decodeURIComponent(encodedValue));
            }
            updatedCaseviews[encodeURIComponent(newProperty)] = newValue;
            octStatus.caseviews = updatedCaseviews;
            setStatus(octStatus);
            

            $('#caseid_header').html();
            //toggleCaseCards();
            $('#clientname').html(casedata.clientname+"<a class='fa-userlink' href='"+casedata.member+"'></a>");
            $('#itemsummary').html(casedata.item_summary);
            //console.log(thisDateDue);
            
            $('#date_due').val(thisDateDue);
            $('#date_due_parent').addClass(dateclass);
            
            if(casedata.is_restricted == 1) {
                $('#isrestricted_cover').removeClass('bg-light');
                $('#isrestricted_cover').addClass('bg-danger');
                $('#isrestricted_cover').attr('title', 'This case has restricted access to only administrators and the user it is assigned to');
                $('#isrestricted_image').attr('src', 'images/lock.svg');    
                $('#is_restricted').removeClass('bg-light');
                $('#is_restricted').addClass('bg-danger');
                $('#is_restricted').attr('title', 'This case has restricted access to only administrators and the user it is assigned to');
                $('#is_restricted_image').attr('src', 'images/lock.svg');    

            }
            
            if(casedata.children != null) {
                console.log('Has children');
                $('#isparent_cover').show();
            } 
            if(casedata.parent != null) {
                $('#isdependent_cover').show();
            }
            if(casedata.companion != null) {
                $('#iscompanion_cover').show();
            }
            
            $('#assignedto_cover').html(casedata.assigned_real_name);
            $("#casetype_cover").html(casedata.tasktype_name);
            if(casedata.line_manager) {
                $("#linemanager_cover").html(casedata.line_manager)
                if(casedata.line_manager_ph != '') {
                    $('#linemanager_cover').append(" ("+casedata.line_manager_ph+") <a class='fa-phone' href='tel:"+casedata.line_manager_ph+"'></a><a class='fa-chat' href='sms:"+casedata.line_manager_ph+"'></a>");
                }
            }
            $('#casegroup_cover').html(casedata.version_name);
            $("#department_cover").html(casedata.category_name);
            $("#unit_cover").html(casedata.unit);
            if(casedata.local_delegate) {
                $("#delegate_cover").html(casedata.local_delegate)
                if(casedata.local_delegate_ph!='') {
                    $('#delegate_cover').append(" ("+casedata.local_delegate_ph+") <a class='fa-phone' href='tel:'"+casedata.local_delegate_ph+"'></a><a class='fa-chat' href='sms:"+casedata.local_delegate_ph+"'></a>");
                }
            }
            
            $("#detaileddesc_cover").html(deWordify(casedata.detailed_desc).replace(/\n/g, "<br />"));
            $("#resolution_cover").html(deWordify(casedata.resolution_sought).replace(/\n/g, "<br />"));
            
            $("#dateopened_cover").html(dateOpened);
            $("#openedby_cover").html(casedata.openedby_real_name);
            
            //Now insert the custom fields
            if(typeof casedata.customlist != "undefined") {
                $.each(casedata.customlist, function(i, name) {
                    //console.log(name);
                    var type=$('#'+name+'_cover').prop('nodeName');
                    //console.log($('#'+name+'_cover').prop('nodeName'));
                    //console.log(casedata[name]);
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
            $('#edit_is_restricted').val(casedata.is_restricted); 
            if($('#edit_unit').is('input:text')) {
                $('#edit_unit').val(casedata.unit);
            } else {
                $('#edit_unit option:contains('+casedata.unit+')').attr('selected', 'selected');   
            }
            
            $('#case-cover-sheet').append('<input type="hidden" id="nocase" value="0" />')       
        } else {
            $('#case-cover-sheet').html('<div class="p-5 bg-light rounded m-5 text-center" style="height: 20%;">Case not available</div><input type="hidden" id="nocase" value="1" />');
            $('#case-tabs').hide();
        }
        
        
        //
            
    }).then(function() {
        toggleCaseCards();
        toggleDatepickers(); 
        var status=getStatus();
        if(typeof status.caseviews[caseId] !== "undefined") {
            //Get the last tab loaded & select it
            if(typeof status.caseviews[caseId]['lasttab'] !== "undefined") {
                var link=$('a[href="'+status.caseviews[caseId]['lasttab']+'"]');
                link.trigger('click');
            }

        }
    }).fail(function() {
        //console.log('Nothing found');
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

function toggleCloseCase() {
    //$('#closeCaseTitle').html(title);
    //$('#closeCaseMessage').html(message);
    $('#closeCaseWindow').modal('show');    
}

