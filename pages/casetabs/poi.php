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
?>
<script src="js/pages/casetabs/poi.js"></script>

<div class='justify-content-center'>
    <div class='float-right pale-green-link rounded mr-3 mb-1 p-1 small' id='newPoiBtn'>
        <img src='images/plus.svg' width='12px' /> Connect Person
    </div>
    <div style='clear: both'></div>
    <div class='form-group hidden border rounded p-2 m-2 mb-4' id='newPoiForm'>
        <h4 class="header">Connect a person</h4>
        <div class="pager rounded-bottom w-100">&nbsp;</div>
        <div class="row mb-4" id="newpoi_step1">
            <input type='hidden' id='newPoiPersonId' />
            <div class='col-sm-2'></div>
            <div class='col-sm-2'>
                Person:
            </div>
            <div class='col-sm-4'>
                <input type='text' class='form-control poidropdown' id='newPoiPerson' placeholder='Person to connect' />
                <ul class="poidropdown-menu w-100 smaller" style="position: absolute;" id="poi-dropdown-menu"></ul>
            </div>
            <div class='col-sm-2'><span class='form-control pale-green-link text-center' id='newpoi_step1button'>Create</span></div>
            <div class='col-sm-2'></div>
        </div>
        
        
        <div class="row mb-4" id="newpoi_step2">
            <div class="col">
                <div class="row mb-1">
                    <div class="col-sm-3">
                        <input action='firstname' placeholder='First Name' class='form-control p-1 smaller updatepoi' type='text' id='firstname' />
                    </div>
                    <div class="col-sm-4">
                        <input action='lastname' placeholder='Last Name' class='form-control p-1 smaller updatepoi' type='text' id='lastname'  />
                    </div>
                    <div class="col-sm-5">
                        <input action='email' placeholder='Email' class='form-control p-1 smaller updatepoi' type='text' id='email' />
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-sm-5">
                        <input action='position' placeholder='Position Name' class='form-control p-1 smaller updatepoi' type='text' id='position' />
                    </div>
                    <div class="col-sm-5">
                        <input action='organisation' placeholder='Organisation' class='form-control p-1 smaller updatepoi' type='text' id='organisation' />
                    </div>
                    <div class="col-sm-2">
                        <input action='phone' placeholder='Phone No' class='form-control p-1 smaller updatepoi' type='text' id='phone' />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8"></div> 
                    <div class="col-sm-4 text-right">
                        <span class='btn btn-sm btn-primary createpoiperson'>Create</span>
                        <span class='btn btn-sm btn-secondary cancelpoiperson smaller'>Cancel</span>
                    </div> 
                </div>
            </div> 
        </div>
        
        <div id="newpoi_step3">
            <div class="row mb-4">
                <div class='col-sm-1'></div>
                <div class='col-sm-9'>
                    Adding a connection to <span id='newpoiconnectionname'></span>
                </div>
                <div class='col-sm-2'></div>
            </div>
            <div class="row mb-1">
                <div class='col-sm-1'></div>
                <div class='col-sm-7'>
                    <input type='text' class="form-control" id='newPoiComment' name='newPoiComment' placeholder='Reason for connecting this person' />
                </div>
                <div class='col-sm-2'>
                    <button class="form-control pale-green-link btn-primary" id='submitPoiBtn'>Submit</button>
                </div>
                <div class='col-sm-2'><button class="form-control btn-secondary smaller w-100" id='cancelPoiConnectionBtn'>Cancel</button></div>      
            </div>
        </div>
        
    </div>
</div>
<div style='clear: both'></div>

<div id='poilist' class="justify-content-center">
</div>
