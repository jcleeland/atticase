$(function() {
    var status=getStatus();
    //console.log(status);
    if(status.caseviews != undefined) {
        const caseviews=status.caseviews;
        
        //console.log(caseviews);
        for(const key in caseviews) {
            caseview=caseviews[key];
            //console.log(caseview);
            if(caseview.userid==$('#userid').val()) {
            $('#case_viewing_history').append('<div viewed="'+caseview.viewed+'" caseid="'+caseview.caseid+'" client="'+caseview.caseclient+'"><a href="index.php?page=case&case='+caseview.caseid+'">Case #'+caseview.caseid+'</a>: '+caseview.client+' ['+caseview.title+'] | Accessed: '+timestamp2date(caseview.viewed, 'g:ia dd MM yy')+'</div>');                
            }

        }
        sortDivsByAttribute("#case_viewing_history", "viewed", "desc");
    }
    $('#clear_case_history').click(function() {
        if (confirm('Are you sure you want to clear your case browsing history?')) {
            delete status.caseviews;
            //Object.defineProperty(status, "caseviews", {value: {null}, enumerable: true});
            setStatus(status);
        }
    })
    $('#logoutcookies').click(function() {
        if (confirm('Are you sure you want to clear your entire cookie? This will also log you out.')) {
            window.location.href="index.php?logout=true&clearcookies=true";    
        }
    })
    $('#save_changes').click(function () {
        var userId=$('#userid').val();
        //Gather all the values
        var newValues={};
        $('.updateaccount').each(function(i, obj) {
            if($(this).is(':checkbox')) {
                if($(this).is(':checked')) {
                    newValues[this.id]=1;
                } else {
                    newValues[this.id]=0;
                }
            } else {
                if(typeof $(this).val() !== "undefined" && $(this).val() != null && $(this).val() != "") {
                    newValues[this.id]=$(this).val();
                }
            }
        });  
        
        console.log(newValues);
        
        $.when(accountUpdate(userId, newValues)).done(function(output) { 
            console.log('Updated');
            console.log(output);
        })          
    })
    
});