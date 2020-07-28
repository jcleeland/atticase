<?php
  
?>
<div class='col-sm-12 mb-1'>
    <ul class="nav nav-tabs w-100">
        <li class="nav-item active no-wrap"><a class="nav-link nav-link-tab active" data-toggle="tab" href='#comments'><img src='images/book.svg' width='20px' class="float-left mr-1" title="comments" /><div class='float-left d-none d-sm-block'>Comments</div><div style='clear: both'></div></a></li>
        <li class="nav-item">
            <a class="nav-link nav-link-tab" data-toggle="tab" href='#attachments'>
                <img src='images/file.svg' width='20px' class="float-left mr-1" title="Attachments" />
                <div class='float-left d-none d-sm-block'>Attachments</div>
                <div style="clear: both"></div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link nav-link-tab" data-toggle="tab" href='#poi'>
                <img src='images/user.svg' width='20px' class="float-left mr-1" title="People of Interest" />
                <div class='float-left d-none d-sm-block'>POI</div><div style='clear: both'></div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link nav-link-tab" data-toggle="tab" href='#related'>
                <img src='images/external.svg' width='20px' class="float-left mr-1" title="Related" />
                <div class='float-left d-none d-sm-block'>Related</div><div style='clear: both'></div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link nav-link-tab" data-toggle="tab" href='#linked'>
                <img src='images/lock.svg' width='20px' class="float-left mr-1" title="Linked" />
                <div class='float-left d-none d-sm-block'>Linked</div><div style='clear: both'></div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link nav-link-tab" data-toggle="tab" href='#notifications'>
                <img src='images/telephone.svg' width='20px' class="float-left mr-1" title="Notifications" />
                <div class='float-left d-none d-sm-block'>Notifications</div><div style='clear: both'></div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link nav-link-tab" data-toggle="tab" href='#planning'>
                <img src='images/location.svg' width='20px' class="float-left mr-1" title="Planning" />
                <div class='float-left d-none d-sm-block'>Planning</div><div style='clear: both'></div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link nav-link-tab" data-toggle="tab" href='#billing'>
                <img src='images/creditcard.svg' width='20px' class="float-left mr-1" title="Billing" />
                <div class='float-left d-none d-sm-block'>Billing</div><div style='clear: both'></div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link nav-link-tab" data-toggle="tab" href='#history'>
                <img src='images/info.svg' width='20px' class="float-left mr-1" title="History" />
                <div class='float-left d-none d-sm-block'>History</div><div style='clear: both'></div>
            </a>
        </li>
    </ul>

    <div class='tab-content '>
        <div class='tab-pane active' id='comments'>
            <?php include("pages/casetabs/comments.php"); ?>
        </div>

        <div class='tab-pane' id='attachments'>
            <?php include("pages/casetabs/attachments.php"); ?>
        </div>
        
        <div class='tab-pane active' id='poi'>
        
        </div>        

        <div class='tab-pane container active' id='related'>
        
        </div>        

        <div class='tab-pane container active' id='linked'>
        
        </div>        

        <div class='tab-pane container active' id='notifications'>
        
        </div>        

        <div class='tab-pane container active' id='planning'>
        
        </div>        

        <div class='tab-pane container active' id='billing'>
        
        </div>
                
        <div class='tab-pane container active' id='history'>
        
        </div>        
    
    </div>
        
    
</div>
