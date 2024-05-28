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
                        window.location.href="?page=options&option=systemsettings";   
                    })
                } else {
                    $.when(systemSettingsUpdate(values, wheres)).done(function(output) {
                        //console.log(output);
                        $('#undoSystemSettingsBtn').hide();
                        $('#saveSystemSettingsBtn').removeClass("pale-green-link"); 
                        window.location.href="?page=options&option=systemsettings";   
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

function toggleVisibility(setting, comparitorkey, comparitorvalue) {
    console.log('Seeting, key and value are: ' + setting, comparitorkey, comparitorvalue);
    if ($('#config_' + comparitorkey).is(':checkbox')) {
        // Handle checkboxes
        var isChecked = $('#config_' + comparitorkey).is(':checked');
        var comparitorBool = (comparitorvalue.toLowerCase() === 'true');

        if (isChecked == comparitorBool) {
            $('#systemsetting_' + setting).show();
        } else {
            $('#systemsetting_' + setting).hide();
        }
    } else {
        
        // Handle other input types
        if ($('#config_' + comparitorkey).val() == comparitorvalue) {
            $('#systemsetting_' + setting).show();
        } else {
            $('#systemsetting_' + setting).hide();
        }
    }
}

function testEmailSend() {
    var to=$('#config_admin_email').val();
    var from=$('#config_retrievalaccount' ).val();
    console.log('From: '+from);
    var fromname=$('#config_project_title').val();
    var cc=[];
    var bcc=[];
    var isHtml=1;
    var subject='Test AttiCase Email';
    var message='<b>Test outgoing email from AttiCase</b><br />If you have received this email then your AttiCase installation is able to send emails.';
    var attachments=[];
    $.when(sendEmail(to, toname, from, fromname, cc, bcc, isHtml, subject, message, attachments)).done(function(output) {
        console.log(output);
    })
}
