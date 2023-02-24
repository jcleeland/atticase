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
<script src="js/pages/casetabs/attachments.js"></script>

<div class='justify-content-center'>
    <div class='float-right pale-green-link rounded mr-3 mb-1 p-1 small' id='newAttachmentBtn'>
        <img src='images/plus.svg' width='12px' /> Attachment
    </div>
    <div class='float-right m-2'>
        <input type='text' class='roundedcorners ml-4 form-control-sm form-transparent-sm text-muted' id='attachments-inpage_filter' title='Search currently showing results...' />
    </div>    
    <div style='clear: both'></div>
    <div class='hidden border rounded p-2 m-2' id='newAttachmentForm'>
        <form id="attachmentForm" name="attachmentForm" action="helpers/ajax/uploadAttachment.php" method="post" enctype="multipart/form-data">
            <input type='hidden' name='method' value='attachmentUpload' />
            <input type='hidden' name='userId' value='<?php echo $_SESSION['user_id'] ?>' />
            <input type='hidden' name='caseId' value='<?php echo $_GET['case'] ?>' />
            <h4 class="header">New attachment</h4>
            <div class="pager rounded-bottom w-100">&nbsp;</div>
            <div class='form-group'>
                <label for="attachmentFile">File</label>
                <input type="file" class="form-control" name="attachmentFile" id="attachmentFile" placeholder="Select file" aria-describedby="attachmentFileHelp" /> 
                <small id="attachmentFileHelp" class="form-text text-muted">Select the file you wish to upload here</small>
            </div>
            <div class='form-group'>
                <label for="attachmentFileDesc">Description</label>
                <input type="text" class="form-control" name="attachmentFileDesc" id="attachmentFileDesc" placeholder="Description of file" aria-describedby="attachmentFileDescHelp" />
                <small id="attachmentFileDescHelp" class="form-text text-muted">Put a detailed description of the file and its contents here so that others can recognise what is in it</small>
            </div>
            <input type="submit" value="Submit" class="form-control pale-green-link" id='submitAttachmentBtn'>
        </form>
    </div>
</div>
<div style='clear: both'></div>

<div id='attachmentlist' class="justify-content-center">
</div>
