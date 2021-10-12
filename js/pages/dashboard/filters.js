$(function() {
    //Read the current search/filter settings from status cookie
    var status=getStatus();

    $.each(status.filter, function (i, element) {
        $('#'+i).val(element);
        //console.log('FILTER:'+i+"-"+element);
    })
    
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
            loadCaselist();
        }        
    })
    
    $('#FilterSearch').click(function() {
        //Gather all the options & load cases page
        saveFilterSettings();
        
        //window.location.href="?page=cases"+queryString;
        window.location.href="?page=cases";
    })
    
    $('#userSelect').change(function() {
        myCaseStatus();
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist();
        }
    })
    
    $('#caseTypeSelect').change(function() {
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist();
        }
    })
    
    $('#productSelect').change(function() {
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist();
        }
    }) 
    
    $('#departmentSelect').change(function() {
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist();
        }
    })
    
    $('#statusSelect').change(function() {
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist();
        }
    })
    
    $('#caseGroupSelect').change(function() {
        saveFilterSettings();
        if($('#caselist').length) {
            loadCaselist();
        }
    })
})

function myCaseStatus() {
    //console.log('Checking user selected - '+$('#userSelect').val()+' against current user: '+globals.user_id);
    if($('#userSelect').val()==globals.user_id) {
        $('#mycasesOnly').prop('checked', true);
    } else {
        $('#mycasesOnly').prop('checked', false);
    }    
}

function saveFilterSettings() {
    var status=getStatus();
    var queryString='';
    $.each($('.filterQuery'), function(i, element) {
        queryString+="&"+this.id+"="+$(this).val();
        //queryString+=
        //delete status[this.id];
        if(status.filter == undefined) {
            status['filter']={};
        }
        status.filter[this.id]=$(this).val();
        
    })
    //console.log(status);
    setStatus(status);    
}