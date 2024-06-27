$(function() {
    $(document).on('change', '.updatecustomfieldlist', function() {
        var value=$(this).val();
        var field=$(this).attr('action');
        var customfieldlistid=$(this).attr('typeid');
        var currentId=$(this).attr('id');
        $('#'+currentId).removeClass('fieldUpdated');
        console.log(customfieldlistid, field, value);
        console.log('ID: '+currentId);
        $.when(customFieldItemUpdate(customfieldlistid, field, value)).done(function(output) {
            console.log(output);
            $('#'+currentId).addClass('fieldUpdated');
        })
    });

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
        if($(this).attr("data-original")=="l") {
            if (confirm("Changing this custom field type to something other than list will also delete all the items currently stored for the existing list. Are you sure you want to change this?")) {
                $.when(customFieldUpdate(customfielddefinitionid, field, value)).done(function(output) {
                    console.log(output);
                    //Now delete all the list items
                    $.when(customFieldItemDelete(null, customfielddefinitionid)).done(function(output) {
                        console.log(output);
                        $('#'+currentId).addClass('fieldUpdated'); 
                    });
                })
            } else {
                $(this).val($(this).attr("data-original"));
            }
        } 
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

    $('.customfieldtype').change(function() {
        var customfieldtype=$(this).find(":selected").val();
        var customfieldid = $(this).attr('typeid') || '';
        console.log(customfieldtype, customfieldid);
        if(customfieldtype=="l") {
            $('#customfieldname'+customfieldid+'-list').show();
        } else {
            $('#customfieldname'+customfieldid+'-list').hide();
        }
        
    });
});

function deleteCustomField(id) {
    if(confirm("Are you sure you want to delete this custom field?")) {
        //Check if the custom field is type "l" and if so, delete all the list items
        if($('#customfieldtype'+id).val()=="l") {
            if(confirm("Note that when you delete this field, you'll also be deleting the list items associated with it. Are you still sure you want to delete this field?")) {
                $.when(customFieldItemDelete(null, id)).done(function() {
                    $.when(customFieldDelete(id)).done(function() {
                        window.location.href="?page=options&option=customfields"; 
                    })
                });
            }
        } else {
            $.when(customFieldDelete(id)).done(function() {
                window.location.href="?page=options&option=customfields"; 
            })
        }
    }
}

function addAnotherOption(target, customFieldDefinitionId) {
    var lastListOrder=$('#'+target+' input[name="custom_field_order[]"]').last().val();
    if(lastListOrder) {
        var nextListOrder=parseInt(lastListOrder)+1;
    } else {
        var nextListOrder=1;
    }
    $.when(customFieldListItemCreate(customFieldDefinitionId, 'New list item', nextListOrder)).done(function(output) {  
        console.log(output);
        var customFieldListId=output.insertId;
        $('#'+target).append("<div class='row mb-1 mt-1 customfieldlistitem' data-fieldlistid='"+customFieldDefinitionId+"'><div class='col-sm-1'></div><div class='col-sm-9'><input action='custom_field_value' typeid='"+customFieldListId+"' class='form-control smaller updatecustomfieldlist' placeholder='List Item' id='customfieldvalue"+customFieldDefinitionId+"_"+customFieldListId+"' type='text' name='custom_field_value[]' /></div><div class='col-sm-2 text-center'><input action='custom_field_order' typeid='"+customFieldListId+"' class='form-control smaller updatecustomfieldlist' placeholder='List Item Order' id='customfieldorder"+customFieldDefinitionId+"_"+customFieldListId+"' type='text' name='custom_field_order[]' value='"+nextListOrder+"' /></div></div>");
        $('#customfieldvalue'+customFieldDefinitionId+'_'+customFieldListId).focus();
    });
}

function removeLastOption(target, customFieldDefinitionId) {
    console.log('Checking #'+target);
    var customfieldlistid=$('#'+target+' div.customfieldlistitem:last').attr("data-fieldlistid");
    if(customfieldlistid != "new") {
        //Delete the item from the database
        $.when(customFieldItemDelete(customfieldlistid, null)).done(function(output) {
            $('#'+target+' div.customfieldlistitem:last').remove();
        });
    } else {
        //Just remove the item from the screen
        $('#'+target+' div.customfieldlistitem:last').remove();
    }
    
}