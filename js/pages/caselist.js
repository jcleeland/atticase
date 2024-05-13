var expiredMembers=[];

$(function() {
    loadCaselist();
    toggleCaseCards();
    toggleDatepickers();
    
    var status=getStatus();
    //console.log('Status');
    //console.log(status);
    
    var settings=getSettings();
    //console.log('Settings');
    //console.log(settings);
    
    $('#caselistqty').change(function() {
        loadCaselist();
    })
    
    $('#caselist-inpage_filter').keyup(function(e) {
        var text=$(this).val();
        delay(function() {
            //console.log('Searching '+text+' and using delay');
            searchDivsByText('caselist', text, 1);    
        }, 1500);
    })
    
});

function loadCaselist(reset) {
    return new Promise((resolve, reject) => {
        var today=new Date();
        console.log('STATUS PRIOR TO LOADING CASES');
        var status=getStatus();
        console.log(status);
        var parameters={};
        var conditions='';
        
        var conditions='1=1';
        
        var order='date_due ASC';
        var status=getStatus();
        if(status.orders != undefined) {
            if(status.orders.caselist !== undefined) {
                const orders=status.orders['caselist'];
                var counter=0;
                for (const key in orders) {
                    if(counter==0) {
                        order='';
                    }
                    //console.log(`${key}: ${orders[key]}`);
                    if(counter > 0) {
                        order+=', ';
                    }
                    order+=key+' '+orders[key]+'';
                    counter++;
                }
                //console.log('ORDERS:');
                //console.log(orders); 
                //console.log(order);       
            }
        }

        // Check for filter settings
        
        if($('#userSelect').val() != '') {
            if($('#userSelect').val() == 'null') {
                conditions+=' AND u.user_id is null';
            } else {
                parameters[':userid']=$('#userSelect').val();
                conditions+=' AND u.user_id = :userid';
            }
            
        }
        
        if($('#caseTypeSelect').val() != '') {
            //console.log('Case Type: '+$('#caseTypeSelect').val());
            parameters[':casetype']=$('#caseTypeSelect').val();
            conditions+=' AND t.task_type = :casetype';
        }
        
        if($('#departmentSelect').val() != '') {
            parameters[':department']=$('#departmentSelect').val();
            conditions+=' AND t.product_category = :department';
        }
        
        if($('#caseGroupSelect').val() != '') {
            parameters[':casegroup']=$('#caseGroupSelect').val();
            conditions += ' AND t.product_version = :casegroup';
        }
        
        if($('#statusSelect').val() != '') {
            if($('#statusSelect').val()=='1') {
                parameters[':isClosed']=$('#statusSelect').val();
                conditions+=' AND t.is_closed = :isClosed';
            }
            if($('#statusSelect').val()=='0') {
                conditions+=' AND t.is_closed != 1';
            }
        }
    
        if($('#casetext').val() != '') {
            parameters[':text']='%'+$('#casetext').val()+'%';
            conditions += ' AND CONCAT(t.item_summary, t.detailed_desc, t.member, t.name, t.resolution_sought) like :text';
        }
    
        //MANAGE THE PAGER
        var pagerSettings=pagerNumberSettings('caselist');
        //console.log('Pager Settings');
        //console.log(pagerSettings);
        if(reset && reset == 1) {
            //IN A NEW SEARCH, RESET THE PAGER VALUES (reset value has been set to 1)
            //console.log('Resetting pager values');
            var qty=10;
            var start=1;
            var end=10;
        } else {
            //IN AN OLD SEARCH, KEEP THE PAGER VALUES
            //console.log('Reusing old pager values');
            //console.log($('#caselistqty').val());
            if(parseInt($('#caselistqty').val())==0 || $('#caselistqty').val()=="") {
                var qty=parseInt(pagerSettings.qty);
                if(isNaN(qty)) {
                    qty=10;
                }       
                var start=pagerSettings.start;
                if(isNaN(start)) {
                    start=0;
                }
                var end=pagerSettings.start+qty-1;
                //console.log('Old pager quantity setting was zero or absent - resetting to default (10)');
            } else {
                var qty=parseInt($('#caselistqty').val());
                var start=parseInt($('#caseliststart').attr("value"));
                var end=qty+start-1;
                //console.log('Reusing web page settings: Qty-'+qty+', Start-'+start+', End-'+end);
            }
            
        }
    
    
        $('#caselist').html("<center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>");
        
        $.when(caseList(parameters, conditions, order, start, end)).done(function(cases) {
            //console.log('Cases');
            //console.log(cases);
            //console.log(cases.query);
            if(cases.count===0) {
                //console.log('Nothing');
                $('#caselist').html("<center><br />No cases in list<br />&nbsp;</center>");
            } else {
                //console.log('Updating pager with '+start+', '+end+', '+cases.total);
                pagerNumbers('caselist', start, end, cases.total);
                $('#caselist').html('');
                $.each(cases.results, function(i, casedata) {
                    if('clientModified' in casedata) { //clientModified should only exist if an external database is being used... 
                        if(casedata.clientModified == null) {
                            //Create a list of external db members that need updating
                            expiredMembers.push(casedata.member);
                        } else {
                            var date = new Date(casedata.clientModified * 1000);
                            var thePast = new Date();
                            thePast.setDate(thePast.getDate() - 14);
                            if(date < thePast) {
                                //Create a list of external db members that need updating
                                expiredMembers.push(casedata.member);
                            }
                        }
                    }
                    insertCaseCard('caselist', 'caselist'+casedata.task_id, casedata);
                })
                
            }
        }).then(function() {
            console.log('Expired data:');
            console.log(expiredMembers);
            toggleCaseCards();
            toggleDatepickers();        
        }).fail(function() {
            //console.log('Nothing found');
            $('#todolist').html("<center><img src='images/logo.png' width='50px' /><br />No cases found</center>");
            pagerNumbers('todo', 0, 0, 0);        
        });    
        resolve();
    });
}

loadCaselist().then(() => {
    var currentIndex = 0;

    var intervalId = setInterval(function() {
        if(currentIndex < expiredMembers.length) {
            console.log('Updating '+expiredMembers[currentIndex]);
            updatePersonInfo(expiredMembers[currentIndex]);
            currentIndex++;
        } else {
            clearInterval(intervalId); // Stop the interval when all identifiers have been processed
        }
    }, 5000); // 5000 milliseconds = 5 seconds
});

function caselistend_pager() {
    var pagerSettings=pagerNumberSettings('caselist');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start+qty;
    if(start > parseInt($('#caselistcount').val())) {
        start=start-qty;
    }

    var end=start+qty-1;
    //console.log('CASELISTEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#caseliststart').attr("value",(start));
    $('#caselistend').attr("value", (end));
    
    
    savePagerSettings('caselist', start, end, qty);
    loadCaselist();
}

function caseliststart_pager() {
    var pagerSettings=pagerNumberSettings('caselist');
    //var start=parseInt($('#caseliststart').val()) || 0;
    var start=parseInt(pagerSettings.start);
    //var qty=parseInt($('#caselistqty').val()) || 10;
    var qty=parseInt(pagerSettings.qty);
    start=start-qty;
    /*if(start < 1) {
        start=1;
    }*/
    var end=start+qty-1;
    
    
    //console.log('CASELISTEND FUNCTION: Qty-'+qty+', Start-'+start+', End-'+end);
    $('#caseliststart').attr("value",(start));
    $('#caselistend').attr("value", (end));
    
    
    savePagerSettings('caselist', start, end, qty);
    loadCaselist();
    
}

function caselistfirst_pager() {
    var pagerSettings=pagerNumberSettings('caselist');
    var start=0;
    var qty=parseInt(pagerSettings.qty);
    var end=start+qty-1;
    $('#caseliststart').attr("value",(start));
    $('#caselistend').attr("value", (end));
    
    
    savePagerSettings('caselist', start, end, qty);
    loadCaselist();    
}

function caselistlast_pager() {
    var pagerSettings=pagerNumberSettings('caselist');
    var qty=parseInt(pagerSettings.qty);
    var pages=parseInt($('#caselistcount').val())/qty;
    pages=parseInt(pages);
    var start=pages*qty;
    var end=start+qty-1;
    $('#caseliststart').attr("value",(start));
    $('#caselistend').attr("value", (end));
    
    
    savePagerSettings('caselist', start, end, qty);
    loadCaselist();    
}

/**
 * This function is only used in conjunction with an external db
 * @param {int} identifier 
 */
function updatePersonInfo(identifier) {
    console.log(identifier);
    $.when(fetchPerson(identifier).done(function(output) {
        if(output.response) {
            console.log(output);
            //var identifier;
            var surname=output.response.membershipAssociationSummaries[0].person.lastName;
            var pref_name=output.response.membershipAssociationSummaries[0].person.firstNames;
            var started=date2timestamp(output.response.membershipAssociationSummaries[0].membershipAssociation.association.from.substr(0,10));
            var primary_key=output.response.membershipAssociationSummaries[0].person.id;
            var data=JSON.stringify(output.response.membershipAssociationSummaries[0]);
            console.log(started);
            $.when(clientCreate(identifier, surname, pref_name, started, primary_key, data)).done(function(result) {
                console.log(result);
                if(result.response.substr(0,5)=="Error") {
                    //this entry already exists... update instead
                    console.log(result.response);
                    $.when(clientUpdate(identifier, surname, pref_name, started, primary_key, data)).done(function(result2) {
                        console.log('Updated '+pref_name+' '+surname+' in database');
                    }) 
                } else {
                    console.log('Added '+pref_name+' '+surname+' to database');
                }

            });
        } else {
            console.log('No matching person');
        }
    }))

}


