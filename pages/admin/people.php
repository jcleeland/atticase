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
$members=$oct->memberList(array(), "1=1", "surname, pref_name", 0, 200);

?>
<script src="js/pages/admin/poi.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Clients</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-2">
                        <div class="col-sm">
                            Identifier
                        </div>
                        <div class="col-sm">
                            Surname
                        </div>
                        <div class="col-sm-2">
                            Preferred Name
                        </div>
                        <div class="col-sm-3">
                            Finance Date
                        </div>
                        <div class="col-sm">
                            Joined
                        </div>
                        <div class="col-sm">
                            Action
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto p-2" style="max-height: 600px" >

<?php
foreach($members['results'] as $poi) {
    //print_r($poi); die();
    $id=$poi['member'];    
?>                
                    <div class="row mb-2 p-1">
                        <input type="hidden" name="id[]" value="<?php echo $id ?>" />
                        <div class="col-sm">
                            <input action='member' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatemember' type='text' id='member<?php echo $id ?>' value='<?php echo $poi['member'] ?>' />
                        </div>
                        <div class="col-sm">
                            <input action='lastname' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatemember' type='text' id='surname<?php echo $id ?>' value='<?php echo $poi['surname'] ?>' />
                        </div>
                        <div class="col-sm-2">
                            <input action='position' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatemember' type='text' id='pref_name<?php echo $id ?>' value='<?php echo $poi['pref_name'] ?>' />
                        </div>
                        <div class="col-sm-3">
                            <input action='organisation' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatemember' type='text' id='subs_paid_to<?php echo $id ?>' value='<?php echo $poi['subs_paid_to'] ?>a' />
                        </div>
                        <div class="col-sm">
                            <input action='phone' typeid='<?php echo $id ?>' class='form-control p-1 smaller updatemember' type='text' id='joined<?php echo $id ?>' value='<?php echo $poi['joined'] ?>' />
                        </div>
                        <div class="col-sm">
                            <span class="btn btn-sm btn-main">Del</span>
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