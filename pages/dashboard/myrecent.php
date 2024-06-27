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
<div class="row overflow-hidden flex-grow h-100 smaller">
    <div class="col-12 h-100">
        <script src="js/pages/dashboard/myrecent.js"></script>
        <div class="float-right mt-1 text-muted clearfix">
            <select class="form-control-sm form-transparent-sm text-muted" id="myrecentFocus">
                <option>Mine</option>
                <option>All</option>
            </select>
            <!--<input type=text class="form-control-sm form-transparent-sm text-muted" style='width: 80px;' id="filterRecent" title="Search displayed recent items" />-->
        </div>

        <h4 class="p-0 header" class="float-left">Recently changed</h4>
        <?php 
            $pagername="myrecent"; 
            include('pages/helpers/pager.php'); 
        ?>
        <div class="overflow-auto flex-grow h-fullleft pb-4" id="myrecentlist">
            <center><img src='images/logo_spin.gif' width='50px' /><br />Searching...</center>
        </div>
    </div>
</div>
