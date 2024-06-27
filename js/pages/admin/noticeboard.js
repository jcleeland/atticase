$(function() {
    toggleDatepickers();
    $('.summernoteme').each(function() {      
        $(this).summernote({
            height: 200, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
        });
    });

    $('.updatenoticeboarditem').click(function() {
        var id = $(this).data('noticeboardid');
        console.log('Updating id '+id);
        var title = $('#title_' + id).val();
        var message = $('#message_' + id).val();
        var publish_date = $('#publish_date_' + id).val();
        var expiry_date = $('#expiry_date_' + id).val();
        var published = $('#published_' + id).val();
        var created_by=$('#created_by_'+id).val();
        var allow_comments=$('#allow_comments_'+id).val();
        
        $.when(noticeboardUpdate(id, title, message, publish_date, expiry_date, published, created_by, allow_comments)).done(function(output) {
            $('#noticeboard_' + id).addClass('fieldUpdated');
        })
        
    });
    $('.createnoticeboarditem').click(function() {
        var title = $('#newtitle').val();
        var message = $('#newmessage').val();
        var publish_date = $('#newpublish_date').val();
        var expiry_date = $('#newexpiry_date').val();
        var published = $('#newpublished').val();
        var created_by=$('#newcreated_by').val();
        var allow_comments=$('#newallow_comments').val();
        
        $.when(noticeboardCreate(title, message, publish_date, expiry_date, published, created_by, allow_comments)).done(function(output) {
            if(output.results != "Error - No category name provided") {
                $(location).attr("href", "?page=options&option=noticeboard");
            }
        })
        
    });
});