<?php
$users=$oct->userList(array(), null, null, 0, 1000000000);

//Get a list of case types
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";
$casegroups=$oct->caseGroupList(array(), null, null, 0, 10000000);

?>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <img src="images/save.svg" class="floatright pointer img-fluid rounded ml-2" title="Save changes" id="saveCaseTypesBtn"/>
            <img src="images/undo.svg" class="floatright pointer img-fluid rounded hidden ml-2" width='24px' title="Undo changes" id="undoCaseTypesBtn"/>
            <h4 class="header">Case Groups</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-9">
                            Name
                        </div>
                        <div class="col-sm">
                            Position
                        </div>
                        <div class="col-sm">
                            Show
                        </div>
                        <div class="col-sm">
                            Enquiry?
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2" style="height: 700px" >
                
<?php
foreach($casegroups['results'] as $casegroup) {
    $id=$casegroup['version_id'];

?>                
                    <div class="row mb-1">
                        <input type="hidden" name="id[]" value="<?php echo $casegroup['version_id'] ?>" />
                        <div class="col-sm-9">
                            <input class="form-control smaller" placeholder="Case Type Name" id="categoryname<?php echo $id ?>" type="text" name="category_name[]" value="<?php echo $casegroup['version_name'] ?>" />
                        </div>
                        <div class="col-sm">
                            <input class="form-control smaller" placeholder="Position in list" id="listposition<?php echo $id ?>" type="text" size="2" name="list_position[]" value="<?php echo $casegroup['list_position'] ?>" />
                        </div>
                        <div class="col-sm">
                            <input class="form-control smaller" placeholder="Show in list?" id="showinlist<?php echo $id ?>" type="checkbox" name="show_in_list[]" <?php if ($casegroup['show_in_list']==1) echo "checked" ?> />
                        </div>
                        <div class="col-sm">
                            <input class="form-control smaller" placeholder="Enquiry type?" id="isenquiry<?php echo $id ?>" type="checkbox" name="is_enquiry[]" <?php if ($casegroup['is_enquiry']==1) echo "checked" ?> />
                        </div>
                        
                    </div>    
<?php
}
?>                
                
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $oct->showArray($casegroups); 

?>