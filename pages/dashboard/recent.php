<h4 class="header">Recent</h4>
<div class="w-100 overflow-auto" style="max-height: 270px">
<?php
for($x=1; $x<=7; $x++) {

    $parent="recent";
    /** FOR TESTING - REPLACE WITH DATA GATHER **/
    $case_details=array(
        "task_id"=>$x,
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
    );    
    
    
    include("pages/dashboard/listitem.php");
}  
?>
</div>
