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
$pois=$oct->poiPeopleList(array(), "1=1");
//$oct->showArray($pois, "Custom Texts"); die();
$results=$oct->fetchMany("SELECT person_id, count(*) as total FROM ".$oct->dbprefix."people_of_interest GROUP BY person_id ORDER BY person_id ASC", null, 0, 10000);
$poicounts=array();
foreach($results['output'] as $pcount) {
    $poicounts[$pcount['person_id']]=$pcount['total'];
}


?>
<script src="js/pages/admin/poi.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">People of Interest</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-2">
                        <div class="col-sm">
                            Firstname
                        </div>
                        <div class="col-sm">
                            Lastname
                        </div>
                        <div class="col-sm-2">
                            Position
                        </div>
                        <div class="col-sm-3">
                            Organisation
                        </div>
                        <div class="col-sm">
                            Phone
                        </div>
                        <div class="col-sm-2">
                            Email
                        </div>
                        <div class="col-sm text-center">
                            Links
                        </div>
                        <div class="col-sm">
                            &nbsp;
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2" style="max-height: 600px" >

<?php
foreach($pois['results'] as $poi) {
    $id=$poi['id'];    
?>                
                    <div class="row mb-2 p-1">
                        <input type="hidden" name="id[]" value="<?php echo $id ?>" />
                        <div class="col-sm">
                            <input action='firstname' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatepoiperson' type='text' id='firstname<?php echo $id ?>' value='<?php echo $poi['firstname'] ?>' />
                        </div>
                        <div class="col-sm">
                            <input action='lastname' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatepoiperson' type='text' id='lastname<?php echo $id ?>' value='<?php echo $poi['lastname'] ?>' />
                        </div>
                        <div class="col-sm-2">
                            <input action='position' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatepoiperson' type='text' id='position<?php echo $id ?>' value='<?php echo $poi['position'] ?>' />
                        </div>
                        <div class="col-sm-3">
                            <input action='organisation' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatepoiperson' type='text' id='organisation<?php echo $id ?>' value='<?php echo $poi['organisation'] ?>' />
                        </div>
                        <div class="col-sm">
                            <input action='phone' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatepoiperson' type='text' id='phone<?php echo $id ?>' value='<?php echo $poi['phone'] ?>' />
                        </div>
                        <div class="col-sm-2">
                            <input action='email' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatepoiperson' type='text' id='email<?php echo $id ?>' value='<?php echo $poi['email'] ?>' />
                        </div>                        
                        <div class="col-sm text-center">
                        <?php
                        if(!isset($poicounts[$id])) {
                            echo "0";       
                        } else {
                            echo "<span id='connectioncount".$id."'>".$poicounts[$id]."</span>"; 
                        }    
                        ?>
                        </div>
                        <div class="col-sm text-center smaller">
                        <?php
                        if(isset($poicounts[$id])) {
                        ?>
                            <span typeid="<?php echo $id ?>" class='btn btn-info btn-sm viewconnections' title="View person's connections">View</span>                     
                        <?php } else { ?>
                            <span class='btn btn-warning btn-sm' title='This person can be deleted because there are no cases connected against them' onClick='deletePoiPerson("<?php echo $id; ?>")'>Del</span>                        
                        <?php } ?>
                        </div>
                    </div>
                    <div class="row m-2 hidden connectionlists smaller" id="connectionlist<?php echo $id ?>">
                        <div id="connections<?php echo $id ?>" class="border rounded">
                        
                        </div>    
                    </div>
<?php
}
?>                
                
                
                
                </div>
                </form>
            </div>
            
            <h4 class="header">Add New Person</h4>
            <div class="row border rounded centered">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm">
                            Firstname
                        </div>
                        <div class="col-sm">
                            Lastname
                        </div>
                        <div class="col-sm-2">
                            Position
                        </div>
                        <div class="col-sm-3">
                            Organisation
                        </div>
                        <div class="col-sm">
                            Phone
                        </div>
                        <div class="col-sm-2">
                            Email
                        </div>
                        <div class="col-sm">
                            
                        </div>
                        <div class="col-sm">
                            &nbsp;
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2 w-100" >
                       <div class="row mb-1">
                            <div class="col-sm">
                                <input action='firstname' class='form-control p-1 smaller updatepoi' type='text' id='firstname' />
                            </div>
                            <div class="col-sm">
                                <input action='lastname' class='form-control p-1 smaller updatepoi' type='text' id='lastname'  />
                            </div>
                            <div class="col-sm-2">
                                <input action='position' class='form-control p-1 smaller updatepoi' type='text' id='position' />
                            </div>
                            <div class="col-sm-3">
                                <input action='organisation' class='form-control p-1 smaller updatepoi' type='text' id='organisation' />
                            </div>
                            <div class="col-sm">
                                <input action='phone' class='form-control p-1 smaller updatepoi' type='text' id='phone' />
                            </div>
                            <div class="col-sm-2">
                                <input action='email' class='form-control p-1 smaller updatepoi' type='text' id='email' />
                            </div>                        
                            <div class="col-sm text-center">
                                
                            </div>
                            <div class="col-sm text-center">
                                <span class='btn btn-sm btn-main createpoiperson'>Add</span>
                            </div>      
                       </div>          
                </div>            
        </div>
    </div>
</div>

<?php
    //$oct->showArray($casetypes);
?>