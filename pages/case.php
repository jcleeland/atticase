<?php
$case_details=array(
    "task_id"=>332,
    "item_summary"=>"Feeling really let down by my employer and manager",
    "name"=>"Joe Bloggs",
    "date_due"=>"12/05/2020",
    "assigned_to"=>"Roger Officer",
    "case_type"=>"Misconduct",
    "line_manager"=>"Billy Thekid",
    "line_manager_ph"=>"03 9638 1822",
    "case_group"=>"Standard Case",
    "department"=>"Health & Human Services",
    "local_delegate"=>"Carl Marks",
    "local_delegate_ph"=>"(03) 8080 8080",
    "detailed_desc"=>"It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).",
    "resolution_sought"=>"It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).",
    "opened_by"=>"Johnny Admin",
    "date_opened"=>"01/12/2019"
)  
?>
<div class='col-sm-12 mb-1'>
    <div class="card">
        <div class="card-header card-heading border rounded">
            <div class="float-left card-heading-border border rounded pl-1 pr-1 mr-2 case-link">332</div>
            <a data-toggle='collapse' href='#case-card' aria-expanded='true' aria-controls='case-card' id='toggle-case-card' >
                <img id="case-card-toggle-image" src='images/caret-top.svg' class='img-thumbnail float-right green-link' width='30px' title="Hide case details" />
            </a>
            <div class="float-right mr-2 card-heading-border border rounded pl-1 pr-1 calendar-div pointer"><input type='text' id='date_due' class='datepicker' value='<?php echo $case_details['date_due'] ?>' /></div>
            <div class="float-right card-heading-border border rounded pl-1 pr-1 mr-2 pale-green-link">
                <?php echo $case_details['name'] ?><a class='fa-userlink' href=''></a>
            </div>
            <div class="float-left display-6">
                <?php echo $case_details['item_summary'] ?>
            </div>
            &nbsp;
        </div>
    </div>
    
    <div class="card mb-2 collapse show" id="case-card" aria-labelledby='toggle-case-card'>
        <div class="card-body p-0">
            <div class="card-header border rounded">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Assigned To
                            </div>
                            <div class="subSection-field cols-xs-8 assigned_to">
                                <?php echo $case_details['assigned_to'] ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Case Type
                            </div>
                            <div class="subSection-field col-xs-8 case_type">
                                <?php echo $case_details['case_type'] ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Line Manager
                            </div>
                            <div class="subSection-field col-xs-8 line_manager">
                                <?php echo $case_details['line_manager'] ?> <?php echo $case_details['line_manager_ph'] ?><a class="fa-phone" href="tel:<?php echo $case_details['line_manager_ph'] ?>"></a><a class="fa-chat" href="sms:<?php echo $case_details['line_manager_ph'] ?>"></a>
                            </div>
                        </div>
                        
                    </div>
                    
                    
                    
                    <div class="col-xs-12 col-sm-6">

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Case Group
                            </div>
                            <div class="subSection-field col-xs-8 case_group">
                                <?php echo $case_details['case_group'] ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Department
                            </div>
                            <div class="subSection-field col-xs-8 department">
                                <?php echo $case_details['department'] ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Local Delegate
                            </div>
                            <div class="subSection-field col-xs-8 local_delegate">
                                <?php echo $case_details['local_delegate'] ?> <?php echo $case_details['local_delegate_ph'] ?><a class="fa-phone" href="tel:<?php echo $case_details['local_delegate_ph'] ?>"></a><a class="fa-chat" href="sms:<?php echo $case_details['local_delegate_ph']; ?>"></a>
                            </div>
                        </div>
                                                
                        
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-1">
                    <div class="subSection-label col-xs-2">
                        Case Outline
                    </div>
                    <div class="subSection-field col-xs-10 detailed_desc">
                        <?php echo $case_details['detailed_desc'] ?>
                    </div>
                </div>    

                <div class="row">
                    <div class="subSection-label col-xs-2">
                        Resolution Sought
                    </div>
                    <div class="subSection-field col-xs-10 resolution_sought">
                        <?php echo $case_details['resolution_sought'] ?>
                    </div>
                </div>                  
            </div>
            <div class="card-footer text-footnote">
                <div class="float-right border rounded red-link p-1" style='margin-top: -8px'>
                    <img src="images/end.svg" width="25px" title="Close case" />
                </div>
                Case created on <?php echo $case_details['date_opened'] ?> by <?php echo $case_details['opened_by'] ?>
            </div>
        </div>
    </div>
</div>
<?php
    include("pages/casetabs.php");
?>