$(function() {
    $('.form-control, .form-checkbox').on('input, change', function(x) {
        $('#saveDepartmentsBtn').addClass("pale-green-link");
        $('#undoDepartmentsBtn').show();
    })
    $('.notification-btn').click(function() {
        var departmentId=$(this).attr('departmentId');
        $('.notificationsshell').each(function() {
            //console.log($(this));
            $(this).html('');
            $(this).hide();
        })
        $('#notifications_'+departmentId).show('slow');
        if($('#notifications_'+departmentId).is(':visible')) {
             $.when(departmentNotificationsList(departmentId)).done(function(notifications) {
                console.log(notifications);
                $('#notifications_'+departmentId).html('<div class="col-sm-11 ml-3 mr-3 w-100 border rounded smaller mb-2" id="existingnotifications"><div class="row"><div class="col-1"></div><div class="col w-100 text-center header">Notifications for this Department</div><div class="col-1"></div></div><div class="row"><div class="col-1"></div><div class="col-4">User</div><div class="col-1">New</div><div class="col-1">Change</div><div class="col-1">Close</div><div class="col-1"></div><div class="col-1"></div></div>');
                if(notifications.count > 0) {
                    $.each(notifications.results, function(i, notificationdata) {
                        var newchecked=notificationdata.notify_new=1 ? ' checked' : '';
                        var changechecked=notificationdata.notify_change=1 ? ' checked' : '';
                        var closechecked=notificationdata.notify_close=1 ? ' checked' : '';
                        console.log(notificationdata);
                        $('#existingnotifications').append('<div class="row"><div class="col-1"></div><div class="col-4">'+notificationdata.real_name+'</div><div class="col-1"><input id="newNotifications_'+notificationdata.user_id+'" class="checkbox" type="checkbox" onClick="toggleNewNotification(\''+notificationdata.user_id+'\',\''+departmentId+'\')" '+newchecked+' /></div><div class="col-1"><input id="changeNotifications_'+notificationdata.user_id+'" class="checkbox"type="checkbox" onClick="toggleChangeNotification(\''+notificationdata.user_id+'\',\''+departmentId+'\')" '+changechecked+' /></div><div class="col-1"><input id="closeNotifications_'+notificationdata.user_id+'" class="checkbox" type="checkbox" onClick="toggleCloseNotification(\''+notificationdata.user_id+'\',\''+departmentId+'\')" '+closechecked+' /></div><div class="col-1"><span class="btn-info btn-sm p-1 pointer" userid="'+notificationdata.user_id+'" groupid="'+departmentId+'" style="line-height: 0.75em !important" onClick="deleteNotification(\''+notificationdata.user_id+'\',\''+departmentId+'\')">Delete</span></div><div class="col-1"></div></div>');
                    });
                } else {
                    $('#existingnotifications').html('No current notifications for this Department');
                } 
             })
        } else {
            $('#notifications_'+departmentId).html("");
        }
    })

});

function deleteNotification(userid, departmentid) {
    console.log('User id: '+userid+', Department id: '+departmentid);
}

function toggleNewNotification(userid, departmentid) {
    if($('#newNotifications_'+userid).is(':checked')) {
        console.log('It is checked');
    } else {
        console.log('It is not checked');
    }
    console.log($(this));
}

function toggleChangeNotification(userid, departmentid) {
    if($('#changeNotifications_'+userid).is(':checked')) {
        console.log('It is checked');
    } else {
        console.log('It is not checked');
    }
    console.log($(this));
}

function toggleCloseNotification(userid, departmentid) {
    if($('#closeNotifications_'+userid).is(':checked')) {
        console.log('It is checked');
    } else {
        console.log('It is not checked');
    }
    console.log($(this));
}