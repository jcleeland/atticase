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
        <h4 class="header">Add a note</h4>
        <div class="pager rounded-bottom w-100">&nbsp;</div> 
        <div class="row mb-2 mt-2">
            <div class="col-1"></div>
            <div class="col-7">
                <textarea class="form-control" id='newComment' rows="4" name='newComment' placeholder="Enter your note here"></textarea><br />
            </div>
            <div class="col-3">
                <div class="row">
                    <input type='hidden' id='allowtime' value='<?php echo $oct->config['comments']['allowtime']['value'] ?>' />
                    <input type='hidden' id='allowcost' value='<?php echo $oct->config['comments']['allowcost']['value'] ?>' />
<?php
//echo "<pre>"; print_r($oct->config); echo "</pre>";
if($oct->config['comments']['allowtime']['value']==1) {
    echo "  <div class='col-4 text-right'>Time spent</div>\n";   
    echo "  <div class='col-8'><input class='form-control' type='text' id='newTimeSpent' name='newTimeSpent' placeholder='Minutes' /></div>\n";
    echo "</div>\n<div class='row'>\n";
}
if($oct->config['comments']['allowcost']['value']==1) {
    echo "  <div class='col-4 text-right'>Cost</div>\n";   
    echo "  <div class='col-8'><input class='form-control' type='text' id='newCost' name='newCost' placeholder='Dollars' /></div>\n";
    echo "</div>\n<div class='row'>\n";
}    
?>
                    <div class='col'><button class="form-control pale-green-link" id='submitCommentBtn'>Submit</button></div>
                </div>            
            </div>
            <div class='col-1'></div>
        </div>
    </div>
</div>
<div style='clear: both'></div>
<?php
    if($oct->config['comments']['allowcost']['value']==1 || $oct->config['comments']['allowtime']['value']==1) {
        //Add heading with summary of cost &/or time spent
?>
    <div id='timeandcost' class='justify-content-center row border rounded ml-2 mr-2'>
        <div class='col-4'></div>
<?php
        if($oct->config['comments']['allowtime']['value']==1) {
        ?>
            <div id='totalTimeSpent' class='col-2'></div>
        <?php        
        }
        if($oct->config['comments']['allowcost']['value']==1) {
        ?>
            <div id='totalCost' class='col-2'></div>
        <?php        
        }        
?>    
        <div class='col-4'></div>
    </div>
<?php
    }
?>
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
