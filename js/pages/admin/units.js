$(function() {
    $('.updateunitfield').change(function() {
        if($(this).is(':checkbox')) {
            var value=$(this).is(':checked') ? 1 : 0;
        } else {
            var value=$(this).val();
        }
        var field=$(this).attr('action');
        var unitid=$(this).attr('unitid');
        var currentId=$(this).attr('id');
        $('#'+currentId).removeClass('fieldUpdated');        
        console.log(unitid, field, value);
        $.when(unitUpdate(unitid, field, value)).done(function(output) {
            console.log(output);
            $('#'+currentId).addClass('fieldUpdated'); 
        })
    })
    
    $('.createunit').click(function() {
        var unitdescrip=$('#unitdescrip').val();
        var listpos=$('#listposition').val();
        var showin=$('#showinlist').is(':checked') ? 1 : 0;
        console.log(unitdescrip, listpos, showin);
        
        $.when(unitCreate(unitdescrip, listpos, showin)).done(function(output) {
            if(output.results != "Error - No unit description provided") {
                window.location.href="?page=options&option=units";
            }
        })
        
    });
});

function deleteUnit(id) {
    if(confirm("Are you sure you want to delete this unit? Note that deleting removes the item from the list of options but does not delete already existing data stored against a case.")) {
        $.when(unitDelete(id)).done(function() {
            window.location.href="?page=options&option=units"; 
        })
    }
}