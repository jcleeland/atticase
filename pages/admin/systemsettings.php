<?php
  
?>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">System Settings</h4>
            <div class="row border rounded">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['base_url']['description'] ?>">
                            Base URL
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='base_url' size=30 value='<?php echo $prefs['base_url']['value']; ?>' title='<?php echo $prefs['base_url']['description'] ?>'/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['admin_email']['description'] ?>">
                            Reply email for notifications
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='admin_email' size=30 value='<?php echo $prefs['admin_email']['value']; ?>' />
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['version']['description'] ?>">
                            Database version
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='admin_email' value='<?php echo $prefs['version']['value']; ?>' />
                        </div>                        
                    </div>                     
                </div>
                
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['allow_billing']['description'] ?>">
                            Allow billing
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control' name='allow_billing'>
                                <option value='0'>No</option>
                                <option value='1' <?php if($prefs['allow_billing']['value']==1) echo " selected" ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['billing_rate']['description'] ?>">
                            Billing rate
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='admin_email' size=5 value='<?php echo $prefs['billing_rate']['value']; ?>' />
                        </div>                        
                    </div> 
                    <div class="row">                    
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['assigned_groups']['description'] ?>">
                            Assignable Groups
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="assign_admin" /> Admin
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['allow_restricted']['description'] ?>">
                            Allow task restriction
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="allow_restricted" checked="<?php if($pref['allow_restricted']['value']==1) echo "checked" ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['anon_open']['description'] ?>">
                            Allow anonymous to create
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="anon_open" checked="<?php if($pref['anon_open']['value']==1) echo "checked" ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['restrict_view']['description'] ?>">
                            Restrict views
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type="checkbox" class="form-checkbox" name="anon_open" checked="<?php if($pref['restrict_view']['value']==1) echo "checked" ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                        </div>
                    </div>
                </div>
                
                                
            </div>
        </div>
    </div>
</div>