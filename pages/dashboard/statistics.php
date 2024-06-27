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
<div class="row overflow-hidden flex-grow h-100">
<div class="col-12">
    <script src="js/pages/dashboard/statistics.js"></script>
    <h4 class="header">Statistics</h4>
    <div class="pager rounded-bottom">&nbsp;
        <select id='statsChooser' name='statsChooser' class='form-control-xs float-right p-0 w-25x mr-1' style='font-size: 8pt !important; height: 20px'>
            <optgroup label='My stats'>
                <option value='CaseStats'>My Case Load</option>
                <option value='CaseDepartments'>My Case departments</option>
                <option value='CaseTypes'>My Case types</option>
                <option value='CaseDates'>My Case statuses</option>
            </optgroup>
            <optgroup label='Site stats'>
                <option value='AllCaseStats'>Case Load</option>
                <option value='AllCaseDepartments'>Case departments</option>
                <option value='AllCaseTypes'>Case types</option>
                <option value='AllCaseDates'>Case statuses</option>
            </optgroup>
        </select>
    </div>
    <div class="row overflow-hidden border rounded bg-light justify-content-sm-center m-0 w-100 h-fullleft">
        <div class='col-12 w-100 h-fullleftplus overflow-hidden m-0 p-0' id="statsParent">
            <div id='dashboardStatistics' class='overflow-hidden rounded text-center h-100'><img src='images/logo_flip.gif' /></div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        // Calculate the height of #statsParent
        var height = $('#statsParent').height(); // Get the height of #statsParent
        //console.log('Height is ' + height);

        // Set the height and min-height of #dashboardStatistics
        $('#dashboardStatistics').css({
            'height': height + 'px',
            'min-height': height + 'px'
        });
    });
    </script>
    <?php
    
    ?>
    </div>
</div>
