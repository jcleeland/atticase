$(function() {
    var status=getStatus();
    //console.log(status);
    if(status.caseviews != undefined) {
        const caseviews=status.caseviews;
        
        //console.log(caseviews);
        for(const key in caseviews) {
            caseview=caseviews[key];
            //console.log(caseview);
            if(caseview.userid==$('#userid').val()) {
            $('#case_viewing_history').append('<div viewed="'+caseview.viewed+'" caseid="'+caseview.caseid+'" client="'+caseview.caseclient+'"><a href="index.php?page=case&case='+caseview.caseid+'">Case #'+caseview.caseid+'</a>: '+caseview.client+' ['+caseview.title+'] | Accessed: '+timestamp2date(caseview.viewed, 'g:ia dd MM yy')+'</div>');                
            }

        }
        sortDivsByAttribute("#case_viewing_history", "viewed", "desc");
    }
    $('#clear_case_history').click(function() {
        if (confirm('Are you sure you want to clear your case browsing history?')) {
            delete status.caseviews;
            //Object.defineProperty(status, "caseviews", {value: {null}, enumerable: true});
            setStatus(status);
        }
    })
    $('#logoutcookies').click(function() {
        if (confirm('Are you sure you want to clear your entire cookie? This will also log you out.')) {
            window.location.href="index.php?logout=true&clearcookies=true";    
        }
    })
    
    $('#dateformat').change(function() {
        console.log('Dateformat Changed');
        updateLayoutPreview();
    });

    $('#save_dashboard').click(function() {
        var userId=$('#userid').val();
        var dateformat=$('#dateformat').val();
        var item1=$('#dateformatItem1').val();
        var item2=$('#dateformatItem2').val();
        var item3=$('#dateformatItem3').val();
        var item4=$('#dateformatItem4').val();
        var dateformat_extended=item1+','+item2+','+item3+','+item4;
        var newValues={};
        newValues['dateformat']=dateformat;
        newValues['dateformat_extended']=dateformat_extended;
        console.log(newValues);
        $.when(accountUpdate(userId, newValues)).done(function(output) { 
            console.log('Saved account settings');
            if(output==1) {
                showMessage('Settings Saved', 'Your dashboard changes have been saved.', 'success', false);
            } else if(output !== false) {
                showMessage('No changes', 'There were no changes to save.', 'info', false);
            } else {
                showMessage('Error', 'There was an error saving your settings. Please try again.', 'error', false);
            }
            console.log(output);
        })        
    })
    $('#save_changes').click(function () {
        var userId=$('#userid').val();
        //Gather all the values
        var newValues={};
        $('.updateaccount').each(function(i, obj) {
            if($(this).is(':checkbox')) {
                if($(this).is(':checked')) {
                    newValues[this.id]=1;
                } else {
                    newValues[this.id]=0;
                }
            } else {
                if(typeof $(this).val() !== "undefined" && $(this).val() != null && $(this).val() != "") {
                    newValues[this.id]=$(this).val();
                }
            }
        });  
        
        console.log(newValues);
        
        $.when(accountUpdate(userId, newValues)).done(function(output) { 
            console.log('Save changes done');
            console.log(output);
            if(output==1) {
                showMessage('Settings Saved', 'Your account changes have been saved.', 'success', false);
            } else if(output !== false) {
                showMessage('No changes', 'There were no changes to save.', 'info', false);
            } else {
                showMessage('Error', 'There was an error saving your account settings. Please try again.', 'error', false);
            }
        })          
    })
    
    updateLayoutPreview();
});

function updateLayoutPreview() {
    const layout = $('#dateformat').val();
    console.log('Updating Layout');
    console.log(layout);
    const width = 100;  // Set your desired width here
    const height = 100; // Set your desired height here
    let previewHtml = '';

    switch (layout) {
        case '1c2i':
            previewHtml = `
                <svg viewBox="0 0 100 100" class="layout-diagram" width="${width}">
                    <rect x="0" y="0" width="100" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="0" y="50" width="100" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <text x="50" y="25" alignment-baseline="middle" text-anchor="middle" font-size="12">1</text>
                    <text x="50" y="75" alignment-baseline="middle" text-anchor="middle" font-size="12">2</text>
                </svg>
                <div class="figure-caption">1 column, 2 rows</div>
            `;
            break;
        case '1c4i':
            previewHtml = `
                <svg viewBox="0 0 100 150" class="layout-diagram" width="${width}">
                    <rect x="0" y="0" width="100" height="37.5" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="0" y="37.5" width="100" height="37.5" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="0" y="75" width="100" height="37.5" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="0" y="112.5" width="100" height="37.5" fill="none" stroke="black" stroke-width="1"/>
                    <text x="50" y="18.75" alignment-baseline="middle" text-anchor="middle" font-size="8">1</text>
                    <text x="50" y="56.25" alignment-baseline="middle" text-anchor="middle" font-size="8">2</text>
                    <text x="50" y="93.75" alignment-baseline="middle" text-anchor="middle" font-size="8">3</text>
                    <text x="50" y="131.25" alignment-baseline="middle" text-anchor="middle" font-size="8">4</text>
                </svg>

                <div class="figure-caption">1 column, 4 rows</div>
            `;
            break;
        case '2c3i':
            previewHtml = `
                <svg viewBox="0 0 100 100" class="layout-diagram" width="${width}">
                    <rect x="0" y="0" width="100" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="0" y="50" width="50" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="50" y="50" width="50" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <text x="50" y="25" alignment-baseline="middle" text-anchor="middle" font-size="12">1</text>
                    <text x="25" y="75" alignment-baseline="middle" text-anchor="middle" font-size="12">2</text>
                    <text x="75" y="75" alignment-baseline="middle" text-anchor="middle" font-size="12">3</text>
                </svg>
                <div class="figure-caption">2 rows, top 1 column, bottom 2 columns</div>
            `;
            break;
        case '2c3ib':
            previewHtml = `
                <svg viewBox="0 0 100 100" class="layout-diagram" width="${width}">
                    <rect x="0" y="0" width="50" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="50" y="0" width="50" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="0" y="50" width="100" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <text x="25" y="25" alignment-baseline="middle" text-anchor="middle" font-size="12">1</text>
                    <text x="75" y="25" alignment-baseline="middle" text-anchor="middle" font-size="12">2</text>
                    <text x="50" y="75" alignment-baseline="middle" text-anchor="middle" font-size="12">3</text>
                </svg>
                <div class="figure-caption">2 rows, top 2 columns, bottom 1 column</div>
            `;
            break;
        case '2c1f2h':
            previewHtml = `
                <svg viewBox="0 0 100 100" class="layout-diagram" width="${width}">
                    <rect x="0" y="0" width="50" height="100" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="50" y="0" width="50" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="50" y="50" width="50" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <text x="25" y="50" alignment-baseline="middle" text-anchor="middle" font-size="12">1</text>
                    <text x="75" y="25" alignment-baseline="middle" text-anchor="middle" font-size="12">2</text>
                    <text x="75" y="75" alignment-baseline="middle" text-anchor="middle" font-size="12">3</text>
                </svg>
                <div class="figure-caption">2 columns, 1st column double height, 2nd column 2 rows</div>
            `;
            break;
        case '2c41':
        default:
            previewHtml = `
                <svg viewBox="0 0 100 100" class="layout-diagram" width="${width}">
                    <rect x="0" y="0" width="50" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="50" y="0" width="50" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="0" y="50" width="50" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <rect x="50" y="50" width="50" height="50" fill="none" stroke="black" stroke-width="1"/>
                    <text x="25" y="25" alignment-baseline="middle" text-anchor="middle" font-size="12">1</text>
                    <text x="75" y="25" alignment-baseline="middle" text-anchor="middle" font-size="12">2</text>
                    <text x="25" y="75" alignment-baseline="middle" text-anchor="middle" font-size="12">3</text>
                    <text x="75" y="75" alignment-baseline="middle" text-anchor="middle" font-size="12">4</text>
                </svg>
                <div class="figure-caption">2 columns, 2 rows</div>
            `;
            break;
    }
    
    $('#layout-preview').html(previewHtml);
}