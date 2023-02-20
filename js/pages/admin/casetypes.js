$(function() {
    $('.updatetypefield').change(function() {
        if($(this).is(':checkbox')) {
            var value=$(this).is(':checked') ? 1 : 0;
        } else {
            var value=$(this).val();
        }
        var field=$(this).attr('action');
        var taskid=$(this).attr('typeid');
        var currentId=$(this).attr('id');
        $('#'+currentId).removeClass('fieldUpdated');        
        console.log(taskid, field, value);
        $.when(caseTypeUpdate(taskid, field, value)).done(function(output) {
            console.log(output);
            $('#'+currentId).addClass('fieldUpdated'); 
        })
    })
    
    $('.createtypefield').click(function() {
        var typename=$('#categoryname').val();
        var typedescrip=$('#categorydescrip').val();
        var listpos=$('#listposition').val();
        var showin=$('#showinlist').is(':checked') ? 1 : 0;
        console.log(typename, typedescrip, listpos, showin);
        
        $.when(caseTypeCreate(typename, typedescrip, listpos, showin)).done(function(output) {
            if(output.results != "Error - No case type name provided") {
                window.location.href="?page=options&option=casetypes";
            }
        })
        
    });
});

function deleteCaseType(id) {
    if(confirm("Are you sure you want to delete this case type?")) {
        $.when(caseTypeDelete(id)).done(function() {
            window.location.href="?page=options&option=casetypes"; 
        })
    }
}