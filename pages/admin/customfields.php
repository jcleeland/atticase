<?php
$users=$oct->userList(array(), null, null, 0, 1000000000);

//Get a list of case types
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";
$customfields=$oct->customfieldList(array(), "1=1", "custom_field_visible DESC");
//$oct->showArray($customfields);

$customfieldtypes=array(
    array(
        "value"=>"c", 
        "text"=>"Checkbox",
    ),
    array(
        "value"=>"t",
        "text"=>"Text",
    ),
);

$results=$oct->fetchMany("SELECT custom_field_definition_id, count(*) as total FROM ".$oct->dbprefix."custom_fields GROUP BY custom_field_definition_id ORDER BY custom_field_definition_id ASC", null, 0, 10000);
$customfieldcounts=array();
foreach($results['output'] as $cfcount) {
    $customfieldcounts[$cfcount['custom_field_definition_id']]=$cfcount['total'];
}


?>
<script src="js/pages/admin/customfields.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Custom Fields</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-7">
                            Name
                        </div>
                        <div class="col-sm">
                            Type
                        </div>
                        <div class="col-sm text-center">
                            Visible
                        </div>
                        <div class="col-sm text-center">
                            Cases
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2" style="max-height: 600px" >

<?php
foreach($customfields['results'] as $customfield) {
    $id=$customfield['custom_field_definition_id'];
    $selectattributes=array(
        "name"=>"custom_field_type[]", 
        "id"=>"customfieldtype$id",
        "class"=>"form-control smaller w-100 updatecustomfield",
        "placeholder"=>"Custom Field Type",
        "action"=>"custom_field_type",
        "typeid"=>$id,
    );
    $cftypeselect=$oct->buildSelectList($customfieldtypes, $selectattributes, "value", "text", $customfield['custom_field_type']);    
?>                
                    <div class="row mb-1">
                        <input type="hidden" name="id[]" value="<?php echo $id ?>" />
                        <div class="col-sm-7">
                            <input action="custom_field_name" typeid="<?php echo $id ?>" class="form-control smaller updatecustomfield" placeholder="Field Name" id="customfieldname<?php echo $id ?>" type="text" name="custom_field_name[]" value="<?php echo $customfield['custom_field_name'] ?>" />
                        </div>
                        <div class="col-sm">
                            <?php echo $cftypeselect ?>
                        </div>
                        <div class="col-sm text-center">
                            <input action="custom_field_visible" typeid="<?php echo $id ?>" class="form-control smaller updatecustomfield" placeholder="Visible?" id="customfieldvisible<?php echo $id ?>" type="checkbox" name="show_in_list[]" <?php if ($customfield['custom_field_visible']==1) echo "checked" ?> />
                        </div>
                        <div class="col-sm text-center smaller">
                            <?php
                            if(isset($customfieldcounts[$customfield['custom_field_definition_id']])) {
                                echo $customfieldcounts[$customfield['custom_field_definition_id']];
                            } elseif ($customfield['custom_field_visible']==1) {
                                echo "0";
                            } else {
                                echo "<span class='btn btn-warning btn-sm' title='This custom field can be deleted because there are no cases assigned against it' onClick='deleteCustomField(\"".$id."\")'>Del</span>";
                            }
                            ?>
                        </div>
                    </div>    
<?php
}
?>                
                
                
                
                </div>
                </form>
            </div>
            
            <h4 class="header">Add Custom Field</h4>
            <div class="row border rounded centered">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-7">
                            Name
                        </div>
                        <div class="col-sm">
                            Type
                        </div>
                        <div class="col-sm text-center">
                            Visible
                        </div>
                        <div class="col-sm text-center">
                            &nbsp;
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2 w-100" >
                       <div class="row mb-1">
                            <div class="col-sm-7">
                                <input action='custom_field_name' class="form-control smaller" placeholder="Field Name" id="customfieldname" type="text" name="custom_field_name" />
                            </div>
                            <div class="col-sm">
                            <?php
                                $selectattributes=array(
                                    "name"=>"custom_field_type", 
                                    "id"=>"customfieldtype",
                                    "class"=>"form-control smaller w-100 updatecustomfield",
                                    "placeholder"=>"Custom Field Type",
                                    "action"=>"custom_field_type"
                                );
                                $cftypeselect=$oct->buildSelectList($customfieldtypes, $selectattributes, "value", "text");             echo $cftypeselect;                   
                            ?>
                            </div>
                            <div class="col-sm">
                                <input action='custom_field_visible' class="form-control smaller text-center" placeholder="Visible" id="customfieldvisible" type="checkbox" name="custom_field_Visible" />
                            </div>
                            <div class="col-sm text-center">
                                <span class='btn btn-sm btn-main createcustomfield'>Add</span>
                            </div>      
                       </div>          
                </div>            
        </div>
    </div>
</div>

<?php
    //$oct->showArray($casetypes);
?>