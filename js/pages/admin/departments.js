$(function() {

    $('.form-control, .form-checkbox').on('input, change', function(x) {
        $('#saveDepartmentsBtn').addClass("pale-green-link");
        $('#undoDepartmentsBtn').show();
    })
    $('.notification-btn').click(function() {
        var departmentId=$(this).attr('departmentId');
        
        if($('#notifications_'+departmentId).is(':visible')) {
            $('#notifications_'+departmentId).html('');
            $('#notifications_'+departmentId).hide();    
        } else {
            $('.notificationsshell').each(function() {
                //console.log($(this));
                $(this).html('');
                $(this).hide();
            })
            $('#notifications_'+departmentId).show('slow');
            if($('#notifications_'+departmentId).is(':visible')) {
                 $.when(departmentNotificationsList(departmentId)).done(function(notifications) {
                    console.log(notifications);
                    $('#notifications_'+departmentId).html('<div class="col-sm-11 ml-3 mr-3 w-100 border rounded smaller mb-2" id="existingnotifications"><div class="row"><div class="col-3"></div><div class="col w-100 text-center header">Notifications for this Department</div><div class="col-3"></div></div><div class="row"><div class="col-3"></div><div class="col-2 bg-light">User</div><div class="col-1 bg-light">New</div><div class="col-1 bg-light">Change</div><div class="col-1 bg-light">Close</div><div class="col-1 bg-light"></div><div class="col-2"></div></div>');
                    if(notifications.count > 0) {
                        $.each(notifications.results, function(i, notificationdata) {
                            console.log(notificationdata);
                            var newchecked = notificationdata.notify_new == 1 ? ' checked' : '';
                            var changechecked = notificationdata.notify_change == 1 ? ' checked' : '';
                            var closechecked = notificationdata.notify_close == 1 ? ' checked' : '';
                            console.log(notificationdata);
                            $('#existingnotifications').append('<div class="row mt-3 mb-3 userIdNotificationRow'+notificationdata.user_id+'"><div class="col-3"></div><div class="col-2">'+notificationdata.real_name+'</div><div class="col-1"><input id="newNotifications_'+notificationdata.user_id+'" class="checkbox" type="checkbox" onClick="toggleNewNotification(\''+notificationdata.user_id+'\',\''+departmentId+'\')" '+newchecked+' /></div><div class="col-1"><input id="changeNotifications_'+notificationdata.user_id+'" class="checkbox"type="checkbox" onClick="toggleChangeNotification(\''+notificationdata.user_id+'\',\''+departmentId+'\')" '+changechecked+' /></div><div class="col-1"><input id="closeNotifications_'+notificationdata.user_id+'" class="checkbox" type="checkbox" onClick="toggleCloseNotification(\''+notificationdata.user_id+'\',\''+departmentId+'\')" '+closechecked+' /></div><div class="col-1"><span class="btn-info btn-sm p-1 pointer" style="line-height: 0.75em !important" onClick="deleteNotification(\''+notificationdata.user_id+'\',\''+departmentId+'\')">Delete</span></div><div class="col-2"></div></div>');
                        });
                    } else {
                        
                    } 
                    $('#notifications_'+departmentId).append('<div class="col-sm-11 ml-3 mr-3 w-100 border rounded smaller mb-2" id="addnotifications"><div class="row"><div class="col-3"></div><div class="col w-100 text-center header">Add a Notification for this Department</div><div class="col-3"></div></div><div class="row"><div class="col-3"></div><div class="col-2 bg-light">User</div><div class="col-1 bg-light">New</div><div class="col-1 bg-light">Change</div><div class="col-1 bg-light">Close</div><div class="col-1 bg-light"></div><div class="col-2"></div></div>');
                    $('#addnotifications').append('<div class="row mt-3 mb-3"><div class="col-3"></div><div class="col-2 m-0 p-0" id="newUserSelect"></div><div class="col-1"><input id="createNewNotification" class="checkbox" type="checkbox" /></div><div class="col-1"><input id="createChangeNotification" class="checkbox" type="checkbox" /></div><div class="col-1"><input id="createCloseNotification" class="checkbox" type="checkbox" /></div><div class="col-1"><span class="btn-info btn-sm p-1 pointer" style="line-height: 0.75em !important" onClick="createNotification(\''+departmentId+'\')">Add</span></div><div class="col-2"></div></div>');
                    var $klon=$('#createUserNotificationTemplate').clone().prop('id', 'createUserNotification').removeClass('hidden');
                    $("#newUserSelect").html($klon);
                 })
            } else {
                $('#notifications_'+departmentId).html("");
            }           
        }
        
 
    })

    $('.updatedepartmentfield').change(function() {
        if($(this).is(':checkbox')) {
            var value=$(this).is(':checked') ? 1 : 0;
        } else {
            var value=$(this).val();
        }
        var field=$(this).attr('action');
        var departmentid=$(this).attr('departmentid');
        var currentId=$(this).attr('id');
        $('#'+currentId).removeClass('fieldUpdated');
        console.log(departmentid, field, value);
        $.when(departmentUpdate(departmentid, field, value)).done(function(output) {
            console.log(currentId);
            $('#'+currentId).addClass('fieldUpdated');
        })
    })
        
    $('.createDepartmentField').click(function() {
        var departmentName=$('#create_category_name').val();
        var departmentDescrip=$('#create_category_descrip').val();
        var groupIn=$('#create_group_in').val();
        var listpos=$('#create_list_position').val();
        var showin=$('#create_show_in_list').is(':checked') ? 1 : 0;
        
        console.log(departmentName, departmentDescrip, groupIn, listpos, showin);
        
        $.when(departmentCreate(departmentName, departmentDescrip, groupIn, listpos, showin)).done(function(output) {
            if(output.results != "Error - No department name provided") {
                window.location.href="?page=options&option=departments";
            }
        })        
    })

});

function createNotification(departmentid) {
    var newNotification=$('#createNewNotification').is(':checked') ? 1 : 0;
    var changeNotification=$('#createChangeNotification').is(':checked') ? 1 : 0;
    var closeNotification=$('#createCloseNotification').is(':checked') ? 1 : 0;
    var userId=$('#createUserNotification').val();
    var realName=$('#createUserNotification option:selected').text();
    console.log(departmentid, userId, newNotification, changeNotification, closeNotification);
    $.when(departmentNotificationsCreate(departmentid, $('#createUserNotification').val(), newNotification, changeNotification, closeNotification)).done(function(output) {
        console.log(output);
        if(output.results=="There is already an entry for this user") {
            alert("A notification already exists for this user");
        } else {
            var newchecked = newNotification == 1 ? ' checked' : '';
            var changechecked = changeNotification == 1 ? ' checked' : '';
            var closechecked = closeNotification == 1 ? ' checked' : '';        
            $('#existingnotifications').append('<div class="row mt-3 mb-3 userIdNotificationRow'+userId+'"><div class="col-3"></div><div class="col-2">'+realName+'</div><div class="col-1"><input id="newNotifications_'+userId+'" class="checkbox" type="checkbox" onClick="toggleNewNotification(\''+userId+'\',\''+departmentid+'\')" '+newchecked+' /></div><div class="col-1"><input id="changeNotifications_'+userId+'" class="checkbox"type="checkbox" onClick="toggleChangeNotification(\''+userId+'\',\''+departmentid+'\')" '+changechecked+' /></div><div class="col-1"><input id="closeNotifications_'+userId+'" class="checkbox" type="checkbox" onClick="toggleCloseNotification(\''+userId+'\',\''+departmentid+'\')" '+closechecked+' /></div><div class="col-1"><span class="btn-info btn-sm p-1 pointer" style="line-height: 0.75em !important" onClick="deleteNotification(\''+userId+'\',\''+departmentid+'\')">Delete</span></div><div class="col-2"></div></div>');
        }
        
    })
}

function deleteNotification(userid, departmentid) {
    console.log('User id: '+userid+', Department id: '+departmentid);
    if(confirm("Are you sure you want to delete this notification?")) {
        $.when(departmentNotificationsDelete(departmentid, userid)).done(function(output){
            console.log(output);    
            $('.userIdNotificationRow'+userid).hide();        
        });
    }
}

function toggleNewNotification(userid, departmentid) {
    var value=$('#newNotifications_'+userid).is(':checked') ? 1 : 0;
    var name='notify_new';
    $.when(departmentNotificationsUpdate(departmentid, userid, name, value)).done(function() {
        console.log('Saved');
    });
}

function toggleChangeNotification(userid, departmentid) {
    var value=$('#changeNotifications_'+userid).is(':checked') ? 1 : 0;
    var name='notify_change';
    $.when(departmentNotificationsUpdate(departmentid, userid, name, value)).done(function() {
        console.log('Saved');
    });
}

function toggleCloseNotification(userid, departmentid) {
    var value=$('#closeNotifications_'+userid).is(':checked') ? 1 : 0;
    var name='notify_close';
    $.when(departmentNotificationsUpdate(departmentid, userid, name, value)).done(function() {
        console.log('Saved');
    });
}

function deleteDepartment(departmentId) {
    if(confirm('Are you sure you want to delete this Department?'))  {
        $.when(departmentDelete(departmentId)).done(function(output) {
            console.log('Deleted');
            $('#departmentRow'+departmentId).hide('slow');
        })
    }    
}