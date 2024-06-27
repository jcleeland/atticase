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
$accountdata=$oct->getUserAccount($user_id);
$accountdata=$accountdata['results'][0];

$usergroups=$oct->userGroupList(array(), null, null, 0, 1000000000);
$usergroups=$usergroups['results'];
$issuetypes=$oct->getIssueTypes();

$dir_path='pages/dashboard';
$files=scandir($dir_path);
$dashboarditems=array();
foreach($files as $file) {
    if ($file != '.' && $file != '..' && !is_dir($dir_path . '/' . $file)) {
        $filename_without_ext = pathinfo($file, PATHINFO_FILENAME);
        $nicename = ucfirst(strtolower($filename_without_ext)); 
        $coded=substr($nicename, 0, 6);
        //Decrypt with xor       
        $dashboarditems[$coded]=$nicename;
    }
}
$selecteddashboarditems=explode(",", $accountdata['dateformat_extended']);

//echo "<pre>"; print_r($usergroups); echo "</pre>";
//echo "<pre>"; print_r($accountdata); echo "</pre>";
?>
<script src="js/pages/account.js"></script>
<input type='hidden' name='userid' id='userid' value='<?php echo $_SESSION['user_id'] ?>' />
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-8">
            <h4 class="header">Account Details</h4>
            <div class="row border rounded">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Display Name
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control updateaccount' name='username' id='real_name' value='<?php echo $accountdata['real_name'] ?>' />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Email
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control updateaccount' name='email' id='email_address' value='<?php echo $accountdata['email_address'] ?>' />
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Phone
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control updateaccount' name='phone' id='jabber_id' value='<?php echo $accountdata['jabber_id'] ?>' />
                        </div>
                    </div>
                  

                </div>
                <div class="col-xs-12 col-sm-6">    
                    <div class="row">                    
                        <div class="subSection-label col-xs-4 m-1">
                            Username
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='text' class='form-control' name='username' value='<?php echo $accountdata['user_name'] ?>' disabled title='Cannot be changed by user' />
                        </div>
                    </div>
                    <div class="row">
                    <?php
                        $groupselect=$oct->buildSelectList($usergroups, array("name"=>"group_in", "id"=>"group_in", "class"=>"form-control", "disabled"=>"disabled", "title"=>"Cannot be changed by user"), "group_id", "group_name", $user_id, false, null);

                    ?>
                        <div class="subSection-label col-xs-4 m-1">
                            Group
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <?php echo $groupselect ?>
                        </div>
                    </div>                      
                    <div class="form-row">
                        <!--<div class="col-3 m-1">
                            <input type='checkbox' class='form-check-input checkbox' name='admin' id='admin'>
                            <label class='form-check-label' for='admin'>Admin</label>
                        </div>-->
                        <div class="col-4 m-1">
                            <input type='checkbox' class='form-check-input checkbox' name='account_enabled' id='account_enabled'<?php
                                if($accountdata['account_enabled']==1) echo " checked";
                            ?>  disabled title='Cannot be changed by user'>
                            <label class='form-check-label' for='admin'>Account enabled</label>                            
                        </div>
                        <div class="col-4 m-1">
                            <input type='checkbox' class='form-check-input checkbox' name='strategy_enabled' id='strategy_enabled'<?php
                                if($accountdata['strategy_enabled']==1) echo " checked";
                            ?>  disabled title='Cannot be changed by user'>
                            <label class='form-check-label' for='admin'>Can add strategy</label>
                        </div>

                    </div>                                      
                </div>
            </div>
            



            <div class="row border rounded mt-2">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Default case view
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control updateaccount' name='defaultView' id='default_task_view'>
                                <option value=''>All open Cases</option>
                                <option value='assigned'>Only my open cases</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Summary emails
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control updateaccount' name='notifyRate' id='notify_rate'>
                                <option value='N' <?php if($accountdata['notify_rate'] == "N") echo "selected" ?>>Never</option>
                                <option value='D' <?php if($accountdata['notify_rate'] == "D") echo "selected" ?>>Daily</option>
                                <option value='W' <?php if($accountdata['notify_rate'] == "W") echo "selected" ?>>Weekly</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Notify own actions
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <select class='form-control updateaccount' name='self_notify' id='self_notify'>
                                <option value='0' <?php if($accountdata['self_notify'] == 0) echo "selected" ?>>No</option>
                                <option value='1' <?php if($accountdata['self_notify'] == 1) echo "selected" ?>>Yes</option>
                            </select>
                        </div>
                    </div>                    
                </div>
            
                <?php
                    $issuetypeselect=$oct->buildSelectList($issuetypes['results'], array("name"=>"default_version", "id"=>"default_version", "class"=>"form-control"), "version_id", "version_name", $accountdata['default_version'], false, null);
                ?>
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Default case type
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <?php echo $issuetypeselect ?>
                        </div>                    
                    </div>
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Last summary email sent:
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <div class='float-left border pl-2 pr-2'>
                                <?php echo date("l, d M Y", $accountdata['last_notice']) ?>
                            </div>
                        </div>                    
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class='subSection-field w-100 m-1'>
                            <button class='btn btn-primary float-right m-2' id='save_changes'>Save Changes</button>
                        </div>
                    </div>            
                </div>
            </div>

            <br />
            <h4 class="header">Dashboard</h4>
            <div class="row border rounded mt-2">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div  class="subSection-label col-4 col-xs-4 m-1">
                            Page layout
                        </div>
                        <div class="subSection-field col-7 cols-xs-7 m-1">
                            <select class='form-control updateaccount w-100' name='dateformat' id='dateformat'>
                                <option value=''>System default</option>
                                <option value='1c2i' <?php if($accountdata['dateformat'] == '1c2i') echo "selected" ?>>1 Column, 2 Items</option>
                                <option value='1c4i' <?php if($accountdata['dateformat'] == '1c4i') echo "selected" ?>>1 Column, 4 Items</option>
                                <option value='2c3i' <?php if($accountdata['dateformat'] == '2c3i') echo "selected" ?>>2 Columns, 3 Items (top row double width)</option>
                                <option value='2c3ib' <?php if($accountdata['dateformat'] == '2c3ib') echo "selected" ?>>2 Columns, 3 Items (bottom row double width)</option>
                                <option value='2c1f2h' <?php if($accountdata['dateformat'] == '2c1f2h') echo "selected" ?>>2 Columns, 3 Items (1st Full height, 2nd 2 at half height)</option>
                                <option value='2c4i' <?php if($accountdata['dateformat'] == '2c4i') echo "selected" ?>>2 Columns, 4 Items</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Container items
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            Item 1 <select id='dateformatItem1'><?php echo dashboardSelect($dashboarditems, $selecteddashboarditems[0]) ?></select><br />
                            Item 2 <select id='dateformatItem2'><?php echo dashboardSelect($dashboarditems, $selecteddashboarditems[1]) ?>></select><br />
                            Item 3 <select id='dateformatItem3'><?php echo dashboardSelect($dashboarditems, $selecteddashboarditems[2]) ?>></select><br />
                            Item 4 <select id='dateformatItem4'><?php echo dashboardSelect($dashboarditems, $selecteddashboarditems[3]) ?>></select><br />
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class='subSection-field w-100 m-1'>
                            <button class='btn btn-primary float-right m-2' id='save_dashboard'>Save Dashboard</button>
                        </div>
                    </div>            
                </div>                
            </div>
            

            <br />
            <h4 class="header">Change Password</h4>
            <div class="row border rounded mt-2">
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Password
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='password' class='form-control' name='password' value='' />
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="row">
                        <div class="subSection-label col-xs-4 m-1">
                            Confirm Password
                        </div>
                        <div class="subSection-field cols-xs-7 m-1">
                            <input type='password' class='form-control' name='confirmpassword' value='' />
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class='subSection-field w-100 m-1'>
                            <button class='btn btn-primary float-right m-2'>Change Password</button>
                        </div>
                    </div>            
                </div>                
            </div>
        </div>

</div>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-8">
            <br />
            <h4 class="header">Session</h4>
            <div class="row border rounded">
                <div class="col-xs-12 col-sm-3">
                    <div class="row">
                        <div class="subSection-label col-xs-12 m-1">
                            Case Viewing History
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-9">
                    <div class="row">
                        <div class="subSection-field cols-xs-12 m-1">
                            <div id="case_viewing_history" class='small'>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class='subSection-field w-100 m-1'>
                            <button class='btn btn-secondary float-right m-2' id='clearsessionlogout'>Clear session data and logout</button>
                            <button class='btn btn-secondary float-right m-2' id="clear_case_history">Clear Case History</button>
                            <button class='btn btn-secondary float-right m-2' id='logout' onClick='window.location.href="index.php?logout=true"'>Logout</button>
                            <button class='btn btn-secondary float-right m-2' id='logoutcookies'>Clear cookies and logout</button>                            
                        </div>
                    </div>            
                </div>                
            </div>
        </div>
    </div>
</div>
<?php
    function dashboardSelect($dashboarditems, $selecteditem) {
        $output="<option value=''>System default</option>\n";
        foreach($dashboarditems as $key=>$val) {
            $output.="<option value='$key'";
            if($key==$selecteditem) $output.= " selected=selected";
            $output.=">$val</option>\n";
        }
        return $output;
    }
?>
