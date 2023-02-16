$(function() {
    $('.updatesettings').on('input, change', function(x) {
        $('#saveSystemSettingsBtn').addClass("pale-green-link");
        $('#'+this.id).addClass('changed');
        $('#undoSystemSettingsBtn').show();
    })
    
    $('#saveSystemSettingsBtn').click(function() {
        var values={};
        var wheres='';
        /* Update table_prefs with:
            pref_id (unique key)
            pref_name (also unique key)
            pref_value (value)
            pref_desc (don't change)
            pref_group (don't change')
            Update table_prefs set pref_value='new value' where pref_name='pref_name'
            
        */
        $('.updatesettings').each(function(i, obj) {
            //console.log(this.id);
            if($(this).hasClass('changed')) {
                if($(this).is(':checkbox')) {
                    //console.log($(this).val());
                    if($(this).is(':checked')) {
                        values['pref_value']='1';
                    } else {
                        values['pref_value']='0';
                    }                    
                } else {
                    values['pref_value']=$(this).val();
                }
                wheres="pref_name='"+this.id.substr(7)+"'";
                if($(this).hasClass('new')) {
                    //console.log(this.title);
                    values['pref_desc']=this.title;
                    values['pref_name']=this.id.substr(7);
                    //console.log(values);
                    $.when(systemSettingsCreate(values, wheres)).done(function(output) {
                        //console.log('Done');
                        //console.log(output);
                        $('#undoSystemSettingsBtn').hide();
                        $('#saveSystemSettingsBtn').removeClass("pale-green-link");    
                    })
                } else {
                    $.when(systemSettingsUpdate(values, wheres)).done(function(output) {
                        //console.log(output);
                        $('#undoSystemSettingsBtn').hide();
                        $('#saveSystemSettingsBtn').removeClass("pale-green-link");    
                    })
                }
                $(this).removeClass('changed');
                $(this).removeClass('new');                
            }
            //Call javascript function that initiates updateTable - presumably ajax script  
            //updateTable($tablename, $updates, $wheres, $userid, $debug=0)
              
        });
    })

});