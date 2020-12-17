<script src="js/pages/casetabs/attachments.js"></script>

<div class='justify-content-center'>
    <div class='float-right pale-green-link rounded mr-3 mb-1 p-1 small' id='newAttachmentBtn'>
        <img src='images/plus.svg' width='12px' /> Attachment
    </div>
    <div style='clear: both'></div>
    <div class='hidden border rounded p-2 m-2' id='newAttachmentForm'>
        <h4 class="header">New attachment</h4>
        <div class="pager rounded-bottom w-100">&nbsp;</div>
        <div class='form-group'>
            <label for="attachmentFile">File</label>
            <input type="file" class="form-control" id="attachmentFile" placeholder="Select file" aria-describedby="attachmentFileHelp" /> 
            <small id="attachmentFileHelp" class="form-text text-muted">Select the file you wish to upload here</small>
        </div>
        <div class='form-group'>
            <label for="attachmentFileDesc">Description</label>
            <input type="text" class="form-control" id="attachmentFileDesc" placeholder="Description of file" aria-describedby="attachmentFileDescHelp" />
            <small id="attachmentFileDescHelp" class="form-text text-muted">Put a detailed description of the file and its contents here so that others can recognise what is in it</small>
        </div>
        <button class="form-control pale-green-link" id='submitAttachmentBtn'>Submit</button>
        
    </div>
</div>
<div style='clear: both'></div>

<div id='attachmentlist' class="justify-content-center">
</div>
