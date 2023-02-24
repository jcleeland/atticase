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
$resolutions=$oct->resolutionList(array(), "1=1", "show_in_list DESC, list_position ASC");

$results=$oct->fetchMany("SELECT resolution_reason, count(*) as total FROM ".$oct->dbprefix."tasks GROUP BY resolution_reason ORDER BY resolution_reason ASC", null, 0, 10000);
$resolutioncounts=array();
foreach($results['output'] as $cgcount) {
    $resolutioncounts[$cgcount['resolution_reason']]=$cgcount['total'];
}
  
?>
<script src="js/pages/admin/resolutions.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Case Types</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-4">
                            Name
                        </div>
                        <div class="col-sm-5">
                            Description
                        </div>
                        <div class="col-sm">
                            Position
                        </div>
                        <div class="col-sm text-center">
                            Show
                        </div>
                        <div class="col-sm text-center">
                            Cases
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2" style="max-height: 600px" >

<?php
foreach($resolutions['results'] as $resolution) {
    $id=$resolution['resolution_id'];
?>                
                    <div class="row mb-1">
                        <input type="hidden" name="id[]" value="<?php echo $id ?>" />
                        <div class="col-sm-4">
                            <input action="resolution_name" typeid="<?php echo $id ?>" class="form-control smaller updateresolutionfield" placeholder="Resolution Name" id="resolutionname<?php echo $id ?>" type="text" name="resolution_name[]" value="<?php echo $resolution['resolution_name'] ?>" />
                        </div>
                        <div class="col-sm-5">
                            <input action="resolution_description" typeid="<?php echo $id ?>" class="form-control smaller updateresolutionfield" placeholder="Resolution Description" id="resolutiondescrip<?php $id ?>" type="text" name="resolution_descrip[]" value="<?php echo $resolution['resolution_description'] ?>" />
                        </div>
                        <div class="col-sm">
                            <input action="list_position" typeid="<?php echo $id ?>" class="form-control smaller updateresolutionfield" placeholder="Position in list" id="listposition<?php echo $id ?>" type="text" size="2" name="list_position[]" value="<?php echo $resolution['list_position'] ?>" />
                        </div>
                        <div class="col-sm text-center">
                            <input action="show_in_list" typeid="<?php echo $id ?>" class="form-control smaller updateresolutionfield" placeholder="Show in list?" id="showinlist<?php echo $id ?>" type="checkbox" name="show_in_list[]" <?php if ($resolution['show_in_list']==1) echo "checked" ?> />
                        </div>
                        <div class="col-sm text-center smaller">
                            <?php
                            if(isset($resolutioncounts[$resolution['resolution_id']])) {
                                echo $resolutioncounts[$resolution['resolution_id']];
                            } elseif ($resolution['show_in_list']==1) {
                                echo "0";
                            } else {
                                echo "<span class='btn btn-warning btn-sm' title='This resolution can be deleted because there are no cases assigned against it' onClick='deleteResolution(\"".$id."\")'>Del</span>";
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
            
            <h4 class="header">Add Resolution</h4>
            <div class="row border rounded centered">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-4">
                            Name
                        </div>
                        <div class="col-sm-5">
                            Description
                        </div>
                        <div class="col-sm">
                            Position
                        </div>
                        <div class="col-sm text-center">
                            Show
                        </div>
                        <div class="col-sm text-center">
                            &nbsp;
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2 w-100" >
                       <div class="row mb-1">
                            <div class="col-sm-4">
                                <input action='resolution_name' class="form-control smaller" placeholder="Resolution Name" id="resolutionname" type="text" name="category_name" />
                            </div>
                            <div class="col-sm-5">
                                <input action='resolution_descrip' class="form-control smaller" placeholder="Description of resolution" id="resolutiondescrip" type="text" name="category_descrip" />
                            </div>
                            <div class="col-sm">
                                <input action='list_position' class="form-control smaller" placeholder="Position in list" id="listposition" type="text" size="2" name="list_position" />
                            </div>
                            <div class="col-sm">
                                <input action='show_in_list' class="form-control smaller text-center" placeholder="Show in list?" id="showinlist" type="checkbox" name="show_in_list" />
                            </div>
                            <div class="col-sm text-center">
                                <span class='btn btn-sm btn-main createresolution'>Add</span>
                            </div>      
                       </div>          
                </div>            
        </div>
    </div>
</div>

<?php
    //$oct->showArray($casetypes);
?>