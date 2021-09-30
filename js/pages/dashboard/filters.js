$(function() {
    $('#mycasesOnly').click(function() {
        userId=$('#user_id').val();
        console.log('Current User is'+userId);
        if($(this).is(':checked')) {
            console.log('Checked');
            //Change the user list to current user
            $('#userSelect').val(userId);
        } else {
            $('#userSelect').val('');
            console.log('Unchecked');
        }
        loadCaselist();
    })
    
    $('#userSelect').change(function() {
        if($('#userSelect').val()==globals.userId) {
            $('#mycasesOnly').prop('checked', true);
        } else {
            $('#mycasesOnly').prop('checked', false);
        }
        loadCaselist();
    })
    
    $('#caseTypeSelect').change(function() {
        loadCaselist();
    })
    
    $('#productSelect').change(function() {
        loadCaselist();
    }) 
    
    $('#departmentSelect').change(function() {
        loadCaselist();
    })
    
    $('#statusSelect').change(function() {
        loadCaselist();
    })
    
    $('#caseGroupSelect').change(function() {
        loadCaselist();
    })
})