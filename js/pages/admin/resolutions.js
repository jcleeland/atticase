$(function() {
    $('.updateresolutionfield').change(function() {
        if($(this).is(':checkbox')) {
            var value=$(this).is(':checked') ? 1 : 0;
        } else {
            var value=$(this).val();
        }
        var field=$(this).attr('action');
        var resolutionid=$(this).attr('typeid');
        var currentId=$(this).attr('id');
        $('#'+currentId).removeClass('fieldUpdated');        
        console.log(resolutionid, field, value);
        $.when(resolutionUpdate(resolutionid, field, value)).done(function(output) {
            console.log(output);
            $('#'+currentId).addClass('fieldUpdated'); 
        })
    })
    
    $('.createresolution').click(function() {
        var resolutionname=$('#resolutionname').val();
        var resolutiondescrip=$('#resolutiondescrip').val();
        var listpos=$('#listposition').val();
        var showin=$('#showinlist').is(':checked') ? 1 : 0;
        console.log(resolutionname, resolutiondescrip, listpos, showin);
        
        $.when(resolutionCreate(resolutionname, resolutiondescrip, listpos, showin)).done(function(output) {
            if(output.results != "Error - No resolution name provided") {
                window.location.href="?page=options&option=resolutions";
            }
        })
        
    });
});

function deleteResolution(id) {
    if(confirm("Are you sure you want to delete this resolution?")) {
        $.when(resolutionDelete(id)).done(function() {
            window.location.href="?page=options&option=resolutions"; 
        })
    }
}