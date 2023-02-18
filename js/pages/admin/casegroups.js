$(function() {
    $('.updategroupfield').change(function() {
        if($(this).is(':checkbox')) {
            var value=$(this).is(':checked') ? 1 : 0;
        } else {
            var value=$(this).val();
        }
        var field=$(this).attr('action');
        var groupid=$(this).attr('groupid');
        console.log(groupid, field, value);
        $.when(caseGroupUpdate(groupid, field, value)).done(function(output) {
            console.log(output);
        })
    })
    
    $('.creategroupfield').click(function() {
        var vername=$('#versionname').val();
        var listpos=$('#listposition').val();
        var showin=$('#showinlist').is(':checked') ? 1 : 0;
        var isenquiry=$('#isenquiry').is(':checked') ? 1 : 0;
        console.log(vername, listpos, showin, isenquiry);
        
        $.when(caseGroupCreate(vername, listpos, showin, isenquiry)).done(function(output) {
            if(output.results != "Error - No category name provided") {
                window.location.href="?page=options&option=casegroups";
            }
        })
        
    });
});

function deleteCaseGroup(id) {
    if(confirm("Are you sure you want to delete this case group?")) {
        $.when(caseGroupDelete(id)).done(function() {
            window.location.href="?page=options&option=casegroups"; 
        })
    }
}