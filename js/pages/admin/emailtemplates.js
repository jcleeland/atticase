$(function (){
    templateGroupFilter($('#emailtemplatefilter').val());
    $('#emailtemplatefilter').change(function() {
        templateGroupFilter($(this).val());
    });
    $('.emailmessagebody').each(function() {
        var content = $(this).val();
    
        if (isHTML(content)) {
            $(this).summernote({
              height: 200, // set editor height
              minHeight: null, // set minimum height of editor
              maxHeight: null, // set maximum height of editor
            });
          } else {
            $(this).summernote({
              height: 200, // set editor height
              minHeight: null, // set minimum height of editor
              maxHeight: null, // set maximum height of editor
              /*callbacks: {
                onInit: function() {
                  var code = $(this).summernote('code');
                  $(this).summernote('code', code.replace(/\n/g, '<br>'));
                }
              } */
              callbacks: {
                onInit: function() {
                  var code = $(this).summernote('code');
                  var blocks = code.split('\n');
                  var formatted = blocks.map(block => block.trim() !== '' ? '<p>' + block + '</p>' : '').join('\n');
                  $(this).summernote('code', formatted);
                }
              }
            });
          }
      });
});

function templateGroupFilter(value) {
    $('.emailtemplate').each(function() {
        if(value == 'all') {
            $(this).show();
            return;
        } else {
            var templateGroup = $(this).data('group-id');
            if(value=='user') {
                if (templateGroup == 999) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            } else {
                if (templateGroup != 999) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
                
            }
        }
        
    });
}