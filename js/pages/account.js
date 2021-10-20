$(function() {
    var status=getStatus();
    console.log(status);
    if(status.caseviews != undefined) {
        const caseviews=status.caseviews;
        for(const key in caseviews) {
            console.log(key+' -> '+caseviews[key]);
            $('#case_viewing_history').append('<li>Case #'+key+'</li>');
        }
    }
    $('#clear_case_history').click(function() {
        if (confirm('Are you sure you want to clear your case history?')) {
            status.caseviews={};
            setStatus(status);
        }
    })
    
});