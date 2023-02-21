$(function() {
    $('.updatecustomtext').change(function() {
        if($(this).is(':checkbox')) {
            var value=$(this).is(':checked') ? 1 : 0;
        } else {
            var value=$(this).val();
        }
        var field=$(this).attr('action');
        var customtextid=$(this).attr('typeid');
        var currentId=$(this).attr('id');
        $('#'+currentId).removeClass('fieldUpdated');        
        console.log(customtextid, field, value);
        $.when(customTextUpdate(customtextid, field, value)).done(function(output) {
            console.log(output);
            $('#'+currentId).addClass('fieldUpdated'); 
        })
    })
    
    $('.updatepreview').change(function() {
        var currentId=$(this).attr('typeid');
        console.log('updating preview '+currentId);
        console.log($(this).val());
        $('#preview'+currentId).html($(this).val());
    })
    
    $('.createcustomtext').click(function() {
        var modifyaction=$('#modifyaction').val();
        var customtext=$('#customtext').val();
        console.log(modifyaction, customtext);
        if(modifyaction != "") {
            $.when(customTextCreate(modifyaction, customtext)).done(function(output) {
                if(output.results != "Error - No modify action provided") {
                    window.location.href="?page=options&option=customtexts";
                }
            })
        } else {
            alert("You must select an action");
        }
        
    });
});

function deleteCustomText(id) {
    if(confirm("Are you sure you want to delete this custom field?")) {
        $.when(customTextDelete(id)).done(function() {
            window.location.href="?page=options&option=customtexts"; 
        })
    }
}