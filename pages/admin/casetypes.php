<?php
$users=$oct->userList(array(), null, null, 0, 1000000000);

//Get a list of case types
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";
$casetypes=$oct->caseTypeList(array(), null, null, 0, 10000000);

  
?>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <img src="images/save.svg" class="floatright pointer img-fluid rounded ml-2" title="Save changes" id="saveCaseTypesBtn"/>
            <img src="images/undo.svg" class="floatright pointer img-fluid rounded hidden ml-2" width='24px' title="Undo changes" id="undoCaseTypesBtn"/>
            <h4 class="header">Case Types</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-4">
                            Name
                        </div>
                        <div class="col-sm-6">
                            Description
                        </div>
                        <div class="col-sm">
                            Position
                        </div>
                        <div class="col-sm">
                            Show
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2" style="height: 700px" >

<?php
foreach($casetypes['results'] as $casetype) {
    $id=$casetype['tasktype_id'];

?>                
                    <div class="row mb-1">
                        <input type="hidden" name="id[]" value="<?php echo $casetype['tasktype_id'] ?>" />
                        <div class="col-sm-4">
                            <input class="form-control smaller" placeholder="Case Type Name" id="categoryname<?php echo $id ?>" type="text" name="category_name[]" value="<?php echo $casetype['tasktype_name'] ?>" />
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control smaller" placeholder="Case Type Description" id="categorydescrip<?php $id ?>" type="text" name="category_descrip[]" value="<?php echo $casetype['tasktype_description'] ?>" />
                        </div>
                        <div class="col-sm">
                            <input class="form-control smaller" placeholder="Position in list" id="listposition<?php echo $id ?>" type="text" size="2" name="list_position[]" value="<?php echo $casetype['list_position'] ?>" />
                        </div>
                        <div class="col-sm">
                            <input class="form-control smaller" placeholder="Show in list?" id="showinlist<?php echo $id ?>" type="checkbox" name="show_in_list[]" <?php if ($casetype['show_in_list']==1) echo "checked" ?> />
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
    $oct->showArray($casetypes);
?>