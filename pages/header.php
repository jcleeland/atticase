<?php
    $pages=array(""=>$page);
    if($page=="case") {
        $pages = array("cases"=>"cases")+$pages;
        $pages['']=$pages['']." ".$_GET['case'];
    }
?>
        <nav aria-label="breadcrumb">
                    
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard">OpenCaseTracker</a></li>
                <?php
                    foreach($pages as $thislink=>$thispage) {
                    ?>
                    <li class="breadcrumb-item active aria-current-page"><?php if($thislink != "") {?><a href="index.php?page=<?php echo $thislink ?>"><?php } ?><?php echo ucfirst($thispage) ?><?php if($thislink != "") {?></a><?php } ?></li>
                    <?php    
                    }
                ?>
                
            </ol>
        </nav>  
