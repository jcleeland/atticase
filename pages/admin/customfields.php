<?php
/*
 * Copyright [2022] [Jason Alexander Cleeland, Melbourne Australia]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
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
    array(
        "value"=>"d",
        "text"=>"Date",
    ),
    array(
        "value"=>"l",
        "text"=>"List",
    )
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
                        <div class="col-sm-7 text-center">
                            <span class="admin-headers">Name</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Type</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Visible</span>
                        </div>
                        <div class="col-sm text-center">
                        <span class="admin-headers">Cases</span>
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
        "class"=>"form-control smaller w-100 updatecustomfield customfieldtype",
        "placeholder"=>"Custom Field Type",
        "action"=>"custom_field_type",
        "data-original"=>$customfield['custom_field_type'],
        "typeid"=>$id,
    );
    $cftypeselect=$oct->buildSelectList($customfieldtypes, $selectattributes, "value", "text", $customfield['custom_field_type']);   
    
    //If this is a list field, get the list items
    if($customfield['custom_field_type']=="l") {
        $extracss="";
        $listitems=$oct->fetchMany("SELECT * FROM ".$oct->dbprefix."custom_field_lists WHERE custom_field_definition_id=? ORDER BY custom_field_order", array($id), 0, 10000);
    } else {
        $extracss="hidden";
        $listitems=array("output"=>array());
    }
?>                
                    <div class="row mb-1">
                        <input type="hidden" name="id[]" value="<?php echo $id ?>" />
                        <div class="col-sm-7">
                            <input action="custom_field_name" typeid="<?php echo $id ?>" class="form-control smaller updatecustomfield" placeholder="Field Name" id="customfieldname<?php echo $id ?>" type="text" name="custom_field_name[]" value="<?php echo $customfield['custom_field_name'] ?>" />
                            <div id="customfieldname<?php echo $id ?>-list" class="smaller <?= $extracss ?>" style="min-height: 2.8rem">
                                <div class="float-left position-absolute col-sm-1 text-center p-0" style="z-index: 1">
                                    <span class="btn btn-light btn-sm mt-1" title="Add a new item to this list" onClick="addAnotherOption('customfieldname<?= $id ?>-list', '<?= $id ?>')"><img style="width: 0.5rem" src="images/plus.svg" /></span><br />
                                    <span class="btn btn-light btn-sm mt-1" title="Remove last item from this list" onClick="removeLastOption('customfieldname<?= $id ?>-list', '<?= $id ?>')"><img style="width: 0.5rem" src="images/minus.svg" /></span>
                                </div>
                                    
                                <?php foreach($listitems['output'] as $listitem) { ?>
                                
                                    <div class="row mb-1 mt-1 customfieldlistitem" data-fieldlistid="<?= $listitem['custom_field_list_id'] ?>">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-9">
                                            <input action="custom_field_value" typeid="<?= $listitem['custom_field_list_id'] ?>" class="form-control smaller updatecustomfieldlist" placeholder="List Item" id="customfieldvalue<?php echo $id ?>_<?= $listitem['custom_field_list_id'] ?>" type="text" name="custom_field_value[]" value="<?php echo $listitem['custom_field_value'] ?>" />
                                        </div>
                                        <div class="col-sm-2 text-center">
                                            <input action="custom_field_order" typeid="<?= $listitem['custom_field_list_id'] ?>" class="form-control smaller updatecustomfieldlist" placeholder="List Item Order" id="customfieldorder<?php echo $id ?>_<?= $listitem['custom_field_list_id'] ?>" type="text" name="custom_field_order[]" value="<?php echo $listitem['custom_field_order'] ?>" />
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
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
                        <div class="col-sm-7 text-center">
                            <span class="admin-headers">Name</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Type</span>
                        </div>
                        <div class="col-sm text-center">
                            <span class="admin-headers">Visible</span>
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
                                
                                <div class="smaller hidden text-info m-2 border rounded">
                                    
                                    Once you have added this new field with the "List" type you will be able to add new items to the list that displays with it.
                                
                                </div>
                            
                            </div>
                            <div class="col-sm">
                            <?php
                                $selectattributes=array(
                                    "name"=>"custom_field_type", 
                                    "id"=>"customfieldtype",
                                    "class"=>"form-control smaller w-100 customfieldtype",
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