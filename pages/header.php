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
    $pages=array(""=>$page);
    if($page=="case") {
        $pages = array("cases"=>"cases")+$pages;
        $pages['']=$pages['']." ".$_GET['case'];
    }
?>
        <nav aria-label="breadcrumb">
                    
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard">AttiCase</a></li>
                <?php
                    foreach($pages as $thislink=>$thispage) {
                    ?>
                    <li class="breadcrumb-item active aria-current-page"><?php if($thislink != "") {?><a href="index.php?page=<?php echo $thislink ?>"><?php } ?><?php echo ucfirst($thispage) ?><?php if($thislink != "") {?></a><?php } ?></li>
                    <?php    
                    }
                ?>
                
            </ol>
        </nav>  
