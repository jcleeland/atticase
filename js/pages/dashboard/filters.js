const queryString="";
$(function() {
    //Read the current search/filter settings from status cookie
    var status=getStatus();

    $.each(status.filter, function (i, element) {
        $('#'+i).val(element);
        //console.log('FILTER:'+i+"-"+element);
    })
    
    $('#userSelect').prepend("<option value='null'>Unassigned</option>\n");
    
    //console.log('Checking user');
    myCaseStatus();
    
    $('#mycasesOnly').click(function() {
        userId=globals.user_id;
        //console.log('Current User is'+userId);
        if($(this).is(':checked')) {
            //console.log('Checked');
            //Change the user list to current user
            $('#userSelect').val(userId);
        } else {
            $('#userSelect').val('');
            //console.log('Unchecked');
        }
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist();
        }
    })
    
    $('#clearFilter').click(function() {
        var status=getStatus();
        //console.log('Old status');
        //console.log('New status');
        $.each(status.filter, function(i, element) {
            delete status.filter[i];
        })
        setStatus(status);
        //console.log('New Status');
        //console.log(status);
        
        $.each($('.filterQuery'), function(i, element) {
            $(this).val("");
        })
        $("#statusSelect").val("0");
        $("#userSelect").val(globals.user_id);
        myCaseStatus();
        if($('#caselist').length) {
            loadCaselist(1);
        }        
    })
    
    $('#FilterSearch').click(function() {
        //Gather all the options & load cases page
        $.when(saveFilterSettings()).done(function() {
            loadCaselist(1);
        });
        //window.location.href="?page=cases&"+queryString;
        //window.location.href="?page=cases";
    })
    
    $('#userSelect').change(function() {
        myCaseStatus();
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist(1);
        }
    })
    
    $('#caseTypeSelect').change(function() {
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist(1);
        }
    })
    
    $('#productSelect').change(function() {
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist(1);
        }
    }) 
    
    $('#departmentSelect').change(function() {
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist(1);
        }
    })
    
    $('#statusSelect').change(function() {
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist(1);
        }
    })
    
    $('#caseGroupSelect').change(function() {
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist(1);
        }
    })
    
    $('#filterMoreBtn').click(function() {
        console.log('Click');
        if($('#filterMore').is(':visible')) {$('#filterMoreBtn').html('More >>')} else {$('#filterMoreBtn').html('Less <<');}
    })
})

function myCaseStatus() {
    //console.log('Checking user selected - '+$('#userSelect').val()+' against current user: '+globals.user_id);
    if($('#userSelect').val()==globals?.user_id ?? null) {
        $('#mycasesOnly').prop('checked', true);
    } else {
        $('#mycasesOnly').prop('checked', false);
    }    
}

function saveFilterSettings() {
    var status=getStatus();
    console.log('Status was...');
    console.log(status);
    var queryString='';
    $.each($('.filterQuery'), function(i, element) {
        queryString+="&"+this.id+"="+$(this).val();
        //console.log(queryString);
        //queryString+=
        //delete status[this.id];
        if(status.filter == undefined) {
            status['filter']={};
        }
        status.filter[this.id]=$(this).val();   
        console.log(status.filter); 
    })
    setStatus(status);    
    var test=getStatus();
    console.log('Status is read back as:');
    console.log(test);
}