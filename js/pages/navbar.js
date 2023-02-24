$(function() {
    var status=getStatus();
    var views=Object.values(status.caseviews);
    //console.log(views);
    var settings=getSettings('OpenCaseTrackerSystem');
    var userId=settings.user_id;
    var caseviewcount=0;
    $('#casemenuitems').append("<div class='dropdown-divider'></div><div class='m-1'>Recently viewed</div>");
    views.sort((a, b) => b.viewed - a.viewed); //Sort views by viewed date
    $.each(views, function(i, caseview) {
        if(parseInt(caseview.userid)==userId && caseviewcount < 15) {
            caseviewcount++;
            //console.log(caseview);
            $('#casemenuitems').append("<a class='dropdown-item pl-1 ml-0 smaller' href='index.php?page=case&case="+caseview.caseid+"'><img src='images/link.svg' class='p-1' width='20px'> #"+caseview.caseid+" ("+caseview.title+" - "+caseview.client+") ["+timestamp2date(caseview.viewed, 'g:ia dd MM yy')+"]</a>");
        }
    });
    
});