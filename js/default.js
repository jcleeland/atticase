$(document).ready(function(){
    //$('.header').height($(window).height());
    $('#goto').keypress(function (e) {
        if(e.which==13) {
            window.location.href="index.php?page=case&case="+$('#goto').val();
        }
    })
    
    $('.datepicker').each(function() {
        //console.log($(this));
        //console.log($(this).style.width);
        var fontSize=parseInt($(this).css("font-size"));
        $(this).css("width", (($(this).val().length+1)*(fontSize/2))+'px');
        var height=parseInt($(this).css("height"));
        $(this).css("height", (height-2)+'px');
        console.log("After:" + $(this).css("height"));
        $(this).datepicker({dateFormat: "dd/mm/yy"});
        //this.style.width=((this.value.length+1)*8)+'px';
        
    })
    
    $('.nav-link-tab').click(function () {
        if($('#case-card').first().is(":visible")) {
            $('#toggle-case-card').click();
        }
    })
    $('#toggle-case-card').click(function() {
        if($('#case-card').first().is(":visible")) {
            console.log('Toggling icon to view');
            $('#case-card-toggle-image').attr("src", "images/caret-bottom.svg");
            $('#case-card-toggle-image').attr("title", "View case details"); 
        } else {
            console.log('Toggling icon to hide');
            $('#case-card-toggle-image').attr("src", "images/caret-top.svg");
            $('#case-card-toggle-image').attr("title", "Hide case details");
        }
    })
    
    $('.case-link').each(function(i, obj) {
        var caseid=$(this).html();
        $(this).html("<a href='index.php?page=case&case="+caseid+"'>#"+caseid+"</a>");
        //console.log(caseid);
    })
})