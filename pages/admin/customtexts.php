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
$customtexts=$oct->customTextList(array(), "1=1");
//$oct->showArray($customtexts, "Custom Texts"); die();

$customtexttypes=array(
    array(
        "value"=>"taskclosed", 
        "text"=>"When case is closed",
    ),
);

?>
<script src="js/pages/admin/customtexts.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Custom Pages</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-2">
                        <div class="col-sm-2">
                            Action
                        </div>
                        <div class="col-sm-9">
                            Text
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2" style="max-height: 600px" >

<?php
foreach($customtexts['results'] as $customtext) {
    $id=$customtext['custom_text_id'];
    $selectattributes=array(
        "name"=>"modify_action[]", 
        "id"=>"customtextid$id",
        "class"=>"form-control smaller w-100 updatecustomtext",
        "placeholder"=>"Action",
        "action"=>"modify_action",
        "typeid"=>$id,
    );
    $cttypeselect=$oct->buildSelectList($customtexttypes, $selectattributes, "value", "text", $customtext['modify_action']);    
?>                
                    <div class="row mb-2">
                        <input type="hidden" name="id[]" value="<?php echo $id ?>" />
                        <div class="col-sm-2">
                            <?php echo $cttypeselect ?>
                        </div>
                        <div class="col-sm-9">
                            <textarea action="custom_text" typeid="<?php echo $id ?>" class="form-control smaller updatecustomtext updatepreview" placeholder="Custom Text" id="customtext<?php echo $id ?>" name="custom_text[]" style="height: 150px" onMouseOver="$('#preview<?php echo $id ?>').show();" onMouseOut="$('#preview<?php echo $id?>').hide()"><?php echo $customtext['custom_text'] ?></textarea>
                            <div class='m-2 p-6 hidden' id='preview<?php echo $id ?>' style='box-shadow: 0px 0px 80px rgba(0,0, 0,0.75); z-index: 9999; position: fixed; margin-top: 10%; left: 50%; transform: translateX(-50%);'><?php echo $customtext['custom_text'] ?></div>                            
                        </div>
                        <div class="col-sm text-center smaller">
                            <span class='btn btn-warning btn-sm' title='This custom field can be deleted because there are no cases assigned against it' onClick='deleteCustomText("<?php echo $id; ?>")'>Del</span>
                        </div>
                    </div>    
<?php
}
?>                
                
                
                
                </div>
                </form>
            </div>
            
            <h4 class="header">Add Custom Page</h4>
            <div class="row border rounded centered">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-2">
                            Action
                        </div>
                        <div class="col-sm-9">
                            Text
                        </div>
                        <div class="col-sm text-center">
                            &nbsp;
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2 w-100" >
                       <div class="row mb-1">
                            <div class="col-sm-2">
                            <?php
                                $selectattributes=array(
                                    "name"=>"custom_field_type", 
                                    "id"=>"modifyaction",
                                    "class"=>"form-control smaller w-100",
                                    "placeholder"=>"Action",
                                    "action"=>"modify_action"
                                );
                                $cttypeselect=$oct->buildSelectList($customtexttypes, $selectattributes, "value", "text");
       
                                echo $cttypeselect;                   
                            ?>
                            </div>
                            <div class="col-sm-9">
                                <textarea action='custom_text' class="form-control smaller" placeholder="Custom text" id="customtext" name="custom_text" style="height: 200px" /></textarea>
                            </div>                            

                            <div class="col-sm text-center">
                                <span class='btn btn-sm btn-main createcustomtext'>Add</span>
                            </div>      
                       </div>          
                </div>            
        </div>
    </div>
</div>

<?php
    //$oct->showArray($casetypes);
?>