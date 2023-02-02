$(function() {
    var status=getStatus();
    console.log(status);
    if(status.caseviews != undefined) {
        const caseviews=status.caseviews;
        for(const key in caseviews) {
            console.log(key+' -> '+caseviews[key]);
            $('#case_viewing_history').append('<div>Case #'+key+': '+timestamp2date(caseviews[key]['timestamp'], 'dd/mm/yy hh:ii')+'</div>');
        }
    }
    $('#clear_case_history').click(function() {
        if (confirm('Are you sure you want to clear your case browsing history?')) {
            status.caseviews={};
            setStatus(status);
        }
    })
    $('#logoutcookies').click(function() {
        if (confirm('Are you sure you want to clear your entire cookie? This will also log you out.')) {
            window.location.href="index.php?logout=true&clearcookies=true";    
        }
    })
    
});