$(function() {
    $('.updatepoiperson').change(function() {
        if($(this).is(':checkbox')) {
            var value=$(this).is(':checked') ? 1 : 0;
        } else {
            var value=$(this).val();
        }
        var field=$(this).attr('action');
        var poipersonid=$(this).attr('typeid');
        var currentId=$(this).attr('id');
        $('#'+currentId).removeClass('fieldUpdated');        
        console.log(poipersonid, field, value);
        $.when(poiPersonUpdate(poipersonid, field, value)).done(function(output) {
            console.log(output);
            $('#'+currentId).addClass('fieldUpdated'); 
        })
    })
    
    $('.viewconnections').click(function() {
        var viewid=$(this).attr('typeid');
        if($('#connectionlist'+viewid).is(':visible')) {
            console.log('Already showing');
            $('#connectionlist'+viewid).hide('slow');
        } else {
            $('.connectionlists').each(function() {
                $(this).hide();
            })
            $('#connectionlist'+viewid).show('fast');
            $('#connections'+viewid).html('');
            $.when(poiConnectionsList(viewid)).done(function(output) {
                //console.log(output);
                $('#connections'+viewid).append("<div class='row font-weight-bold'><div class='col-sm-1'>Case</div><div class='col-sm-3'>Summary</div><div class='col-sm-6'>Connection comment</div><div class='col-sm'>Connected</div><div class='col-sm'></div></div>");
                $.each(output.results, function(i, connection) {
                    console.log(connection);
                    $('#connections'+viewid).append("<div class='row' id='personconnection"+connection.id+"'><div class='col-sm-1'><a href='index.php?page=case&case="+connection.task_id+"'>#"+connection.task_id+"</a></div><div class='col-sm-3'>"+connection.item_summary+"</div><div class='col-sm-6'>"+connection.comment+"</div><div class='col-sm'>"+connection.modified+"</div><div class='col-sm'><span class='btn btn-sm btn-info pb-0 pt-0 smaller disconnect' title='Disconnect this person from this case' onClick='deleteConnection(\""+connection.id+"\", \""+viewid+"\")'>Del</span></div></div>");
                })
            })            
        }
    })
    
   
    $('.createpoiperson').click(function() {
        var firstname=$('#firstname').val();
        var lastname=$('#lastname').val();
        var position=$('#position').val();
        var organisation=$('#organisation').val();
        var phone=$('#phone').val();
        var email=$('#email').val();
        console.log(firstname, lastname, position, organisation, phone, email);
        if(firstname != "" && lastname != "") {
            $.when(poiPersonCreate(firstname, lastname, position, organisation, phone, email)).done(function(output) {
                if(output.results != "Error - No poi action provided") {
                    window.location.href="?page=options&option=poi";
                }
            })
        } else {
            alert("There must be at least a first and last name");
        }
        
    });
});

function deletePoiPerson(id) {
    if(confirm("Are you sure you want to delete this person?")) {
        $.when(poiPersonDelete(id)).done(function() {
            window.location.href="?page=options&option=poi"; 

        })
    }
}

function deleteConnection(poiId, personid) {
    if(confirm("Are you sure you want to disconnect this person from this case?")) {
        $.when(poiLinkDelete(poiId)).done(function() {
            console.log('Link removed');
            $('#personconnection'+poiId).hide();
            var connectioncount=parseInt($('#connectioncount'+personid).text());
            var connectioncount=connectioncount-1;
            if(connectioncount==0) {
                $('#connectioncount'+personid).html("<span class='btn btn-warning btn-sm' title='This person can be deleted because there are no cases connected against them' onClick='deletePoiPerson(\""+personid+"\")'>Del</span>");
                $('#connectionlist'+personid).hide();    
            } else {
                $('#connectioncount'+personid).text(connectioncount);
            }
                       
        })
    }
}