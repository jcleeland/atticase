$(function() {
    if($('#nocase').val() != 0) {
        loadPois();
    }
    $('#newpoi_step2').hide();
    $('#newpoi_step3').hide();
    
    $('#newPoiBtn').click(function() {
        $('#newPoiForm').toggle();    
    });
    
    $('#newpoi_step1button').click(function() {
        $('#newpoi_step1').hide();
        $('#newpoi_step2').show();
        $('#newpoi_step3').hide();
    })
   
    $('.cancelpoiperson').click(function() {
        $('#newpoi_step2').hide();
        $('#newpoi_step3').hide();
        $('#newpoi_step1').show();
    })
    $('#cancelPoiConnectionBtn').click(function() {
        $('#newpoi_step2').hide();
        $('#newpoi_step3').hide();
        $('#newpoi_step1').show();
    })
    
    
    $('#newPoiPerson').keyup(function() {
        value=$(this).val();
        //console.log('waiting');
        console.log($(this).val().length);
        if($(this).val().length > 0) {
            delay(function() {
                const menu = $('#poi-dropdown-menu');
                menu.html('');
                //console.log(value);
                $.when(poiPersonLookup(value)).done(function(output) {
                    //console.log(output);
                    const menu = $('#poi-dropdown-menu');
                    //console.log(menu);
                    if(parseInt(output.count) > 0) {
                        $.each(output.results, function(i, person) {
                            menu.append('<li value="'+person.id+'" onClick="selectPoiPerson(\''+person.id+'\',\''+person.firstname+' '+person.lastname+'\')">'+person.firstname+' '+person.lastname+' ('+person.position+': '+person.organisation+')</li>\n');
                            menu.show();
                            //$('#newpoi_step1button').hide();   
                        })
                    } else {
                        $('#newpoi_step1button').show();
                    }
                })
            }, 1000);        
            
        } else {
            console.log('Not searching');
            const menu = $('#poi-dropdown-menu');
            menu.html('');
            menu.hide();            
        }

        
    }) 
    
    $('.createpoiperson').click(function() {
        var firstname=$('#firstname').val();
        var lastname=$('#lastname').val();
        var position=$('#position').val();
        var organisation=$('#organisation').val();
        var phone=$('#phone').val();
        var email=$('#email').val();
        //console.log(firstname, lastname, position, organisation, phone, email);
        if(firstname != "" && lastname != "") {
            $.when(poiPersonCreate(firstname, lastname, position, organisation, phone, email)).done(function(output) {
                if(output.results != "Error - No poi action provided") {
                    $('#newpoiconnectionname').text(firstname+' '+lastname);
                    $('#newpoi_step2').hide();
                    $('#newpoi_step3').show();
                    $('#newPoiPersonId').val(output.insertid);
                    //console.log(output);
                }
            })
        } else {
            alert("There must be at least a first and last name");
        }
        
    });    
    
    $('#submitPoiBtn').click(function() {
        var userId=globals.user_id;
        var caseId=$('#caseid').val();
        var poiComment=$('#newPoiComment').val();
        var poiPersonId=$('#newPoiPersonId').val();
        //console.log('POI: '+poiComment);
        var time=Math.floor(Date.now() / 1000);
        $.when(poiLinkCreate(caseId, poiPersonId, poiComment)).done(function(insert) {
            if(insert.count=="1") {
                $('#fileDesc').val('');
                $('#newAttachmentForm').toggle();
                $('#newpoi_step3').hide();
                $('#newpoi_step1').show();
                $('#newPoiForm').hide();
                
                historyCreate(caseId, userId, '60', null, null, poiComment);
                loadPois();
                loadHistory();
            }
        })
        //Successfully added
    })
    
    $('#filterPois').keyup(function() {
        //console.log($(this).val());
               
        var search=$(this).val();
        $('.poiitem').each(function() {
            if($(this).html().toUpperCase().includes(search.toUpperCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    })
}) 

function selectPoiPerson(poiId, poiName) {
    //console.log('Selected person '+poiId);  
    $('#poi-dropdown-menu').hide();
    $('#newpoiconnectionname').text(poiName);
    $('#newPoiPersonId').val(poiId);
    $('#newpoi_step1').hide();
    $('#newpoi_step3').show();
}

function loadPois() {
    var today=new Date();
    var caseId=$('#caseid').val();
    
    var parameters={};
    parameters[':taskid']=caseId;
    //parameters[':assignedto']=$('#user_id').val();
    //parameters[':isclosed']=1;
    //parameters[':datedue']=today.getTime() / 1000 | 0;
    
    //var conditions='assigned_to = :assignedto AND is_closed != :isclosed AND date_due <= :datedue';
    
    var conditions='task_id = :taskid'
    
    var order='poi.created DESC';
    
    var start=parseInt($('#poistart').val()) || 0;
    var end=parseInt($('#poiend').val()) || 90000000;
    
    /* if(start<0) {
        start=0;
        $('#todostart').val(0);
    }
    if(end<9) {
        end=9;
        $('#todoend').val(9);
    }  */
    
    //console.log('Doing comments, '+start+' to '+end);
    
    $('#poilist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Loading people of interest...</center>");
    
    
    $.when(poiList(parameters, conditions, order, start, end)).done(function(pois) {
        //console.log('POIs');
        //console.log(pois);
        if(pois.count<1) {
            //console.log('Nothing');
            $('#poilist').html("<center><br />No people of interest for this case yet<br />&nbsp;</center>");
            $('#poicount').html('');
        } else {
            //pagerNumbers('todo', start, end, cases.total);
            $('#poilist').html('');
            $('#poicount').html(pois.count);
            $.each(pois.results, function(i, poidata) {
                
                var parentDiv='poilist';
                var uniqueId='poi'+poidata.poi_id;
                var name=poidata.firstname+" "+poidata.lastname
                var primeBox=name;
                var briefPrimeBox=getInitials(name);
                var dateBox=timestamp2date(poidata.created, 'dd/mm/yy g:i a');
                var briefDateBox=timestamp2date(poidata.created, 'dd MM YY');
                var actionPermissions=null;
                if(globals.user_id==poidata.user_id || globals.is_admin=='1') {
                    actionPermissions=['edit', 'delete'];    
                }                
                var header='<span class="d-xs-block dsm-block d-md-none d-lg-none d-xl-none font-weight-bold">'+name+': </span>'+poidata.organisation;
                var content=deWordify(poidata.comment);
    
                insertTabCard(parentDiv, uniqueId, primeBox, briefPrimeBox, dateBox, briefDateBox, actionPermissions, header, content);                

            })
            
        }
    }).then(function() {
        //toggleCaseCards();
        //toggleDatepickers();        
    }).fail(function() {
        //console.log('Nothing found');
        $('#poilist').html("<center><img src='images/logo.png' width='50px' /><br />No people of interest for this case yet</center>");
        //pagerNumbers('todo', 0, 0, 0);
    });    
}