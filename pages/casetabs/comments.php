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
<script src="js/pages/casetabs/comments.js"></script>

<div class='justify-content-center'>
    <div class='float-right pale-green-link rounded mr-3 mb-1 p-1 small' id='newCommentBtn'>
        <img src='images/plus.svg' width='12px' /> Note
    </div>
    <div class='float-right m-2'>
        <input type='text' class='roundedcorners ml-4 form-control-sm form-transparent-sm text-muted' id='comments-inpage_filter' title='Search currently showing results...' />
    </div>
    <div style='clear: both'></div>
    <div class='form-group hidden border rounded p-2 m-2' id='newCommentForm'>
        <h4 class="header">New note</h4>
        <div class="pager rounded-bottom w-100">&nbsp;</div> 
        <textarea class="form-control" id='newComment' rows="4" name='newComment' placeholder="Enter your note here"></textarea><br />
        <button class="form-control pale-green-link" id='submitCommentBtn'>Submit</button>
        
    </div>
</div>
<div style='clear: both'></div>

<div id='commentlist' class='justify-content-center'>
</div>
<?php
  
//Note - change the "x" in the data toggle to the comment id
/* for ($x=1; $x<10; $x++) {
?>
    <div class="card m-2 w-100">
        <div class="card-header">
            <div class="float-right card-heading-border card-heading-inverse border rounded pl-1 pr-1 mr-2">Roger Officer</div>
            12 Mar 2019, 3:32pm
        </div>
        <div class="card-body comment-card">
            <div class=" overflow-auto" style="max-height: 130px">
            A comment was made about this case which was very interesting.<Br /> and went on for a while.<p>Hi there</p>
            <br />
            <br />
            .
            <br />
            </div>
        </div>
    </div>
<?php
} */
?>
