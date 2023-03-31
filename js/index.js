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
/*
This file contains all the common javascript functions for OpenCaseTracker

*/
var userNames={};

/** Add a delay before running a function
* eg: delay(function() {
*       runThisFunction(value1, value2);
*     }, 2000);
*    runs the "runThisFunction" function with values value1 and value 2 after a 2000 millisecond dealy
* 
*/
var delay = (function() {
    var timer = 0;
    return function(callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$(function() {
    $(document).on('click', '.tabActionEdit', function() {
        //console.log('Editing ');
                                                                              
        var details=this.id.split("_");
        var edits=details[1].split(/([0-9]+)/);
        //console.log(edits);
        $(this).hide();
        $('#save_'+details[1]).show();
        $('#rightTabCol_'+details[1]).addClass("tab-being-edited");
        $('#rightTabCol_'+details[1]).append("<div class='hidden' id='cardbody_old_"+details[1]+"'>"+$('#cardbody_'+details[1]).html()+"</div>");
        $('#cardbody_'+details[1]).attr("contenteditable", "true");
        $('#cardbody_'+details[1]).focus();

    })
    $(document).on('click', '.tabActionSave', function() {
        console.log('Saving');
        
        var details=this.id.split("_");
        var edits=details[1].split(/([0-9]+)/);
        
        $.when(saveTabEdit(edits[0], edits[1])).done(function(output) {
            console.log('Database save of tab edits');
        })
        
        $(this).hide();
        $('#edit_'+details[1]).show();
        $('#rightTabCol_'+details[1]).removeClass("tab-being-edited");
        $('#cardbody_'+details[1]).attr("contenteditable", "false");
        
    })
    $(document).on('click', '.tabActionDelete', function() {
        console.log('Deleting');
        console.log(this.id);
        var details=this.id.split("_");
        var edits=details[1].split(/([0-9]+)/);
        console.log(details);
        console.log(edits);
        $.when(deleteTabEdit(edits[0], edits[1])).done(function(output) {
            console.log('Database delete of tab');    
        })
    
        
        
    })

    $.when(getUsers()).done(function(users) {
        
        $.each(users.results, function(i, x) {
            userNames[x.user_id]=x.real_name;
        })
    })
    
})

function gotoCase(caseId) {
    window.location.href="index.php?page=case&case="+caseId;        
}

function deleteTabEdit(method, id) {
    console.log('Deleting '+id+' using '+method);
    confirmMessage="Are you sure you want to delete this entry?";
    if(method!='history') {
        confirmMessage+="\r\n\r\n* Note that a record of the deletion will be stored in the case history.";
    } else {
        confirmMessage+="\r\n\r\n* Note that no record of this deletion will be kept.";
    }
    if(confirm(confirmMessage)) {
            
        switch(method) {
            case "poi":
                console.log('Deleting POI');
                var oldDescription=$('#tabPrimeBox_poi'+id).text()+': '+$('#cardbody_poi'+id).text();
                console.log('ID: '+id);
                $.when(poiLinkDelete(id)).done(function(results) {
                    historyCreate($('#caseid').val(), globals.user_id, "61", "poi", oldDescription, "");
                    //$('#tabCardParent_poi'+id).hide();
                    loadPois();
                    loadHistory();                    
                    
                })
                break;
            case "attachment":
                console.log('Deleting attachment');
                var oldDescription=$('#cardbody_attachment'+id).text();
                $.when(attachmentDelete(id)).done(function(results) {
                    console.log(results);
                    if(results.count == 0) {
                        alert('Unable to delete attachment. \r\n\r\nError given: '+results.results);
                    } else {
                        historyCreate($('#caseid').val(), globals.user_id, "8", id, null, oldDescription);
                        loadAttachments();
                        loadHistory();
                    }
                });
                break;
            case "comment":
                var oldComment=$('#cardbody_comment'+id).text();
                $.when(commentDelete(id)).done(function(output) {
                    historyCreate($('#caseid').val(), globals.user_id, "5", id, oldComment, null);
                    loadComments();
                    loadHistory();
                })
                break;
            case "history":
                $.when(historyDelete(id)).done(function(output) {
                    loadHistory();
                })
                break;
            case "master":
                console.log('Deleting MASTER!!');
                $.when(linkedDelete('master', id)).done(function(output) {
                    console.log('Done link delete');
                    console.log(output);
                    loadLinkeds();
                }).fail(function(output) {
                    console.log('Failed to delete link');
                    console.log(output);
                })
                break;
            case "companion":
                $.when(linkedDelete('companion', id)).done(function(output) {
                    loadLinkeds();
                    historyCreate($('#caseid').val(), globals.user_id, "27", id, null, null);
                    loadHistory();
                })
                break;
            case "notification":
                $.when(linkedDelete('notification', id)).done(function(output) {
                    loadNotifications();
                    historyCreate($('#caseid').val(), globals.user_id, "10", id, null, null);
                    loadHistory();
                })
        }
    }    
}

function saveTabEdit(method, id) {
    console.log('Saving '+id+' using '+method);
    switch(method) {
        case "poi":
            var oldComment=$('#cardbody_old_poi'+id).text();
            var newComment=$('#cardbody_poi'+id).text();
            if(oldComment.trim() != newComment.trim()) {
                $.when(poiUpdate(id, newComment)).done(function(output) {
                    historyCreate($('#caseid').val(), globals.user_id, "62", id, oldComment, newComment);
                    loadHistory();
                })
            }
            break;
        case "comment":
            var oldComment=$('#cardbody_old_comment'+id).text();
            var newComment=$('#cardbody_comment'+id).text()
            if(oldComment.trim() != newComment.trim()) {
                $.when(commentUpdate(id, newComment)).done(function(output) {
                    historyCreate($('#caseid').val(), globals.user_id, "71", id, oldComment, newComment);
                    loadHistory();
                })
            }
            break;
        case "attachment":
            var oldDescription=$('#cardbody_old_attachment'+id).text();
            var newDescription=$('#cardbody_attachment'+id).text();
            if(oldDescription.trim() != newDescription.trim()) {
                $.when(attachmentUpdate(id, newDescription)).done(function(output) {
                    historyCreate($('#caseid').val(), globals.user_id, "81", id, oldDescription, newDescription);
                    loadHistory();
                })
            }
            break;
    }
}

function caseList(parameters, conditions, order, first, last) {
    //console.log(parameters);
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {parameters: parameters, method: 'caseList', conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    }) 
    
}

function getCase(caseId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'case', caseId: caseId},
        dataType: 'json'
    })
}

/**
* Returns OpenCaseTracker Cookies
* 
* @param name - OpenCaseTrackerStatus or OpenCaseTrackerSystem
*/
function getSettings(name) {
    var cookiename = name + "="; //this is looking for an empty cookie name (?)
    //console.log(cookiename);
    var thisCookie=document.cookie;
    //console.log(thisCookie);
    var ca = thisCookie.split('; ');
    //sconsole.log(ca);
    //ca=decodeURIComponent(ca);
    //console.log('Cookie: '+name+' - size: '+ca.length);
    for(var i=0;i < ca.length;i++)
    {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1,c.length);
        }
        //console.log(c);
        if (c.indexOf(cookiename) == 0) {
            //console.log('Found '+cookiename);
            var output=decodeURIComponent(c).substring(cookiename.length,c.length);
            return JSON.parse(output);
        }
    }
    return null;
    
}

function getStatus() {
    //console.log('Reading status cookie');
    var cookiename = "OpenCaseTrackerStatus" + "=";
    var thisCookie=document.cookie;

    var ca = thisCookie.split('; ');

    var output=null;
    for(var i=0;i < ca.length;i++)
    {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(cookiename) == 0) {
            output=decodeURIComponent(c).substring(cookiename.length,c.length);
        }
    }
    var theend=JSON.parse(output);
    var deleteditems=false; 
    for(const key in theend.caseviews) {
        //console.log(key);
        if(key.substring(0,4) != 'case') {
            deleteditems=true;
            delete theend.caseviews[key];
        }
    }
    if(deleteditems) {
        //write the fixed cookie
        setStatus(theend);
    }
    return theend;
}

function getUsers(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'usersList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    })    
}

/** 
* Read the status into an object (getStatus()), then update values, then rewrite the status using setStatus
* 
* @param status    - object containing status values
*/
function setStatus(status) {
    //console.log('Setting status cookie');
    //console.log(status);
    //console.log('Stringified');
    //console.log(JSON.stringify(status));
    var cookiename = "OpenCaseTrackerStatus" + "=" + JSON.stringify(status)+"; SameSite=Strict; path=/";
    //console.log(cookiename);
    document.cookie=cookiename;
                                 
    //console.log("After setting, cookie looks like this");
    //console.log(document.cookie);
}




function accountUpdate(userId, newValues) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'saveAccount', userId: userId, newValues: newValues},
        dataType: 'json'
    })
}

function attachmentList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'attachmentList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function attachmentDelete(attachmentId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'attachmentDelete', attachmentId: attachmentId},
        dataType: 'json'
    })    
}

function attachmentUpdate(attachmentId, newValue) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'attachmentUpdate', attachmentId: attachmentId, newValue: newValue},
        dataType: 'json'
    })    
}

function caseCreate(newValues, user_id) {
    //console.log(newValues);
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'createCase', newValues: newValues, user_id: user_id},
        dataType: 'json'
    })    
}

function caseDelete(caseId) {
    var settings=getSettings('OpenCaseTrackerSystem');
    if(settings.administrator==1) {
        return $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: {method: 'caseDelete', caseId: caseId},
            dataType: 'json'
        });
    }
   
}

function caseUpdate(caseId, newValues) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'saveCase', caseId: caseId, newValues: newValues},
        dataType: 'json'
    })
}

function caseGroupCreate(versionName, listPosition, showInList, isEnquiry) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'caseGroupCreate', versionName: versionName, listPosition: listPosition, showInList: showInList, isEnquiry: isEnquiry},
        dataType: 'json'
    })    
}

function caseGroupDelete(versionId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'caseGroupDelete', versionId: versionId},
        dataType: 'json'
    })      
}

function caseGroupUpdate(groupId, fieldName, value) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'caseGroupUpdate', groupId: groupId, fieldName: fieldName, value: value},
        dataType: 'json'
    })    
}

function caseTypeUpdate(taskId, fieldName, value) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'caseTypeUpdate', taskId: taskId, fieldName: fieldName, value: value},
        dataType: 'json'
    })    
}

function caseTypeCreate(casetypeName, casetypeDescrip, listPosition, showInList) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'caseTypeCreate', casetypeName: casetypeName, casetypeDescrip: casetypeDescrip, listPosition: listPosition, showInList: showInList},
        dataType: 'json'
    })    
}

function caseTypeDelete(casetypeId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'caseTypeDelete', casetypeId: casetypeId},
        dataType: 'json'
    })      
}

function commentList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'commentList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function commentCreate(caseId, userId, comment, time) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'commentCreate', caseId: caseId, userId: userId, comment: comment, time: time},
        dataType: 'json'
    })    
    
}

function commentDelete(commentId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'commentDelete', commentId: commentId},
        dataType: 'json'
    })     
}

function commentUpdate(commentId, newValue) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'commentUpdate', commentId: commentId, newValue: newValue},
        dataType: 'json'
    })    
}

function customFieldUpdate(customFieldDefinitionId, fieldName, value) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'customFieldUpdate', customFieldDefinitionId: customFieldDefinitionId, fieldName: fieldName, value: value},
        dataType: 'json'
    })    
}

function customFieldCreate(customFieldName, customFieldType, customFieldVisible) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'customFieldCreate', customFieldName: customFieldName, customFieldType: customFieldType, customFieldVisible: customFieldVisible},
        dataType: 'json'
    })    
}

function customFieldDelete(customFieldDefinitionId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'customFieldDelete', customFieldDefinitionId: customFieldDefinitionId},
        dataType: 'json'
    })      
}

function customTextUpdate(customTextId, fieldName, value) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'customTextUpdate', customTextId: customTextId, fieldName: fieldName, value: value},
        dataType: 'json'
    })    
}

function customTextCreate(modifyAction, customText) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'customTextCreate', modifyAction: modifyAction, customText: customText},
        dataType: 'json'
    })    
}

function customTextDelete(customTextId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'customTextDelete', customTextId: customTextId},
        dataType: 'json'
    })      
}

function departmentCreate(departmentName, departmentDescrip, groupIn, listpos, showin) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'departmentCreate', departmentName: departmentName, departmentDescrip: departmentDescrip, groupIn: groupIn, listpos: listpos, showin: showin},
        dataType: 'json'
    }) 
}

function departmentDelete(departmentId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'departmentDelete', departmentId: departmentId},
        dataType: 'json'
    })      
}

function departmentUpdate(departmentId, fieldName, value) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'departmentUpdate', departmentId: departmentId, fieldName: fieldName, value: value},
        dataType: 'json'
    })    
}

function departmentNotificationsList(departmentId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'departmentNotificationsList', departmentId: departmentId},
        dataType: 'json'
    })
}

function departmentNotificationsCreate(departmentId, userId, notifyNew, notifyChange, notifyDel) {
     return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'departmentNotificationsCreate', departmentId: departmentId, userId: userId, notifyNew: notifyNew, notifyChange: notifyChange, notifyDel: notifyDel},
        dataType: 'json'
    })    
}

function departmentNotificationsDelete(departmentId, userId) {
     return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'departmentNotificationsDelete', departmentId: departmentId, userId: userId},
        dataType: 'json'
    })    
}

function departmentNotificationsUpdate(departmentId, userId, name, value) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'departmentNotificationsUpdate', departmentId: departmentId, userId: userId, name: name, value: value},
        dataType: 'json'
    })    
}

function getLastComment(caseid) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'getLastComment', caseid: caseid},
        dataType: 'json'
    });    
}

function groupCreate(groupName) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'groupCreate', groupName: groupName},
        dataType: 'json'
    })     
}

function groupDelete(groupId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'groupDelete', groupId: groupId},
        dataType: 'json'
    })       
}

function groupUpdate(groupId, fieldName, newValue) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'groupUpdate', groupId: groupId, fieldName: fieldName, newValue: newValue},
        dataType: 'json'
    })    
}

function historyList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'historyList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function historyCreate(taskId, userId, eventType, fieldChanged, oldValue, newValue) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'historyCreate', taskId: taskId, userId: userId, eventType: eventType, fieldChanged: fieldChanged, oldValue: oldValue, newValue: newValue},
        dataType: 'json'
    })
    /* EVENT TYPES
            0=>"Field Changed",
            1=>"Case Opened", 2=>"Case Closed", 3=>"Case Edited"
            4=>"Note Added", 5=>"Note Deleted", 6=>"Note Deleted", 61=>"Note changed",
            7=>"Attachment Added", 8=>"Attachment Deleted", 
            9=>"Notification Added", 10=>"Notification Deleted",
            11=>"Related Case Added", 12=>"Related Case Deleted",
            13=>"Case Reopened", 14=>"Case Assigned",
            15=>"Case Related to Other Case", 16=>"Case unRelated to Other Case",
            17=>"Reminder Added", 18=>"Reminder Deleted",
            19=>"Linked Child Case Added", 20=>"Linked Child Case Deleted",
            21=>"Case made a linked child Case", 22=>"Case no longer a linked child Case",
            23=>"Linked parent status removed",
            24=>"Planning Note deleted", 25=>"Planning note marked as read",
            26=>"Companion task added",  27=>"Companion task removed",
            28=>"Task made companion of other case", 29=>"Task removed as companion of other case",
            30=>"Acknowledgement of checklist item"           
    */
    //INSERT INTO history task_id, user_id, event_date [unix], event_type, field_changed, old_value, new_value    
    
}

function historyDelete(historyId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'historyDelete', historyId: historyId},
        dataType: 'json'
    }) 
}

function linkedCreate(linkType, taskId, linkedId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'linkedCreate', linkType: linkType, taskId: taskId, linkedId: linkedId},
        dataType: 'json'
    });    
}

function linkedDelete(linkType, id) {
    console.log(linkType, id);
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'linkedDelete', linkType: linkType, id: id},
        dataType: 'json'
    })    
}

function linkedList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'linkedList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function notificationsList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'notificationsList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });    
}

function notificationCreate(caseId, userId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'notificationCreate', caseId: caseId, userId: userId},
        dataType: 'json'
    });      
}

function notificationDelete(notifyId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'notificationDelete', notifyId: notifyId},
        dataType: 'json'
    });    
}

function poiConnectionsList(personId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'poiConnectionsList', personId: personId},
        dataType: 'json'
    });    
}

function poiLinkCreate(caseId, poiId, comment) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'poiLinkCreate', caseId: caseId, poiId: poiId, comment: comment},
        dataType: 'json'
    });   
}

function poiLinkDelete(poiId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'poiLinkDelete', poiId: poiId},
        dataType: 'json'
    });     
}

function poiList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'poiList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function poiPersonUpdate(personId, fieldName, value) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'poiPersonUpdate', personId: personId, fieldName: fieldName, value: value},
        dataType: 'json'
    })    
}

function poiPersonCreate(firstname, lastname, position, organisation, phone, email) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'poiPersonCreate', firstname: firstname, lastname: lastname, position: position, organisation: organisation, phone: phone, email: email},
        dataType: 'json'
    })    
}

function poiPersonDelete(personId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'poiPersonDelete', personId: personId},
        dataType: 'json'
    })      
}

function poiPersonLookup(value) {
    var parameters={};
    parameters[':nameValue']='%'+value+'%';
    var conditions="CONCAT(firstname, ' ', lastname) LIKE :nameValue"; 
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'poiPeopleList', parameters: parameters, conditions: conditions },
        dataType: 'json'
    })    
}

function poiCreate() {
    
}

function poiUpdate(poiId, newValue) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'poiUpdate', poiId: poiId, newValue: newValue},
        dataType: 'json'
    })    
}

function poiDelete() {
    
}

function recentList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'recentList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function relatedList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'relatedList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function relatedCreate(caseId, userId, relatedId, time) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'relatedCreate', caseId: caseId, userId: userId, relatedId: relatedId, time: time},
        dataType: 'json'
    })    
}

function resolutionUpdate(resolutionId, fieldName, value) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'resolutionUpdate', resolutionId: resolutionId, fieldName: fieldName, value: value},
        dataType: 'json'
    })    
}

function resolutionCreate(resolutionName, resolutionDescrip, listPosition, showInList) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'resolutionCreate', resolutionName: resolutionName, resolutionDescrip: resolutionDescrip, listPosition: listPosition, showInList: showInList},
        dataType: 'json'
    })    
}

function resolutionDelete(resolutionId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'resolutionDelete', resolutionId: resolutionId},
        dataType: 'json'
    })      
}

function restrictVersionList(groupId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'restrictVersionList', groupId: groupId},
        dataType: 'json'
    })
}

function restrictVersionUpdate(groupId, versionId, newValue) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'restrictVersionUpdate', groupId: groupId, versionId: versionId, newValue: newValue},
        dataType: 'json'
    })    
}

function statsCases(parameters, conditions, order, first, last, select) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'statsCases', parameters: parameters, conditions: conditions, order: order, first: first, last: last, select: select},
        dataType: 'json'
    });
}

function strategyList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'strategyList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function systemSettingsCreate(values) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'systemSettingsCreate', values: values},
        dataType: 'json'
    })
}

function systemSettingsUpdate(values, wheres) {
    //console.log('Values');
    //console.log(values);
    //console.log('Wheres: '+wheres);
    return $.ajax({
        url:'ajax.php',
        method: 'POST',
        data: {method: 'systemSettingsUpdate', values: values, wheres: wheres},
        dataType: 'json'
    })
}

function tableList(tablename, joins, select, parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {tablename: tablename, joins: joins, select: select, parameters: parameters, method: 'tableList', conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    })
}

function timeList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'timeList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function unitCreate(unitDescrip, listPosition, showInList, parentId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'unitCreate', unitDescrip: unitDescrip, listPosition: listPosition, showInList: showInList, parentId: parentId},
        dataType: 'json'
    })    
}

function unitDelete(unitId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'unitDelete', unitId: unitId},
        dataType: 'json'
    })      
}

function unitUpdate(unitId, fieldName, value) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'unitUpdate', unitId: unitId, fieldName: fieldName, value: value},
        dataType: 'json'
    })    
}

function unitList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'unitList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}






function toggleCaseCards() {
    $('.case-link').each(function(i, obj) {
        var caseid=$(this).html();
        var plaintext=$(this).text();
        //console.log('HASH COntents: '+plaintext);
        if(!plaintext.includes("#")) {
            $(this).html("<a href='index.php?page=case&case="+caseid+"'>#"+caseid+"</a>");
        }
        //console.log(caseid);
    })    
}

function toggleDatepickers() {
    //console.log('toggling');
    $('.datepicker').each(function() {
        //console.log(this.id);
        //console.log($(this)[0].classList);
        var classes=$(this)[0].classList;
        var classString=Array.from(classes).join(" ");
        if(!$(this).hasClass('hasDatepicker') || classString.includes("hasDatepicker")) {
            var fontSize=parseInt($(this).css("font-size"));
            $(this).css("width", (($(this).val().length+1)*(fontSize/2))+'px');
            var height=parseInt($(this).css("height"));
            $(this).css("height", (height-2)+'px');
            $(this).datepicker({
                dateFormat: "dd/mm/yy", 
                changeMonth: true, 
                changeYear: true, 
                onSelect: function(dateText, inst) {
                    if (this.id.includes('date_due')) {
                        if(confirm("Update the due date for this case?")) {
                            if(this.id.includes('caselist')) {
                                var newDate=date2timestamp($(this).val());
                                var lastIndex=inst.id.lastIndexOf("_");
                                var caseId=inst.id.substring(lastIndex+1);
                                var newValues={};
                                newValues['date_due']=newDate;
                                $.when(caseUpdate(caseId, newValues)).done(function(changes) {
                                    var oldDate=timestamp2date(changes.date_due['old']);
                                    var newDate=timestamp2date(changes.date_due['new']);
                                    historyCreate(caseId, globals.user_id, '40', 'date_due', oldDate, newDate);
                                });                            
                            } else if(this.id='date_due') {
                                var newDate=date2timestamp($(this).val());
                                var caseId=$('#caseid').val();
                                var newValues={};
                                newValues['date_due']=newDate;
                                $.when(caseUpdate(caseId, newValues)).done(function(changes) {
                                    var oldDate=timestamp2date(changes.date_due['old']);
                                    var newDate=timestamp2date(changes.date_due['new']);
                                    historyCreate(caseId, globals.user_id, '40', 'date_due', oldDate, newDate);
                                    loadHistory();    
                                });
                            }                            
                        }                            
                    }

                },
            });
        } else {
            //console.log('Doesnt have date picker class')
        }
        
    })    
}


function loadAttachment(origname, filename, filetype) {
    var attachmentDir=$('#attachments_dir').val();
    $.when(readFile(attachmentDir+filename)).done(function(contents) {
        //console.log(contents);
        if(contents.length > 0) {
            var data=encodeURI('data:'+filetype+';charset=utf-8,'+contents);
            
            var link = document.createElement("a");
            link.setAttribute("href", data);
            link.setAttribute("download", origname);
            document.body.appendChild(link); // Required for FF

            link.click();    
        } else {
            alert('No file found');
        }
    });
}

function readFile(filename) {
    //console.log(filename);
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'readfile', filename: filename},
        dataType: 'binary'
    }).fail(function(xhr, status, error) {
        //console.log('Failed to open '+filename+' ---> '+status+': '+error);
    })
}

function saveFile(filename, contents) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'savefile', filename: filename, contents: contents},
        dataType: 'json'
    })    
}

/**
* Exports a div containing data list with div attributes as fields
* 
* @param divId
*/
function exportDivWithAttributes(divId, excludes) {
    var csvExport="";
    var csvFirstLine="";
    var csvRows="";
    if(!excludes) {
        var excludes=new Array('class', 'style', 'id', 'holdername');
    }
    
    $.each($('#'+divId).children('div'), function(x, thisDiv) {
        $.each(thisDiv.attributes, function(i, attribs) {
            if(!excludes.includes(attribs.name)) {
                if(x==0) {
                    //Header Row
                    csvFirstLine += '"'+attribs.name+'",';
                }
                if($(thisDiv).is(":visible")) {
                    csvRows += '"'+attribs.value+'",';
                }
            }
        })
        if(x==0) {
            //strip comma from end of firstline
            csvFirstLine=csvFirstLine.substring(0, csvFirstLine.length-1);
        }
        //strip comma from end of csvrow
        if($(thisDiv).is(":visible")) {
            csvRows=csvRows.substring(0, csvRows.length-1);
            csvRows += "\n";
        }
    })
    
    csvExport=csvFirstLine+"\n"+csvRows;
    
    var data=encodeURI('data:text/csv;charset=utf-8,'+csvExport);
    
    var link = document.createElement("a");
    link.setAttribute("href", data);
    link.setAttribute("download", "export.csv");
    document.body.appendChild(link); // Required for FF

    link.click();

    //console.log(csvExport);        
}

/**
* A function to remove weird characters (UTF8 style) from strings that arrive from Word.
* 
* @param string
*/
function deWordify(string) {
    if(string != null) {
        output=string.replace(/\r\n/g, "<br />").replace(/\u00e2\u20ac\u2122/g,"'").replace(/\u00e2\u20ac\u201c/g, "-").replace(/\u00e2\u20ac\u0153/g, '"').replace(/\u00c2/g, "").replace(/\u00e2\u20ac/g, '"').replace(/\\'/g, "'");
    } else {
        output='';
    }
    return output;
}

function minutes2hours(minutes) {
    var thours= parseInt(minutes/60);
    var tminutes=parseInt(minutes)-(thours*60);
    var niceTime=thours+':'+tminutes.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
    return niceTime;
}

/**
* Reformat a timestamp into a nice human readable date
* 
* @param timestamp
* @param {String} format: d/m/y, y/m/d, dd/mm/yy, dd/mm/yy hh:ii, dd/mm/yy g:i a, dd MM, dd MMM, dd MMM YYYY
*/
function timestamp2date(timestamp, format) {
    
    var monthNamesShort=["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var monthNamesLong=["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    if(!format) format='dd/mm/yy';
    
    var date=new Date(timestamp * 1000);
    var year=date.getFullYear();
    var month = date.getMonth()+1;
    var monthSearch=month-1;
    var day = date.getDate();
    var hours=date.getHours();
    var hours12=hours;
    var hoursMeridian='AM';
    if(hours12 > 12) {
        hours12=hours12-12;
        hoursMeridian='PM';
    }
    if(hours12==0) hours12=12;
    if(date.getMinutes() > 9) {
        var minutes=date.getMinutes();
    } else {
        var minutes="0"+date.getMinutes();
    }
    if(date.getSeconds() > 9) {
        var seconds=date.getSeconds();
    } else {
        var seconds="0"+date.getSeconds();
    }    
    
    if(format=='d/m/y') var time=day+'/'+month+'/'+year;
    if(format=='y/m/d') var time=year+'/'+month+'/'+day;
    if(format=='dd/mm/yy') var time=pad(day, 2)+'/'+pad(month, 2)+'/'+year;
    if(format=='yy/mm/dd') var time=year+'/'+pad(month, 2)+'/'+pad(day, 2);
    if(format=='yy-mm-dd') var time=year+'-'+pad(month, 2)+'-'+pad(day, 2);
    if(format=='dd/mm/yy hh:ii') var time=pad(day, 2)+'/'+pad(month, 2)+'/'+year+' '+hours+':'+minutes;
    if(format=='dd/mm/yy g:i a') var time=pad(day, 2)+'/'+pad(month, 2)+'/'+year+' '+hours12+':'+minutes+' '+hoursMeridian;
    if(format=='yy/mm/dd g:i a') var time=year+'/'+pad(month, 2)+'/'+pad(day, 2)+' '+hours12+':'+minutes+' '+hoursMeridian;
    if(format=='g:ia dd MM yy') var time=hours12+':'+minutes+hoursMeridian+' '+day+' '+monthNamesShort[monthSearch]+' '+year;
    if(format=='dd MM') var time=day+' '+monthNamesShort[monthSearch];
    if(format=='dd MMM') var time=day+' '+monthNamesLong[monthSearch];
    if(format=='dd MM YY') var time=day+' '+monthNamesShort[monthSearch]+' '+year;
    if(format=='dd MMM YYYY') var time=day+' '+monthNamesLong[monthSearch]+' '+year;;
    
    return time;
    
}

function countProperties(obj) {
    var count=0;
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop)) {
            ++count;
        }
    }
    return count;
}

function saveCaseView(caseId, timestamp, lasttab) {
    var status=getStatus();
    //console.log(status);
    var queryString='';
    if(status.caseviews == undefined) {
        status['caseviews']={};
    }
    if(status.caseviews[caseId]==undefined) {
        status.caseviews[caseId]={};
    }
    cookiename='case'+caseId;
    status.caseviews[cookiename]['timestamp']=timestamp;
    status.caseviews[cookiename]['lasttab']=lasttab;
    status.caseviews[cookiename]['caseid']=caseId;
    console.log(status);
    //status.caseviews={};
    //count how many statuses there are and trim if there are more than 25
    var count=countProperties(status.caseviews);
    if(count > 10) {
        //Trim oldest items
        var sortable=[];
        for (var timestamp in status.caseviews) {
            sortable.push(status.caseviews[timestamp]);
        }
        sortable.sort(function(a, b) {
            return a[1] - b[1];
        })
        
        status.caseviews={}; //Clear the object
        
        //Iterate through sortable and only leave the first 50
        $.each(sortable, function(i, me) {
            if(i < 10) {
                status.caseviews[me.caseid]=me;
            }
        })       
    }
    
    setStatus(status);
}



function date2timestamp(strDate) {
    myDate=strDate.split("/");
    strDate=new Date(parseInt(myDate[2], 10), parseInt(myDate[1], 10) - 1 , parseInt(myDate[0]), 10).getTime();
    return strDate/1000;
}

function pad(n, width, z) {
  z = z || '0';
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

function getInitials(string) {
    var matches = string.match(/\b(\w)/g);
    var initials=matches.join('');
    return initials;
}

function savePagerOrder(pagerName, orderField, orderMethod) {
    var status = getStatus();
    //console.log(status);
    
    if(status.orders==undefined) {
        status['orders']={};
    }
    
    if(status.orders[pagerName]==undefined) {
        status.orders[pagerName]={};
    }
    status.orders[pagerName][orderField]=orderMethod;
    //status.orders[pagerName]['method']=orderMethod;
    
    setStatus(status);
}

function clearPagerOrder(pagerName, orderField) {
    var status=getStatus();
    //console.log('This id: '+$(this).attr('id'));
    //console.log(status);
    //console.log('Deleting '+orderField+' from '+pagerName);
    if(status.orders[pagerName]==undefined) {
        //Nothing to do here
    } else {
        if(!orderField) {
            delete status.orders[pagerName];
            $('#pager_name_'+pagerName).find('.fieldcheckmark').remove();
            $('#pager_name_'+pagerName).find('.methodcheckmark').remove();
            $('#pager_name_'+pagerName).find('.methodchecked').removeClass('methodchecked');
            $('#pager_name_'+pagerName).find('.fieldchecked').removeClass('fieldchecked');
        } else {
            delete status.orders[pagerName][orderField];
        }
    }
    //console.log(status);
    setStatus(status);
}

function loadPagerOrder(pagerName) {
    var status=getStatus();
    //console.log(status);
    
    if(status.orders==undefined) {
        status['orders']={};
    }
    
    if(status.orders[pagerName]!==undefined) {
        //status.orders[pagerName]={};
        const orders=status.orders[pagerName];
        //console.log('There ARE order settings for '+pagerName);
        for(const key in orders) {
            //console.log(`${key}: ${orders[key]}`);
            addPagerOrder(pagerName, key, orders[key]);
        }
    } else {
        //console.log('No order settings for '+pagerName);
    }   
}

function addPagerOrder(pagerName, orderField, orderMethod) {
    //console.log('Adding order setting to '+pagerName+' for '+orderField+' by '+orderMethod);
    //$('#'+pagerName+'_order').append('<div class="border rounded float-left smaller mt-1">'+orderField+'</div>');
    //clearPagerOrder(pagerName, 'date_closed');
    //Tick the item selected
    var parentItem=pagerName+'-order-field-'+orderField;
    var childItem=pagerName+'-order-field-'+orderField+'-order-method-'+orderMethod;
    
    //First check if the parent has been selected already, because if it has, we need to unselect the child options
    if($('#'+parentItem).hasClass('fieldchecked')) {
        //console.log('This item already has an order set on it');
        if($('#'+childItem).hasClass('methodchecked')) {
            //console.log('This item already has an order method set on it');
            //IN THIS CASE - REMOVE THE ORDER
            $('#'+childItem).find('.methodcheckmark').remove();
            $('#'+childItem).removeClass('methodchecked');
            $('#'+parentItem).find('.fieldcheckmark').remove();
            $('#'+parentItem).removeClass('fieldchecked');
            clearPagerOrder(pagerName, orderField);
            
        } else {
            //IN THIS CASE - WE ARE CHANGING THE ORDER METHOD
            $('#'+parentItem).find('.methodcheckmark').remove();
            $('#'+parentItem).find('.methodchecked').removeClass('methodchecked');
            $('#'+childItem).prepend('<img src="images/checkmark.svg" width="8px" class="mb-1 mr-1 methodcheckmark" />');
            $('#'+childItem).addClass('methodchecked');
            savePagerOrder(pagerName, orderField, orderMethod);
                        
        }
    } else {
        $('#'+parentItem).prepend('<img src="images/checkmark.svg" width="10px" class="mb-1 fieldcheckmark" />');
        $('#'+parentItem).addClass('fieldchecked');
        //console.log('Ticking item in '+childItem);
        $('#'+childItem).prepend('<img src="images/checkmark.svg" width="8px" class="mb-1 mr-1 methodcheckmark" />');
        $('#'+childItem).addClass('methodchecked');  
        
        savePagerOrder(pagerName, orderField, orderMethod);      
    }
    
    
}


/**
* Updates the pager records for the specified pager
* 
* @param pagername
* @param {Number} start - the first record shown
* @param {Number} end - the last record shown
* @param total - total shown
*/
function pagerNumbers(pagername, start, end, total) {
        //console.log('Pager numbers function');
        var displayEnd=end;
        var displayStart=start;
        //console.log(start+'->'+end)+1;
        var qty=parseInt(end)-parseInt(start)+1;
        //console.log('Calculated quantity as '+qty+' (from '+end+' less '+start+')');
        
        //var start=start+1;
        if(displayEnd > parseInt(total)) displayEnd=parseInt(total);
        displayStart++;   //The method we use to search the database assumes that the first record is record zero, however
        displayEnd++;     // people think of the first record as record one. So we "display" the number one higher than we use to search
        if(displayEnd > total) displayEnd=total;
        //console.log('Results found - '+displayEnd);
        if(total==0) {
            qty=0;
            start=0;
            end=0;
        }
        
        $('#'+pagername+'start').attr("value", start);
        $('#'+pagername+'end').attr("value", end);
        $('#'+pagername+'position').html((displayStart)+'-'+(displayEnd));
        $('#'+pagername+'total').html('of '+total+' cases');  
        $('#'+pagername+'count').val(total);
        //$('#'+pagername+'qty').html('Showing <span" id="'+pagername+'Quantity" value="'+qty+'" />'+qty+'</span>');  
        $('#'+pagername+'qty').val(qty);
        
        //Now lets disable the "next" and "end" button if we're already showing the last record in a set
        // or disable the "prev" and "start" buttons if we are at the beginning.
        $('#'+pagername+'first').removeAttr("disabled");
        $('#'+pagername+'last').removeAttr("disabled");
        $('#'+pagername+'start').removeAttr("disabled");
        $('#'+pagername+'end').removeAttr("disabled");
        //console.log('DISPLAYING CHECKS');
        //console.log('Displaystart: '+displayStart+', Displayend: '+displayEnd+', Total: '+total);
        if(displayStart==1) {
            $('#'+pagername+'first').attr("disabled", 1);
            $('#'+pagername+'start').attr("disabled", 1);
        }
        if(displayEnd==total) {
            $('#'+pagername+'last').attr("disabled", 1);
            $('#'+pagername+'end').attr("disabled", 1);
        }
        
        
        //Save the pager details to the cookie
        savePagerSettings(pagername, start, end, qty);
}

function savePagerSettings(pagername, start, end, qty) {
    var status=getStatus();
    var queryString='';
    //console.log(status);
    if(status.pagers == undefined) {
        status['pagers']={};
    }
    if(status.pagers[pagername]==undefined) {
        status.pagers[pagername]={};
    }    
    status.pagers[pagername]['start']=start;
    status.pagers[pagername]['end']=end;
    status.pagers[pagername]['qty']=qty;
    //console.log(status);
    setStatus(status);    
}

/**
* Checks the cookie settings for a specific pager and returns the last used values.
* If no cookie value exists, returns defaults of "start = 0", "end=9", "qty=10"
* 
* @param pagername - the name of the pager
* 
* @returns {Object} output['start'], output['end'], output['qty']
*/
function pagerNumberSettings(pagername) {
    var status=getStatus();
    if(status.pagers!=undefined) {
        if(status.pagers[pagername]!=undefined) {
            output=status.pagers[pagername];
        } else {
            output={};
            output['start']=1;
            output['end']=10;
            output['qty']=9;
        }
    } else {
            output={};
            output['start']=0;
            output['end']=9;
            output['qty']=10;
    }
    return output;
}

function showMessage(title, message) {
    $('#messageCentreTitle').html(title);
    $('#messageCentreMessage').html(message);
    $('#messageCentre').modal('show');
};

function showCase(caseId) {
    window.location.href = "index.php?page=case&case=" + caseId;    
}

/**
* Creates the html code to display a card for a case in a list and insert that card into a parent div
* 
* @param parentDiv - the name of the parent Div into which the case card will be added
* @param uniqueId - the unique ID to use when referencing this particular case card
* @param casedata - an object containing all the data used to fill out the case card. This includes clientname, member, date_due, last_edited_real_name, assigned_real_name, task_id, member_status
*/
function insertCaseCard(parentDiv, uniqueId, casedata) {
    //console.log('Inserting Case Card');
    //console.log(casedata);
    var thisDateDue=timestamp2date(casedata.date_due);
    var caseId=uniqueId.substr(parentDiv.length);
    
    
    //Gather the client name
    if(typeof casedata.clientname !== 'undefined' && casedata.clientname !== null) {
        var client=casedata.clientname;
    } else {
        if(typeof casedata.member !== 'undefined' && casedata.member !== null) {
            var client=casedata.member;
        } else {
            if(typeof casedata.name !== 'undefined' && casedata.name !== null) {
                var client=casedata.name;
            } else {
                var client='None';
            }
        }
    }
    var dateclass='date-future';
    if(casedata.date_due < $('#today_start').val()) {dateclass='date-overdue';}
    if(casedata.date_due >= $('#today_start').val() && casedata.date_due <= $('#today_end').val()) {dateclass='date-due';}
    
    var lasteditedby=(casedata.last_edited_real_name) ? casedata.last_edited_real_name : "Unknown";
    var assignedto=(casedata.assigned_real_name) ? casedata.assigned_real_name : 'Unassigned';
    //console.log(parentDiv+' for case '+casedata.task_id+': Date Due: '+casedata.date_due+' - today_start: '+$('#today_start').val());
    
    $('#'+parentDiv).append('<div class="row m-1" id="caseCardParent_'+uniqueId+'" onDblClick="gotoCase(\''+caseId+'\')"></div>');
        
    //The "leftCaseCol_" div contains the Case Number
    $('#caseCardParent_'+uniqueId).append('<div class="float-left p-0 col m-0" style="min-width: 56px; max-width: 65px;" id="leftCaseCol_'+uniqueId+'"></div>');
    
    //The "rightCaseCol_" div contains the case details
    $('#caseCardParent_'+uniqueId).append('<div class="float-left col p-0 " style="border-top: 1px solid #6ab446" id="rightCaseCol_'+uniqueId+'"></div>');
    
        //The "casePrimeBox" div contains the case number
        $('#leftCaseCol_'+uniqueId).append('<div id="casePrimeBox_'+uniqueId+'" class="casePrimeBox text-center case-link h-100" onClick="gotoCase(\''+caseId+'\')"></div>');
        //The "caseMain_" div doesn't contain anything and can probably be removed
        //$('#rightCaseCol_'+uniqueId).append('<div id="caseMain_'+uniqueId+'" class="card-body p-0"></div>');
    
        //The "caseheader_" div contains data lists
        $('#rightCaseCol_'+uniqueId).append('<div class="flex-nowrap p-0 m-1 mb-0 hidden text-muted green-curtain smaller" style="top: 2px" id="caseheadermessage_'+uniqueId+'"></div>');
        $('#rightCaseCol_'+uniqueId).append('<div class="row flex-nowrap card-header smallish p-2 ml-1" id="caseheader_'+uniqueId+'"></div>');
        $('#rightCaseCol_'+uniqueId).append('<div class="row flex-nowrap smallish p-2 ml-1" id="casesummary_'+uniqueId+'"></div>');
        
        //The "casedetails_" div contains the detailed description of the case & only shows if selected
        $('#rightCaseCol_'+uniqueId).append('<div class="card-body collapse p-1 m-1 border rounded" id="casedetails_'+uniqueId+'"></div>');
        //The empty div for displaying most recent comment:
        $('#rightCaseCol_'+uniqueId).append('<div class="card-body collapse p-0 m-1 pl-1 pr-1 casecomment border rounded " id="casecomment_'+uniqueId+'"></div>');
        //$('#rightCaseCol_case'+casedata.task_id).append('<div class="card-footer small font-italic pt-1 pb-1 text-muted" id="casefooter_'+casedata.task_id+'"></div>');
        
        
        
        
        
        //Now insert information into each of the divs, starting with the casePrimeBox
        $('#casePrimeBox_'+uniqueId).append(casedata.task_id);
    
        //Right Col
        //Client/Member name field
        $('#caseheader_'+uniqueId).append("<div class='col-2 d-xl-block d-lg-block d-md-block d-none d-sm-none d-xs-none border rounded pl-1 pr-1 mr-1 client-link userlink-"+casedata.member_status+" overflow-hidden' title='"+client+"'>"+client+"<a class='fa-userlink' href=''></a></div>");
        $('#caseheader_'+uniqueId).append("<div class='col-2 d-xs-block d-lg-none d-md-none d-sm-block d-xs-block border rounded pl-1 pr-1 mr-1 client-link userlink-"+casedata.member_status+" overflow-hidden' title='"+client+"'>"+getInitials(client)+"<a class='fa-userlink' href=''></a></div>");
        
        //Department field
        $('#caseheader_'+uniqueId).append("<div class='col-3 caselist-department border rounded pl-1 pr-1 mr-1 overflow-hidden' title='"+casedata.category_name+"'>"+casedata.category_name+"</div>");

        //Case type field
        $('#caseheader_'+uniqueId).append("<div class='col-3 caselist-casetype border rounded pl-1 pr-1 mr-1 overflow-hidden' title='"+casedata.tasktype_name+"'>"+casedata.tasktype_name+"</div>");
        
        //Assigned to field
        $('#caseheader_'+uniqueId).append("<div class='col-2 d-xl-block d-lg-block d-md-block d-none d-sm-none d-xs-none officer border rounded pl-1 pr-1 mr-1 overflow-hidden' id='officer_"+casedata.assigned_to+"' title='"+assignedto+"'>"+assignedto+"</div>");
        $('#caseheader_'+uniqueId).append("<div class='col-2 d-xs-block d-lg-none d-md-none d-none d-sm-block d-xs-block officer border rounded pl-1 pr-1 mr-1 overflow-hidden' id='officer_"+casedata.assigned_to+"' title='"+assignedto+"'>"+getInitials(assignedto)+"</div>");
                        
        //Date due field
        $('#caseheader_'+uniqueId).append("<div class='col text-center mr-2 border rounded pl-1 pr-1 calendar-div pointer flex-nowrap "+dateclass+"'><input type='text' id='caselist_date_due_"+casedata.task_id+"' class='datepicker' value='"+thisDateDue+"' /></div>");
        //$('#caseheader_'+uniqueId).append("<div class='d-sm-block d-xs-block d-md-block officer float-right m-0 mb-1 mr-1 border rounded pl-1 pr-1' id='miniofficer_"+casedata.assigned_to+"'>"+getInitials(assignedto)+"</div>");
        
        
        //$('#caseheader_'+uniqueId).append("<div style='clear: both'></div>");
        


        //Item summary
        $('#casesummary_'+uniqueId).append("<div class='col-12 p-0 display-7 smallish'><a data-toggle='collapse' href='#case-card' aria-expanded='true' aria-controls='case-card' id='toggle-case-card_"+uniqueId+"' onClick='toggleDetails(\""+uniqueId+"\")' ><img id='toggledetails_"+uniqueId+"' src='images/folder.svg' class='img-thumbnail float-left mr-0 mt-1 toggledetails pale-green-link' width='20px' title='Show case details' /></a><a data-toggle='collapse' href='#comment-card' aria-expanded='true' aria-controls='comment-card' id='toggle-comment-card_"+uniqueId+"' onClick='toggleLastComment(\""+uniqueId+"\", \""+caseId+"\")' ><img id='togglecomments_"+uniqueId+"' src='images/message.svg' class='img-thumbnail float-left mr-2 mt-1 togglecomments pale-green-link' width='20px' title='Show most recent note' /></a><span class='caselist-itemsummary' id='lastComment_"+uniqueId+"' onClick='toggleLastComment(\""+uniqueId+"\")'></span><span class='caselist-itemsummary' onClick='toggleDetails(\""+uniqueId+"\")'>"+casedata.item_summary+"</span></div>");

        //Comment toggle
        //$('#caseheader_'+uniqueId).append("<div class='float-left p-0 display-7'></div>");

        //$('#caseheader_'+uniqueId).append("<div style='clear: both'></div>");

        //Case description
        $('#casedetails_'+uniqueId).append("<p class='card-text smallish overflow-auto' style='max-height: 100px'>"+deWordify(casedata.detailed_desc)+"</p>");
        $('#casedetails_'+uniqueId).append("<div class='d-xs-block d-sm-block d-md-none d-lg-none d-xl-none officer float-right m-0 mb-1 border rounded pl-1 pr-1 smallish' id='officer_"+casedata.assigned_to+"'>"+assignedto+"</div>");

        //Comment space
        $('#casedetails_'+uniqueId).append("<p class='card-text smaller overflow-auto' style='max-height: 100px'></p>");
        
        
        //console.log(casedata.is_closed);
        if(casedata.is_closed==1) {
            //console.log('Stamping closed');
            $('#leftCaseCol_'+uniqueId).append('<div class="stamp stamp-red-double stamp-tiny">Closed</div>');    
        }
        
}

function insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content) {
    
    //Parent Tab
    $('#'+parentDiv).append('<div class="row m-2" id="tabCardParent_'+uniqueId+'"></div>');
    
    //Children of parent
    $('#tabCardParent_'+uniqueId).append('<div class="col-2 float-left pl-0" id="leftTabCol_'+uniqueId+'"></div>');
    $('#tabCardParent_'+uniqueId).append('<div class="col-10 float-left tabSpeechBubble" id="rightTabCol_'+uniqueId+'"></div>');
    
    //Children of columns
    $('#leftTabCol_'+uniqueId).append('<div class="tabPrimeBox text-center" id="tabPrimeBox_'+uniqueId+'"></div>');
    $('#leftTabCol_'+uniqueId).append('<div class="smaller text-center" id="tabDate_'+uniqueId+'"></div>');
    $('#leftTabCol_'+uniqueId).append('<div class="text-right" id="tabActions_'+uniqueId+'"></div>');
    
    if(header !== null) {
        $('#rightTabCol_'+uniqueId).append('<div class="card-header" id="cardheader_'+uniqueId+'"></div>');
    }
    $('#rightTabCol_'+uniqueId).append('<div class="card-body small overflow-auto" style="max-height: 200px;" id="cardbody_'+uniqueId+'"></div>');
    
    //Inserting Data
    $('#tabPrimeBox_'+uniqueId).append('<span class="d-xs-block d-sm-block d-md-none d-lg-none d-xl-none" title="'+primeBox+'">'+briefPrimeBox+'</span>');
    $('#tabPrimeBox_'+uniqueId).append('<span class="d-none d-md-block d-lg-block d-xl-block">'+primeBox+'</span>');
    
    $('#tabDate_'+uniqueId).append('<span class="d-xs-block d-sm-block d-md-none d-lg-none d-xl-none" title="'+dateBox+'">'+briefDateBox+'</span>');
    $('#tabDate_'+uniqueId).append('<span class="d-none d-md-block d-lg-block d-xl-block">'+dateBox+'</span>');
    
    if(actionPermissions) {
        if(actionPermissions.includes('edit')) {
            $('#tabActions_'+uniqueId).append('<img src="images/save.svg" id="save_'+uniqueId+'" title="Save changes" alt="Save changes" style="max-width: 28px; width: 46%" class="tabActionSave hidden clickable rounded pale-green-link img-fluid p-1" />')
            $('#tabActions_'+uniqueId).append('<img src="images/edit.svg" id="edit_'+uniqueId+'" title="Edit" alt="Edit" style="max-width: 28px; width: 46%" class="tabActionEdit clickable img-fluid p-1" />');
        }
        if(actionPermissions.includes('delete')) {
            $('#tabActions_'+uniqueId).append('<img src="images/trash.svg" id="delete_'+uniqueId+'" title="Delete" alt="Delete" style="max-width: 28px; width: 46%" class="tabActionDelete clickable img-fluid p-1" />');
        }
    }
    
    if(header !== null) {
        $('#cardheader_'+uniqueId).append(header);
    }
    $('#cardbody_'+uniqueId).append(content);
    
}

/**
* Sorts the divs inside parentDiv alphabetically based on an attribute
* 
* @param parentDiv
* @param sortAttribute
* @param sortOrder  "asc" or "desc"
*/
function sortDivsByAttribute(parentDiv, sortAttribute, sortOrder) {
    var sortedDivs = $(parentDiv).children("div").sort(function(a, b) {
      return (sortOrder === "desc") ?
        $(b).attr(sortAttribute) - $(a).attr(sortAttribute) :
        $(a).attr(sortAttribute) - $(b).attr(sortAttribute);
    });
    $(parentDiv).html(sortedDivs);
}

function searchDivsByText(parentDiv, searchTerm, hideCardsOnClear) {
    //Clear highlights from previous searches
    $("span.searchHighlight").replaceWith(function() {
      return $(this).html();
    });    
    $('#'+parentDiv).find("div").each(function() {
        $(this).show();
    })
    // Ensure the search text is lowercase for case-insensitivity
    searchText = searchTerm.toLowerCase();

    // Get all the child divs of the parent element
    var childDivs = $('#'+parentDiv).find("div");

    // Loop through all the child divs
    childDivs.each(function () {
        var div = $(this);
        var divHtml = div.html();
        var divText = div.text().toLowerCase();

        if(searchTerm=='') {
            div.show();
            if(hideCardsOnClear && hideCardsOnClear==1) {
                $('.card-body').each(function() {
                    $(this).hide();
                })
            }
        } else {
            // Check if the search text is present in the div text
            if (divText.indexOf(searchText) === -1) {
              // If not, hide the div
              div.hide();
            } else {
              // If the search text is present, show the div and highlight the searched text
              //console.log('showing');
              div.show();

              // Use a regular expression to match the searched text only if it is not part of an HTML tag
              div.html(divHtml.replace(
                new RegExp("(?![^<]*>)(" + searchText + ")", "gi"),
                "<span class='searchHighlight'>$1</span>"
              ));
            }            
        }

    });           
}

function toggleDetails(id) {
    //console.log('Toggling id: '+id);
    //console.log($('#toggledetails_'+id).attr('src'));
    if($('#toggledetails_'+id).attr('src')=="images/folder-open.svg") {
        //console.log('Toggling icon to view');
        $('#toggledetails_'+id).attr("src", "images/folder.svg");
        $('#case-card-toggle-image').attr("title", "View case details");
        $('#casedetails_'+id).hide(); 
    } else {
        //console.log('Toggling icon to hide');
        $('#toggledetails_'+id).attr("src", "images/folder-open.svg");
        $('#toggledetails_'+id).attr("title", "Hide case details");
        $('#casedetails_'+id).show();
    }
}

function toggleLastComment(id, taskId) {
    //console.log('COMMENT for Task: '+id+', task_id: '+taskId);
    if($('#togglecomments_'+id).attr('src')=="images/caret-top.svg") {
        $('#togglecomments_'+id).attr("src", "images/message.svg");
        $('#comment-card-toggle-image').attr("title", "View comment details");
        $('#casecomment_'+id).hide();
    } else {
        if($('#casecomment_'+id).text()=="") {
            //Get the latest comment
            $.when(getLastComment(taskId)).done(function(output) {
                //console.log(output);
                if(output.count > 0) {
                    var parentDiv='casecomment_'+id;
                    var uniqueId=id;
                    var dateBox=timestamp2date(output.results[0].date_added, 'dd/mm/yy g:i a');
                    var briefDateBox=timestamp2date(output.results[0].date_added, 'dd MM YY');
                    var primeBox=output.results[0].real_name;
                    var briefPrimeBox=getInitials(output.results[0].real_name);
                    var actionPermissions=null;
                    var header=null
                    var content=deWordify(output.results[0].comment_text);
                                        
                    insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content);

                    
                }
            })
        }
        $('#togglecomments_'+id).attr('src', 'images/caret-top.svg');
        $('#comment-card-toggle-image').attr('title', 'Hide comment details');
        $('#casecomment_'+id).show();
    }
}


///// GOOGLE CHARTS STUFF
/**
* Draw a pie chart
* 
* @param name       - the displayed title of the chart
* @param values     - the chart data (in the form of an array ['label', value] - with the first row being titles)
* @param id         - the document id of the chart
* @param xtitle     - the name of the x axis (labels)
* @param ytitle     - the name of the y axis (values)
* @param method     - print or display (default or null is display)
* @param chartheight- the height in pixels of the chart
* @param legend     - where to show the legend (left, right, bottom, top or none)
*/
function drawPieChart(name, values, id, xtitle, ytitle, method, chartheight, legend) {
    //See if google charts has been loaded
    if((typeof google === 'undefined') || (typeof google.visualization === 'undefined')) {
        return false;
    }
    //console.log('Drawing Pie Chart');
    //console.log(values);
    var width=$('#'+id).width();
    
    var chartdata=new google.visualization.DataTable();
    chartdata.addColumn('string', xtitle);
    chartdata.addColumn('number', ytitle);    
    
    var keys=[];
    for (var index in values) {
        keys.push(index);                
    }
    keys.sort(); 
    
    $.each(keys, function(i, val) {
        chartdata.addRow([val, values[val]]);
    })

    var chartoptions={
                'title': name,
                'is3D': true,
                'legend': {position: legend},
                /*'point': {visible: true},*/
                'width': width,
                'height': chartheight,
                'chartArea': {'width': '95%', 'top': 30}
                }; 
    
    var chart_div=document.getElementById(id);
    var chart = new google.visualization.PieChart(chart_div);
    
    chart.draw(chartdata, chartoptions);    
       
}

function googleMultiBarChart(id, title, data) {
    var datum=google.visualization.arrayToDataTable(data);
    var options={
        width: $('#'+id).css("width"),
        backgroundColor: 'white',
        title: title,
        bars: 'veritical',
        //vAxis: {title: 'Cases', viewWindow: {min:0}},
        vAxis: {textStyle: {fontSize: 9}, viewWindow: {min:0}, title: 'Cases'},
        hAxis: {title: 'Quantity', textStyle: {fontSize: 10, fontFamily: "arial"}, slantedText: "true", slantedTextAngle: 50},
        legend: {position: 'none'},
        curveType: 'function',
        bar: {groupWidth: '85%'},
        'chartArea': {'width': '85%', 'top': 30, 'bottom': 100, 'left': 60}
    }
    var chart=new google.visualization.ColumnChart(document.getElementById(id));
    chart.draw(datum, options);
}

function googleMultiLineChart(id, title, data) {
    var datum=google.visualization.arrayToDataTable(data);
    var options={
        width: $('#'+id).css("width"),
        backgroundColor: 'white',
        title: title,
        //vAxis: {title: 'Cases', viewWindow: {min:0}},
        vAxis: {textStyle: {fontSize: 9}, viewWindow: {min:0}, title: 'Cases'},
        hAxis: {title: 'Date', textStyle: {fontSize: 9}, slantedText: false, slantedTextAngle: 120},
        legend: {position: 'top'},
        curveType: 'function',
        pointSize: 4,
        'chartArea': {'width': '85%', 'top': 60, 'bottom': 50}
    }
    var chart=new google.visualization.LineChart(document.getElementById(id));
    chart.draw(datum, options);
}

function googlePieChart(id, title, data) {
    var datum=google.visualization.arrayToDataTable(data);
    var options={
        width: $('#'+id).css("width"),
        backgroundColor: 'white',
        title: title,
        //vAxis: {title: 'Cases', viewWindow: {min:0}},
        vAxis: {textStyle: {fontSize: 9}, viewWindow: {min:0}, title: 'Cases'},
        hAxis: {title: 'Date', textStyle: {fontSize: 9}, slantedText: false, slantedTextAngle: 90},
        legend: {position: 'top'},
        curveType: 'function',
        pointSize: 4,
        'chartArea': {'width': '85%', 'top': 60, 'bottom': 50}
    }
    var chart=new google.visualization.PieChart(document.getElementById(id));
    chart.draw(datum, options);        
    
}

// Function to select a tab by its name, case-insensitive and with spaces trimmed
function selectCaseTabByName(tabName) {
        const targetTab = $("#case-tabs a").filter(function() {
          return $(this).text().trim().toLowerCase() === tabName.trim().toLowerCase();
        });
        
        if (targetTab.length > 0) {
          targetTab.tab('show');
        }
}

(function($,sr){

  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;

      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null;
          };

          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);

          timeout = setTimeout(delayed, threshold || 100);
      };
  }
  // smartresize 
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');


