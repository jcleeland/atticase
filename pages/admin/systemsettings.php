<?php
  
?>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">System Settings</h4>
            <div class="row border rounded">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Base URL
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='baseurl' value='' />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Reply email for notifications
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='replyemail' value='' />
                        </div>                        
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Theme / Style
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control' name='theme'>
                                <option value='default'>Default</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">                    
                        <div class="subSection-label col-xs-4 m-1">
                            Assignable Groups
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="assign_admin" /> Admin
                        </div>                        
                    </div>
                </div>
                
                                
            </div>
        </div>
    </div>
</div>