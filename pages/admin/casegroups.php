<?php
//$users=$oct->userList(array(), null, null, 0, 1000000000);
//Case Groups are stored in the table named "list_version"
//Get a list of case groups
//echo "<pre class='overflow-auto' style='max-height: 270px'>"; print_r($userselect); echo "</pre>";
$casegroups=$oct->caseGroupList(array(), "1=1", null, 0, 10000000);

$results=$oct->fetchMany("SELECT product_version, count(*) as total FROM ".$oct->dbprefix."tasks GROUP BY product_version ORDER BY product_version ASC", null, 0, 10000);
$casegroupcounts=array();
foreach($results['output'] as $cgcount) {
    $casegroupcounts[$cgcount['product_version']]=$cgcount['total'];
}
?>
<script src="js/pages/admin/casegroups.js"></script>
<div class="col-sm-12 mb-1 ">
    <div class="row justify-content-sm-center">
        <div class="col-sm-12">
            <h4 class="header">Case Groups</h4>
            <div class="row border rounded centered">
                <form class="w-100">
                <div class="p-2 w-100">
                    <div class="row mb-1">
                        <div class="col-sm-8">
                            Name
                        </div>
                        <div class="col-sm">
                            Position
                        </div>
                        <div class="col-sm">
                            Show
                        </div>
                        <div class="col-sm">
                            Enquiry?
                        </div>
                        <div class="col-sm text-center">
                            Cases
                        </div>
                    </div>
                </div>
                <div class="form-group overflow-auto m-2 p-2" style="max-height: 600px">
                
<?php
foreach($casegroups['results'] as $casegroup) {
    $id=$casegroup['version_id'];

?>                
                    <div class="row mb-1">
                        <input type="hidden" name="id[]" value="<?php echo $casegroup['version_id'] ?>" />
                        <div class="col-sm-8">
                            <input action='version_name' groupid="<?php echo $id ?>" class="form-control smaller updategroupfield" placeholder="Case Group Name" id="versionname<?php echo $id ?>" type="text" name="version_name[]" value="<?php echo $casegroup['version_name'] ?>" />
                        </div>
                        <div class="col-sm">
                            <input action='list_position' groupid="<?php echo $id ?>" class="form-control smaller updategroupfield" placeholder="Position in list" id="listposition<?php echo $id ?>" type="text" size="2" name="list_position[]" value="<?php echo $casegroup['list_position'] ?>" />
                        </div>
                        <div class="col-sm">
                            <input action='show_in_list' groupid="<?php echo $id ?>" class="form-control smaller updategroupfield" placeholder="Show in list?" id="showinlist<?php echo $id ?>" type="checkbox" name="show_in_list[]" <?php if ($casegroup['show_in_list']==1) echo "checked" ?> />
                        </div>
                        <div class="col-sm">
                            <input action='is_enquiry' groupid="<?php echo $id ?>" class="form-control smaller updategroupfield" placeholder="Enquiry type?" id="isenquiry<?php echo $id ?>" type="checkbox" name="is_enquiry[]" <?php if ($casegroup['is_enquiry']==1) echo "checked" ?> />
                        </div>
                        <div class="col-sm text-center smaller">
                            <?php 
                            if(isset($casegroupcounts[$id])) {
                                echo $casegroupcounts[$id];
                            } else {
                                echo "<span class='btn btn-warning btn-sm' title='This case group can be deleted because there are no cases assigned against it' onClick='deleteCaseGroup(\"".$id."\")'>Del</span>";
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
            <div class="form-group overflow-auto p-2">
                <h4 class="header">Add Case Group</h4>
                <div class="row border rounded centered">
                    <div class="p-2 w-100">
                        <div class="row mb-1">
                            <div class="col-sm-8">
                                Name
                            </div>
                            <div class="col-sm">
                                Position
                            </div>
                            <div class="col-sm">
                                Show
                            </div>
                            <div class="col-sm">
                                Enquiry?
                            </div>
                            <div class="col-sm text-center">
                            </div> 
                        </div>
                       
                    </div>
                    <div class="form-group overflow-auto m-2 p-2 w-100">
                        <div class="row mb-1">
                            <div class="col-sm-8">
                                <input action='version_name' class="form-control smaller" placeholder="Case Group Name" id="versionname" type="text" name="new_category_name" />
                            </div>
                            <div class="col-sm">
                                <input action='list_position' class="form-control smaller" placeholder="Position in list" id="listposition" type="text" size="2" name="list_position" />
                            </div>
                            <div class="col-sm">
                                <input action='show_in_list' class="form-control smaller" placeholder="Show in list?" id="showinlist" type="checkbox" name="show_in_list" />
                            </div>
                            <div class="col-sm">
                                <input action='is_enquiry' class="form-control smaller" placeholder="Enquiry type?" id="isenquiry" type="checkbox" name="is_enquiry" />
                            </div>
                            <div class="col-sm text-center">
                                <span class='btn btn-sm btn-main creategroupfield'>Add</span>
                            </div>      
                        </div>          
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
<?php
    //$oct->showArray($casegroups, "Showing casegroups variable"); 

?>