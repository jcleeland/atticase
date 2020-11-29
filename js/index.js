/*
This file contains all the common javascript functions for OpenCaseTracker

*/
$(function() {

})

function caseList(parameters, conditions, order, first, last) {
    //console.log(parameters);
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {parameters: parameters, method: 'caseList', conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    }) 
    
}

function getCase(caseId) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'case', caseId: caseId},
        dataType: 'json'
    })
}

function attachmentList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'attachmentList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function linkedList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'linkedList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function relatedList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'relatedList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function commentList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'commentList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function poiList(parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'poiList', parameters: parameters, conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    });
}

function tableList(tablename, joins, select, parameters, conditions, order, first, last) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {tablename: tablename, joins: joins, select: select, parameters: parameters, method: 'tableList', conditions: conditions, order: order, first: first, last: last},
        dataType: 'json'
    })
}

function toggleCaseCards() {
    $('.case-link').each(function(i, obj) {
        var caseid=$(this).html();
        var plaintext=$(this).text();
        //console.log('HASH COntents: '+plaintext);
        if(!plaintext.includes("#")) {
            $(this).html("<a href='index.php?page=case&case="+caseid+"'>#"+caseid+"</a>");
        }
        //console.log(caseid);
    })    
}

function toggleDatepickers() {
    //console.log('toggling');
    $('.datepicker').each(function() {
        if(!$(this).hasClass('hasDatepicker')) {
            var fontSize=parseInt($(this).css("font-size"));
            $(this).css("width", (($(this).val().length+1)*(fontSize/2))+'px');
            var height=parseInt($(this).css("height"));
            $(this).css("height", (height-2)+'px');
            $(this).datepicker({dateFormat: "dd/mm/yy"});
        }
        
    })    
}

function loadAttachment(origname, filename, filetype) {
    var attachmentDir=$('#attachments_dir').val();
    $.when(readFile(attachmentDir+filename)).done(function(contents) {
        console.log(contents);
        if(contents.length > 0) {
            var data=encodeURI('data:'+filetype+';charset=utf-8,'+contents);
            
            var link = document.createElement("a");
            link.setAttribute("href", data);
            link.setAttribute("download", origname);
            document.body.appendChild(link); // Required for FF

            link.click();    
        } else {
            alert('No file found');
        }
    });
}

function readFile(filename) {
    console.log(filename);
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'readfile', filename: filename},
        dataType: 'binary'
    }).fail(function(xhr, status, error) {
        console.log('Failed to open '+filename+' ---> '+status+': '+error);
    })
}

function saveFile(filename, contents) {
    return $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {method: 'savefile', filename: filename, contents: contents},
        dataType: 'json'
    })    
}

/**
* Exports a div containing data list with div attributes as fields
* 
* @param divId
*/
function exportDivWithAttributes(divId, excludes) {
    var csvExport="";
    var csvFirstLine="";
    var csvRows="";
    if(!excludes) {
        var excludes=new Array('class', 'style', 'id', 'holdername');
    }
    
    $.each($('#'+divId).children('div'), function(x, thisDiv) {
        $.each(thisDiv.attributes, function(i, attribs) {
            if(!excludes.includes(attribs.name)) {
                if(x==0) {
                    //Header Row
                    csvFirstLine += '"'+attribs.name+'",';
                }
                if($(thisDiv).is(":visible")) {
                    csvRows += '"'+attribs.value+'",';
                }
            }
        })
        if(x==0) {
            //strip comma from end of firstline
            csvFirstLine=csvFirstLine.substring(0, csvFirstLine.length-1);
        }
        //strip comma from end of csvrow
        if($(thisDiv).is(":visible")) {
            csvRows=csvRows.substring(0, csvRows.length-1);
            csvRows += "\n";
        }
    })
    
    csvExport=csvFirstLine+"\n"+csvRows;
    
    var data=encodeURI('data:text/csv;charset=utf-8,'+csvExport);
    
    var link = document.createElement("a");
    link.setAttribute("href", data);
    link.setAttribute("download", "export.csv");
    document.body.appendChild(link); // Required for FF

    link.click();

    console.log(csvExport);        
}

/**
* A function to remove weird characters (UTF8 style) from strings that arrive from Word.
* 
* @param string
*/
function deWordify(string) {
    output=string.replace(/\r\n/g, "<br />").replace(/\u00e2\u20ac\u2122/g,"'").replace(/\u00e2\u20ac\u201c/g, "-").replace(/\u00e2\u20ac\u0153/g, '"').replace(/\u00c2/g, "").replace(/\u00e2\u20ac/g, '"').replace(/\\'/g, "'");
    return output;
}

function timestamp2date(timestamp, format) {
    
    var monthNamesShort=["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var monthNamesLong=["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    if(!format) format='dd/mm/yy';
    
    var date=new Date(timestamp * 1000);
    var year=date.getFullYear();
    var month = date.getMonth()+1;
    var monthSearch=month-1;
    var day = date.getDate();
    var hours=date.getHours();
    var hours12=hours;
    var hoursMeridian='AM';
    if(hours12 > 12) {
        hours12=hours12-12;
        hoursMeridian='PM';
    }
    if(hours12==0) hours12=12;
    if(date.getMinutes() > 9) {
        var minutes=date.getMinutes();
    } else {
        var minutes="0"+date.getMinutes();
    }
    if(date.getSeconds() > 9) {
        var seconds=date.getSeconds();
    } else {
        var seconds="0"+date.getSeconds();
    }    
    
    if(format=='d/m/y') var time=day+'/'+month+'/'+year;
    if(format=='y/m/d') var time=year+'/'+month+'/'+day;
    if(format=='dd/mm/yy') var time=pad(day, 2)+'/'+pad(month, 2)+'/'+year;
    if(format=='dd/mm/yy hh:ii') var time=pad(day, 2)+'/'+pad(month, 2)+'/'+year+' '+hours+':'+minutes;
    if(format=='dd/mm/yy g:i a') var time=pad(day, 2)+'/'+pad(month, 2)+'/'+year+' '+hours12+':'+minutes+'&nbsp;'+hoursMeridian;
    if(format=='dd MM') var time=day+' '+monthNamesShort[monthSearch];
    if(format=='dd MMM') var time=day+' '+monthNamesLong[monthSearch];
    if(format=='dd MM YY') var time=day+' '+monthNamesShort[monthSearch]+' '+year;
    if(format=='dd MMM YYYY') var time=day+' '+monthNamesLong[monthSearch]+' '+year;;
    
    return time;
    
}

function pad(n, width, z) {
  z = z || '0';
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

function getInitials(string) {
    var matches = string.match(/\b(\w)/g);
    var initials=matches.join('');
    return initials;
}

function pagerNumbers(pagername, start, end, total) {
            var displayEnd=end+1;
            console.log(start+'->'+end)+1;
            var qty=parseInt(end)-parseInt(start)+1;
            var start=start+1;
            if(displayEnd > parseInt(total)) displayEnd=parseInt(total)-1;
            console.log('Results found - '+displayEnd);
            if(total==0) {
                qty=0;
                start=0;
                end=0;
            }
            
            $('#'+pagername+'position').html((start)+'-'+(displayEnd+1));
            $('#'+pagername+'total').html('of '+total+' cases');  
            $('#'+pagername+'qty').html('Showing '+qty);  
}


function insertCaseCard(parentDiv, uniqueId, casedata) {
    console.log('Inserting Case Card');
    console.log(casedata);
    var thisDateDue=timestamp2date(casedata.date_due);
    if(typeof casedata.pref_name !== 'undefined') {
        var client=casedata.pref_name+' '+casedata.surname;
    } else {
        var client=casedata.member;
    }
    var dateclass='date-future';
    var lasteditedby=(casedata.last_edited_real_name) ? casedata.last_edited_real_name : "Unknown";
    var assignedto=(casedata.assigned_real_name) ? casedata.assigned_real_name : 'Unassigned';
    if(casedata.date_due < $('#today_start').val()) {dateclass='date-overdue';}
    if(casedata.date_due >= $('#today_start').val() && casedata.date_due <= $('#today_end').val()) {dateclass='date-due';}
    console.log(parentDiv+' for case '+casedata.task_id+': Date Due: '+casedata.date_due+' - today_start: '+$('#today_start').val());
    
    $('#'+parentDiv).append('<div class="row m-1" id="caseCardParent_'+uniqueId+'"></div>');
        
    $('#caseCardParent_'+uniqueId).append('<div class="float-left p-0 col m-0" style="min-width: 56px; max-width: 65px;" id="leftCaseCol_'+uniqueId+'"></div>');
    $('#caseCardParent_'+uniqueId).append('<div class="float-left col p-0 " style="border-top: 1px solid #6ab446" id="rightCaseCol_'+uniqueId+'"></div>');
    
    $('#leftCaseCol_'+uniqueId).append('<div id="casePrimeBox_'+uniqueId+'" class="casePrimeBox text-center case-link"></div>');
    $('#rightCaseCol_'+uniqueId).append('<div id="caseMain_'+uniqueId+'" class="card-body p-0"></div>');
    
    $('#rightCaseCol_'+uniqueId).append('<div class="card-header small p-2 ml-1" id="caseheader_'+uniqueId+'"></div>');
    $('#rightCaseCol_'+uniqueId).append('<div class="card-body collapse p-1" id="casedetails_'+uniqueId+'"></div>');
    //$('#rightCaseCol_case'+casedata.task_id).append('<div class="card-footer small font-italic pt-1 pb-1 text-muted" id="casefooter_'+casedata.task_id+'"></div>');
    
    $('#casePrimeBox_'+uniqueId).append(casedata.task_id);
    
    //Right Col
    $('#caseheader_'+uniqueId).append("<div class='float-right mr-2 border rounded pl-1 pr-1 calendar-div pointer "+dateclass+"'><input type='text' id='caselist_date_due_"+casedata.task_id+"' class='datepicker' value='"+thisDateDue+"' /></div>");
    $('#caseheader_'+uniqueId).append("<div class='d-md-block d-lg-block d-xl-block d-none d-sm-none d-xs-none officer float-right m-0 mb-1 mr-1 border rounded pl-1 pr-1' id='officer_"+casedata.assigned_to+"'>"+assignedto+"</div>");
                    
    $('#caseheader_'+uniqueId).append("<div class='float-left border rounded pl-1 pr-1 mr-2 client-link userlink-"+casedata.member_status+"'>"+client+"<a class='fa-userlink' href=''></a></div>");
    $('#caseheader_'+uniqueId).append("<div class='float-left p-0 display-7'><a data-toggle='collapse' href='#case-card' aria-expanded='true' aria-controls='case-card' id='toggle-case-card_"+uniqueId+"' onClick='toggleDetails(\""+uniqueId+"\")' ><img id='toggledetails_"+uniqueId+"' src='images/caret-bottom.svg' class='img-thumbnail float-left mr-2 mt-1 toggledetails' width='20px' title='Show case details' /></a><span  onClick='toggleDetails(\""+uniqueId+"\")'>"+casedata.item_summary+"</span></div>");
    $('#caseheader_'+uniqueId).append("<div style='clear: both'></div>");
    //Case description
    $('#casedetails_'+uniqueId).append("<p class='card-text small overflow-auto' style='max-height: 100px'>"+deWordify(casedata.detailed_desc)+"</p>");
    $('#casedetails_'+uniqueId).append("<div class='d-xs-block d-sm-block d-md-none d-lg-none d-xl-none officer float-right m-0 mb-1 border rounded pl-1 pr-1 small' id='officer_"+casedata.assigned_to+"'>"+assignedto+"</div>");

}

function insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content) {
    
    //Parent Tab
    $('#'+parentDiv).append('<div class="row m-2" id="tabCardParent_'+uniqueId+'"></div>');
    
    //Children of parent
    $('#tabCardParent_'+uniqueId).append('<div class="col-2 float-left pl-0" id="leftTabCol_'+uniqueId+'"></div>');
    $('#tabCardParent_'+uniqueId).append('<div class="col-10 float-left tabSpeechBubble" id="rightTabCol_'+uniqueId+'"></div>');
    
    //Children of columns
    $('#leftTabCol_'+uniqueId).append('<div class="tabPrimeBox text-center" id="tabPrimeBox_'+uniqueId+'"></div>');
    $('#leftTabCol_'+uniqueId).append('<div class="smaller text-center" id="tabDate_'+uniqueId+'"></div>');
    $('#leftTabCol_'+uniqueId).append('<div class="text-right" id="tabActions_'+uniqueId+'"></div>');
    
    if(header !== null) {
        $('#rightTabCol_'+uniqueId).append('<div class="card-header" id="cardheader_'+uniqueId+'"></div>');
    }
    $('#rightTabCol_'+uniqueId).append('<div class="card-body small overflow-auto" style="max-height: 200px;" id="cardbody_'+uniqueId+'"></div>');
    
    //Inserting Data
    $('#tabPrimeBox_'+uniqueId).append('<span class="d-xs-block dsm-block d-md-none d-lg-none d-xl-none" title="'+$(primeBox).text()+'">'+briefPrimeBox+'</span>');
    $('#tabPrimeBox_'+uniqueId).append('<span class="d-none d-md-block d-lg-block d-xl-block">'+primeBox+'</span>');
    
    $('#tabDate_'+uniqueId).append('<span class="d-xs-block d-sm-block d-md-none d-lg-none d-xl-none" title="'+dateBox+'">'+briefDateBox+'</span>');
    $('#tabDate_'+uniqueId).append('<span class="d-none d-md-block d-lg-block d-xl-block">'+dateBox+'</span>');
    
    if(header !== null) {
        $('#cardheader_'+uniqueId).append(header);
    }
    $('#cardbody_'+uniqueId).append(content);
    
}

function toggleDetails(id) {
    console.log('Toggling id: '+id);
    //console.log($('#toggledetails_'+id).attr('src'));
    if($('#toggledetails_'+id).attr('src')=="images/caret-top.svg") {
        console.log('Toggling icon to view');
        $('#toggledetails_'+id).attr("src", "images/caret-bottom.svg");
        $('#case-card-toggle-image').attr("title", "View case details");
        $('#casedetails_'+id).hide(); 
    } else {
        console.log('Toggling icon to hide');
        $('#toggledetails_'+id).attr("src", "images/caret-top.svg");
        $('#toggledetails_'+id).attr("title", "Hide case details");
        $('#casedetails_'+id).show();
    }
}