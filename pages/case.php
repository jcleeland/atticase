
<script src="js/pages/case.js"></script>
<input type='hidden' name='caseid' id='caseid' value='<?php echo $_GET['case'] ?>' />
<div class='col-sm-12 mb-1'>
    <div class="card">
        <div class="card-header card-heading border rounded">
            <div class="float-left card-heading-border border rounded pl-1 pr-1 mr-2 case-link" id='caseid_header'></div>
            <a data-toggle='collapse' href='#case-card' aria-expanded='true' aria-controls='case-card' id='toggle-case-card' >
                <img id="case-card-toggle-image" src='images/caret-top.svg' class='img-thumbnail float-right' style='background-color: #6ab446' width='30px' title="Hide case details" />
            </a>
            <div class="float-right mr-2 card-heading-border border rounded pl-1 pr-1 calendar-div pointer" id='date_due_parent'><input type='text' id='date_due' class='datepicker' value='' /></div>
            <div class="float-right card-heading-border border rounded pl-1 pr-1 mr-2 pale-green-link" id="clientname">
                <a class='fa-userlink' href=''></a>
            </div>
            <div class="float-left display-6" id='itemsummary'>
                Loading...
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
                            <div class="subSection-field cols-xs-8 assigned_to" id='assignedto_cover'>
                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Case Type
                            </div>
                            <div class="subSection-field col-xs-8 case_type" id="casetype_cover">
                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Line Manager
                            </div>
                            <div class="subSection-field col-xs-8 line_manager" id="linemanager_cover">
                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Local Delegate
                            </div>
                            <div class="subSection-field col-xs-8 local_delegate" id="delegate_cover">
                                
                            </div>
                        </div>

                        
                    </div>
                    
                    
                    
                    <div class="col-xs-12 col-sm-6">

                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Case Group
                            </div>
                            <div class="subSection-field col-xs-8 case_group" id="casegroup_cover">
                                
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Department
                            </div>
                            <div class="subSection-field col-xs-8 department" id="department_cover">
                                
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="subSection-label col-xs-4">
                                Unit
                            </div>
                            <div class="subSection-field col-xs-8 unit" id="unit_cover">
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="card-body row">
                <div class="col-lg">
                    <div class="subSection-label col-xs-2">
                        Case Outline
                    </div>
                    <div class="subSection-field col-xs-10 detailed_desc mb-1 pl-2 overflow-auto small" id="detaileddesc_cover" style="min-height: 100px; max-height: 200px">
                        
                    </div>
                </div>    

                <div class="col-lg">
                    <div class="subSection-label col-xs-2">
                        Resolution Sought
                    </div>
                    <div class="subSection-field col-xs-10 resolution_sought mb-1 pl-2 overflow-auto small" id="resolution_cover" style="min-height: 100px; max-height: 200px">
                        
                    </div>
                </div>                  
            </div>
            <div class="card-footer text-footnote">
                <div class="float-right border rounded red-link p-1" style='margin-top: -8px'>
                    <img src="images/end.svg" width="25px" title="Close case" />
                </div>
                Case created on <span id="dateopened_cover"></span> by <span id="openedby_cover"></span>
            </div>
        </div>
    </div>
</div>
<?php
    include("pages/casetabs.php");
?>