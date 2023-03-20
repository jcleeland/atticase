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
<script src="js/pages/casetabs/linked.js"></script>
    <div class='float-right pale-green-link rounded mr-3 mb-1 p-1 small' id='newLinkBtn'>
        <img src='images/plus.svg' width='12px' /> Link
    </div>
    <div style='clear: both'></div>
    <div class='form-group hidden border rounded p-2 m-2' id='newLinkForm'>
        <h4 class="header">Link a case</h4>
        <div class="pager rounded-bottom w-100">&nbsp;</div> 
        <div class="row mb-2 mt-2">
            <div class='col-2'></div>
            <div class="col-4">
                <select id='linkType' class='w-100 form-control'>
                    <option value=''>Select link type...</option>
                    <option value='parent' title='If you make this case a parent of another case, every change you make to this case will be copied across to the other case(s)'>Make This Case A Master To Another Case</option>
                    <option value='child' title='If you make this case a dependent of another case, every change you make to its master will be copied to this case'>Make This Case A Dependent Of Another Case</option>
                    <option value='companion' title='If you make this case a companion to another case, those who view the companion case will see a short list of recent changes to this case'>Make This Case A Companion To Another Case</option>
                </select>
            </div>
            <div class="col-2">
                <input type='text' id='linkCase' class='form-control' placeholder="Case number of linked case" aria-describedby='linkedCaseIdHelp' />
                <small id="relatedCaseIdHelp" class="form-text text-muted smaller">Enter the linked case's number here</small>
            </div>
            <div class='col-2'><button class="form-control pale-green-link" id='submitLinkBtn'>Create</button></div> 
            <div class='col-2'></div>       
        </div>
        
        
    </div>
<div style='clear: both'></div>

<div id='linkedlist' class="justify-content-center">
</div>
