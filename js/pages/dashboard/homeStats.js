$(function() {
    loadCaseTypes();
    $('#statsChooser').val("CaseTypes");

    $(window).smartresize(function(){
        var fn='load'+$('#statsChooser').val();
        
        var choice=window[fn];
        
        if(typeof choice==="function") choice.apply(null);
    });

    $('#statsChooser').change(function() {
        console.log(globals);
        console.log('STATS');
    
        console.log($(this).val());
        var fn='load'+$(this).val();
        
        var choice=window[fn];
        
        if(typeof choice==="function") choice.apply(null);
        
    })
})

var colors=[];
colors=['#1F4F5B', '#2F7789', '#3E9EB6', '#67B7CB'];

function loadCaseDates() {
    $('#dashboardStatistics').html('<img src="images/logo_flip.gif" />');
    
    var parameters={};
    parameters[':assigned_to']=globals.user_id;
    var conditions='t.assigned_to = :assigned_to AND is_closed != 1';

    var now = new Date; // now
    now.setHours(0);   // set hours to 0
    now.setMinutes(0); // set minutes to 0
    now.setSeconds(0); // set seconds to 0

    var startTimeStamp = Math.floor(now / 1000);
    var endTimeStamp=startTimeStamp+86399;
    
    parameters[':startTimeStamp1']=startTimeStamp;
    parameters[':startTimeStamp2']=startTimeStamp;
    parameters[':startTimeStamp3']=startTimeStamp;
    parameters[':startTimeStamp4']=startTimeStamp;
    parameters[':endTimeStamp1']=endTimeStamp;
    parameters[':endTimeStamp2']=endTimeStamp;
    
    var select='CASE WHEN t.date_due < :startTimeStamp1 THEN "overdue" WHEN t.date_due >= :startTimeStamp2 AND t.date_due <= :endTimeStamp1 THEN "current" ELSE "future" END as status, count(*) as qty';
    
    var order = 'GROUP BY CASE WHEN t.date_due < :startTimeStamp3 THEN "overdue" WHEN t.date_due >= :startTimeStamp4 AND t.date_due <= :endTimeStamp2 THEN "current" ELSE "future" END';
    
    var first=0;
    var last=1;
    
    var graph=[];
    graph[0]=['Time status', 'Cases'];
    
    
    $.when(statsCases(parameters, conditions, order, first, last, select)).done(function(stats) {
        //console.log(graph);
        $.each(stats.results, function(i, result) {
            graph.push([result.status, parseInt(result.qty)]);
        })
        google.charts.setOnLoadCallback(function () {
            console.log(graph);
            googlePieChart('dashboardStatistics', 'My Case Status Overview', graph);
                            
        });
    })    
    
}

function loadCaseDepartments() {
    $('#dashboardStatistics').html('<img src="images/logo_flip.gif" />');
    
    var parameters={};
    parameters[':assigned_to']=globals.user_id;
    var conditions='t.assigned_to = :assigned_to AND is_closed != 1';
    
    var select='lc.category_name, count(*) as qty';
    
    var order = 'group by lc.category_name'; //put in a group by calc to show the case types.
    var first=0;
    var last=1;
    
    var results={};
    results['Case Types']={};
    var graph=[];
    graph[0]=['Type', 'Cases', {role: 'style'}];

    colorCount=0;

    
    $.when(statsCases(parameters, conditions, order, first, last, select)).done(function(stats) {
        //console.log(graph);
        $.each(stats.results, function(i, result) {
            graph.push([result.category_name, parseInt(result.qty), colors[colorCount]]);
            colorCount++;
            if(colorCount > 3) colorCount=0;    
        })
        google.charts.setOnLoadCallback(function () {
            console.log(graph);
            googleMultiBarChart('dashboardStatistics', 'My Case Departments', graph);
                            
        });
    })
}

function loadCaseTypes() {
    $('#dashboardStatistics').html('<img src="images/logo_flip.gif" />');
    
    var parameters={};
    parameters[':assigned_to']=globals.user_id;
    var conditions='t.assigned_to = :assigned_to AND is_closed != 1';
    
    var select='lt.tasktype_name, count(*) as qty';
    
    var order = 'group by lt.tasktype_name'; //put in a group by calc to show the case types.
    var first=0;
    var last=1;
    
    var results={};
    results['Case Types']={};
    var graph=[];
    graph[0]=['Type', 'Cases', {role: 'style'}];

    colorCount=0;

    
    $.when(statsCases(parameters, conditions, order, first, last, select)).done(function(stats) {
        //console.log(graph);
        $.each(stats.results, function(i, result) {
            graph.push([result.tasktype_name, parseInt(result.qty), colors[colorCount]]);
            colorCount++;
            if(colorCount > 3) colorCount=0;    
        })
        google.charts.setOnLoadCallback(function () {
            //console.log(graph);
            googleMultiBarChart('dashboardStatistics', 'My Case Types', graph);
                            
        });
    })
}

function loadCaseStats() {
    $('#dashboardStatistics').html('<img src="images/logo_flip.gif" />');
    //Currently Open Cases for this User
    var parameters={};
    parameters[':assigned_to']=globals.user_id;
    var conditions='t.assigned_to = :assigned_to AND is_closed != 1';
    var order=null;
    var first=0;
    var last=1;
    
    /* $.when(statsCases(parameters, conditions, order, first, last)).done(function(cases) {
        //console.log(cases);
    }) */
    
    
    //Generate 3 months of open case info by fortnight
    var now = new Date; // now
        now.setHours(0);   // set hours to 0
        now.setMinutes(0); // set minutes to 0
        now.setSeconds(0); // set seconds to 0

    var startTimeStamp = Math.floor(now / 1000)
    //console.log('Starttime: '+timestamp2date(startTimeStamp, "yy/mm/dd g:i a"));
    
    var endTimeStamp=startTimeStamp+86399;
    var fortnight=86400*14;
    var earlydate=startTimeStamp-(fortnight*6);
    //check for daylight savings issues
    var ds1check=new Date(earlydate*1000);
    if(ds1check.getHours()==23) {
        earlydate+=3600;
    }
    if(ds1check.getHours()==1) {
        earlydate-=3600;
    }
    //console.log('Earlydate: ['+earlydate+'] '+timestamp2date(earlydate, "yy/mm/dd g:i a"));
    //console.log(startTimeStamp);
    //conditions='t.assigned_to = :assigned_to AND date_opened <= :start_time AND (date_closed = "" OR date_closed IS NULL OR date_closed > :end_time)';
    
    var results={};
    results['Open Cases']={};
    results['New Cases']={};
    results['Closed Cases']={};
    graph={};
    graph[0]=['Date', 'Open Cases', 'Closed Cases', 'New Cases'];
    var resultstotal=0;
    var iterationcount=0;
    var resultscount=0;
    $.each(results, function(i,b) {
        resultstotal++;
    })
    
    
    
    $.each(results, function(item, contents) {
        

        if(item=='Open Cases') {
            conditions='t.assigned_to = :assigned_to AND date_opened <= :start_time AND (date_closed = "" OR date_closed IS NULL OR date_closed > :end_time)';
        }
        if(item=='New Cases') {
            conditions='t.assigned_to = :assigned_to AND date_opened >= :start_time AND date_opened <=:end_time';
        }
        if(item=='Closed Cases') {
            conditions='t.assigned_to = :assigned_to AND date_closed >= :start_time AND date_closed <= :end_time';
        }

        var iterations=parseInt(((startTimeStamp-earlydate)/(86400*7))+2);
        
        for(t=earlydate; t<=startTimeStamp; t+=(86400*7)) {
            //check for daylight savings issues
            var ds2check=new Date(t*1000);
            if(ds2check.getHours()==23) {
                t+=3600;
            }
            if(ds2check.getHours()==1) {
                t-=3600;
            }
            (function(tindex) {
                parameters[':start_time']=tindex;
                parameters[':end_time']=(tindex+(86400*14)-1);
                //console.log(parameters);
                $.when(statsCases(parameters, conditions, order, first, last, null)).done(function(stats) {
                    var stat=stats.results[0]['total'];
                    var n=new Date(tindex*1000);
                    var label=n.getFullYear()+'/'+('0'+n.getMonth()).slice(-2)+'/'+('0'+n.getDate()).slice(-2); 
                    var label=timestamp2date(tindex, "yy-mm-dd");
                    results[item][label]=[];
                    results[item][label]=parseInt(stats.results[0]['total']);
                    if(typeof graph[tindex]=='undefined') {
                        graph[tindex]=[];
                        graph[tindex].push(timestamp2date(tindex, "dd MM"));
                    }
                    graph[tindex].push(parseInt(stats.results[0]['total']));
                    //console.log(graph);
                    iterationcount++;
                    //console.log('Iterations: '+iterationcount+' / '+iterations);
                    //console.log(parseInt(stats.results[0]['total']));
                }).then(function() {
                        if(iterationcount==iterations) {
                            //We've finished this subset
                            resultscount++;
                            iterationcount=0;
                            //console.log('Results Count: '+resultscount);
                            if(resultscount==resultstotal) {
                                    var grapharray=[];
                                    $.each(graph, function(a,b) {
                                        //console.log(b);
                                        grapharray.push(b);
                                    })
                                    //var grapharray=Object.entries(graph);
                                    google.charts.setOnLoadCallback(function () {
                                        //drawLineChart(z, y, 'dashboardStatistics', 'Date', 'Cases', null, null, 'none');
                                        googleMultiLineChart('dashboardStatistics', 'My Cases', grapharray);
                                                        
                                    });
                                //console.log(results);
                                //console.log(graph);                    
                            }
                            
                        }                      
                })
            })(t);


           
        }        
        
        
    })
    
    
    
    
    
}