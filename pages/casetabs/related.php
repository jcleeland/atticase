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
