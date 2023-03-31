/*
 * Copyright [2022] [Jason Alexander Cleeland, Melbourne Australia]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
$(function() {
    var status=getStatus();
    var views=Object.values(status.caseviews);
    var newViews={};
    //console.log(status);
    var settings=getSettings('OpenCaseTrackerSystem');
    var userId=settings.user_id;
    var caseviewcount=0;
    $('#casemenuitems').append("<div class='dropdown-divider'></div><div class='m-1'>Recently viewed</div>");
    views.sort((a, b) => b.viewed - a.viewed); //Sort views by viewed date
    $.each(views, function(i, caseview) {
        if(parseInt(caseview.userid)==userId && caseviewcount < 10) {
            caseviewcount++;
            //console.log(caseview);
            newViews['case'+caseview.caseid]=caseview;
            $('#casemenuitems').append("<a class='dropdown-item pl-1 ml-0 smaller' href='index.php?page=case&case="+caseview.caseid+"'><img src='images/link.svg' class='p-1' width='20px'> #"+caseview.caseid+" ("+caseview.title+" - "+caseview.client+") ["+timestamp2date(caseview.viewed, 'g:ia dd MM yy')+"]</a>");
        }
    });
    //Remove all but the most recent 10 case views - this is to manage the total size of the status cookie which can't exceed 4096bytes
    status.caseviews = newViews;
    setStatus(status);    
    
});