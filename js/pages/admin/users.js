$(function() {
    var system=getSettings("AttiCaseSystem");
    var isAdmin=system.is_admin;
    console.log(system);
    $('.changegroup').click(function() {
        if(isAdmin==1) {
            var action=$(this).attr('action');
            var groupid=$(this).attr('groupid');
            var value=$(this).is(':checked') ? 1 : 0;
            //Check that user has rights to do this? (ie: is_admin)

            groupUpdate(groupid, action, value);        
        } else {
            if($(this).is(':checked')) $(this).prop("checked", false);
            if(!$(this).is(':checked')) $(this).propr("checked", true);
        }
    })
    $('.changeuser').change(function() {
        if(isAdmin==1) {
            var newValues={};
            var userid=$(this).attr('userid');
            var action=$(this).attr('action');
            if($(this).attr('type')=="checkbox") {
                var value=$(this).is(':checked') ? 1 : 0;
            } else {
                var value=$(this).val();
            }
            newValues[action]=value;
            console.log(action+'-'+userid+'-'+value);
            accountUpdate(userid, newValues);
            $(this).css('background-color', '#dfeed7');
        }
    })
    $('.deleteGroup').click(function() {
        if(isAdmin==1) {
            var groupId=$(this).attr('groupid');
            groupDelete(groupId);
            
        }
    })
    $('.showrestrictversions').click(function() {
        var groupid=$(this).attr('groupid');
        if($(this).is(':checked')) {
            $('#restrict_version_title_'+groupid).show();
            $('#restrict_version_options_'+groupid).show();
        } else {
            $('#restrict_version_title_'+groupid).hide();
            $('#restrict_version_options_'+groupid).hide();
        }
    })
    $('.changerestrictversion').click(function() {
        if(isAdmin==1) {
            var groupid=$(this).attr('groupid');
            var versionid=$(this).attr('versionid');
            var value=$(this).is(':checked') ? 1 : 0;
            restrictVersionUpdate(groupid, versionid, value);
        } else {
            if($(this).is(':checked')) $(this).prop("checked", false);
            if(!$(this).is(':checked')) $(this).propr("checked", true);            
        }
    })
});