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
<script src="js/pages/casetabs/related.js"></script>

<div class='justify-content-center'>
    <div class='float-right pale-green-link rounded mr-3 mb-1 p-1 small' id='newRelatedBtn'>
        <img src='images/plus.svg' width='12px' /> Relate
    </div>
    <div style='clear: both'></div>
    <div class='form-group hidden border rounded p-2 m-2' id='newRelatedForm'>
        <h4 class="header">New related</h4>
        <div class="pager rounded-bottom w-100">&nbsp;</div> 
        <div class='form-group'>
            <label for="attachmentFileDesc">Related Case</label>
            <input type="text" class="form-control" name="relatedCaseId" id="relatedCaseId" placeholder="Case number of related case" aria-describedby="relatedCaseIdHelp" />
            <small id="relatedCaseIdHelp" class="form-text text-muted">Enter the related case's number here</small>
        </div>
        
        <button class="form-control pale-green-link" id='submitRelatedBtn'>Submit</button>
        
    </div>
</div>
<div style='clear: both'></div>

<div id='relatedlist' class="justify-content-center">
</div>
