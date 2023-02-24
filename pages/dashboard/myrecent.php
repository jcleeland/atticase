<script src="js/pages/dashboard/myrecent.js"></script>
<div class="float-right mt-1 text-muted">
    <select class="form-control-sm form-transparent-sm text-muted" id="myrecentFocus">
        <option>Mine</option>
        <option>All</option>
    </select>
    <!--<input type=text class="form-control-sm form-transparent-sm text-muted" style='width: 80px;' id="filterRecent" title="Search displayed recent items" />-->
</div>

<h4 class="header" class="float-left">My Recent Changes</h4>


<?php 
    $pagername="myrecent"; 
    include('pages/helpers/pager.php'); 
?>
<div class="overflow-auto" style="max-height: 270px" id="myrecentlist">
    <center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>
<?php

?>
</div>
