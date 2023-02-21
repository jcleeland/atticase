$(function() {
    $('.updatecustomfield').change(function() {
        if($(this).is(':checkbox')) {
            var value=$(this).is(':checked') ? 1 : 0;
        } else {
            var value=$(this).val();
        }
        var field=$(this).attr('action');
        var customfielddefinitionid=$(this).attr('typeid');
        var currentId=$(this).attr('id');
        $('#'+currentId).removeClass('fieldUpdated');        
        console.log(customfielddefinitionid, field, value);
        $.when(customFieldUpdate(customfielddefinitionid, field, value)).done(function(output) {
            console.log(output);
            $('#'+currentId).addClass('fieldUpdated'); 
        })
    })
    
    $('.createcustomfield').click(function() {
        var customfieldname=$('#customfieldname').val();
        var customfieldtype=$('#customfieldtype').val();
        var customfieldvisible=$('#customfieldvisible').is(':checked') ? 1 : 0;
        console.log(customfieldname, customfieldtype, customfieldvisible);
        if(customfieldtype != "") {
            $.when(customFieldCreate(customfieldname, customfieldtype, customfieldvisible)).done(function(output) {
                if(output.results != "Error - No case type name provided") {
                    window.location.href="?page=options&option=customfields";
                }
            })
        } else {
            alert("You must select a field type");
        }
        
    });
});

function deleteCustomField(id) {
    if(confirm("Are you sure you want to delete this custom field?")) {
        $.when(customFieldDelete(id)).done(function() {
            window.location.href="?page=options&option=customfields"; 
        })
    }
}