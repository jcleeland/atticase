<script src="js/pages/casetabs/poi.js"></script>

<div class='justify-content-center'>
    <div class='float-right pale-green-link rounded mr-3 mb-1 p-1 small' id='newPoiBtn'>
        <img src='images/plus.svg' width='12px' /> Connect Person
    </div>
    <div style='clear: both'></div>
    <div class='form-group hidden border rounded p-2 m-2 mb-4' id='newPoiForm'>
        <h4 class="header">New person of interest</h4>
        <div class="pager rounded-bottom w-100">&nbsp;</div>
        <div class="row mb-4" id="newpoi_step1">
            <input type='hidden' id='newPoiPersonId' />
            <div class='col-sm-2'></div>
            <div class='col-sm-2'>
                Find person to connect to:
            </div>
            <div class='col-sm-3'>
                <input type='text' class='form-control poidropdown' id='newPoiPerson' placeholder='Person to connect' />
                <ul class="poidropdown-menu w-100 smaller" style="position: absolute;" id="poi-dropdown-menu"></ul>
            </div>
            <div class='col-sm-3'>
                <span class='btn btn-primary' id='newpoi_step1button'>Create new</span>
            </div>
            <div class='col-sm-2'></div>

        </div>
        
        <div class="row mb-4" id="newpoi_step2">
            <div class="col-sm">
                <input action='firstname' placeholder='First Name' class='form-control p-1 smaller updatepoi' type='text' id='firstname' />
            </div>
            <div class="col-sm-2 pl-0">
                <input action='lastname' placeholder='Last Name' class='form-control p-1 smaller updatepoi' type='text' id='lastname'  />
            </div>
            <div class="col-sm-2 pl-0">
                <input action='position' placeholder='Position Name' class='form-control p-1 smaller updatepoi' type='text' id='position' />
            </div>
            <div class="col-sm-2 pl-0">
                <input action='organisation' placeholder='Organisation' class='form-control p-1 smaller updatepoi' type='text' id='organisation' />
            </div>
            <div class="col-sm pl-0">
                <input action='phone' placeholder='Phone No' class='form-control p-1 smaller updatepoi' type='text' id='phone' />
            </div>
            <div class="col-sm-2 pl-0">
                <input action='email' placeholder='Email' class='form-control p-1 smaller updatepoi' type='text' id='email' />
            </div> 
            <div class="col-sm-2 text-center pl-0">
                <span class='btn btn-sm btn-info cancelpoiperson'>Cancel</span>
                <span class='btn btn-sm btn-primary createpoiperson'>Create</span>
            </div>  
        </div>
        
        <div id="newpoi_step3">
            <div class="row mb-4">
                <div class='col-sm-2'></div>
                <div class='col-sm-9'>
                    Adding a connection to <span id='newpoiconnectionname'></span>
                </div>
                <div class='col-sm-1'></div>
            </div>
            <div class="row mb-4">
                <div class='col-sm-2'></div>
                <div class='col-sm-8'>
                    <input type='text' class="form-control" id='newPoiComment' name='newPoiComment' placeholder='Reason for connecting this person' />
                </div>
                <div class='col-sm-1'>
                    <button class="form-control pale-green-link btn-primary" id='submitPoiBtn'>Submit</button>
                </div>
                <div class='col-sm-1'></div>      
            </div>
        </div>
        
    </div>
</div>
<div style='clear: both'></div>

<div id='poilist' class="justify-content-center">
</div>
