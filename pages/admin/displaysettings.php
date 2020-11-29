<?php
  
?>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Display Settings</h4>
            <div class="row border rounded">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1" title="<?php echo $prefs['project_title']['description'] ?>">
                            Installation Name
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='base_url' size=30 value='<?php echo $prefs['project_title']['value']; ?>' title='<?php echo $prefs['base_url']['description'] ?>'/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Stub
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='stub' value='' />
                        </div>                        
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-6">
                    <div class="row">                    
                        <div class="subSection-label col-xs-4 m-1">
                            Theme / Style
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control' name='theme_style'>
                                <option value='<?php echo $prefs['theme_style']['value'] ?>' title='<?php echo $prefs['theme_style']['description'] ?>'><?php echo $prefs['theme_style']['value'] ?></option>
                                <option value='default'>Default</option>
                                
                            </select>
                        </div>                      
                    </div>
                    <div class="row">

                    </div>

                </div>
                
                                
            </div>
        </div>
    </div>
</div>