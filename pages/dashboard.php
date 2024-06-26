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
 $accountdata=$oct->getUserAccount($user_id);

 //layouts
 // - one column 4 items  (1c4i)
 // - one column 2 items  (1c2i)
 // - two columns 3 items (2c3i) (top row two columns wide)
 // - two columns 4 items (2c4i)
 $item[]="noticeboard.php";
 $item[]="statistics.php";
 $item[]="mytodo.php";
 $item[]="myrecent.php";
 $item[]="unallocatedcases.php";
 
 $layout="2c4i";
 
 $dir_path='pages/dashboard';
 $files=scandir($dir_path);
 $dashboarditems=array();
    foreach($files as $file) {
        if ($file != '.' && $file != '..' && !is_dir($dir_path . '/' . $file)) {
            $filename_without_ext = pathinfo($file, PATHINFO_FILENAME);
            $nicename = ucfirst(strtolower($filename_without_ext)); 
            $coded=substr($nicename, 0, 6);
            //Decrypt with xor       
            $dashboarditems[$coded]=$nicename;
        }
    }
 if(!empty($accountdata['results'][0]['dateformat'])) {
     $layout=$accountdata['results'][0]['dateformat'];
 }
 if(!empty($accountdata['results'][0]['dateformat_extended'])) {
     $userdashboarditems=explode(",", $accountdata['results'][0]['dateformat_extended']);
     foreach($userdashboarditems as $key=>$val) {
         if($val=="") {
             
         } else {
            $item[$key]=strtolower($dashboarditems[$val]).".php";
     
         }
     }
 }

 echo "<div class='container-fluid'>";
 
 switch($layout) {
     case "1c4i":
 ?>
     <div class="row h-fullleft">
         <div class="col-12 mb-1 d-flex flex-column h-25">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[0]); ?>
             </div>
         </div>
         <div class="col-12 mb-1 d-flex flex-column h-25">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[1]); ?>
             </div>
         </div>
         <div class="col-12 mb-1 d-flex flex-column h-25">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[2]); ?>
             </div>
         </div>
         <div class="col-12 mb-1 d-flex flex-column h-25">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[3]); ?>
             </div>
         </div>
     </div>
 <?php
         break;
     case "1c2i":
 ?>
     <div class="row h-fullleft">
         <div class="col-12 mb-1 d-flex flex-column h-50">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[0]); ?>
             </div>
         </div>
         <div class="col-12 mb-1 d-flex flex-column h-50">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[1]); ?>
             </div>
         </div>
     </div>
 <?php
         break;
     case "2c3i":
 ?>
     <div class="row h-fullleft">
         <div class="col-12 col-lg-12 mb-1 d-flex flex-column h-50">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[0]); ?>
             </div>
         </div>
         <div class="col-12 col-lg-6 mb-1 d-flex flex-column h-50">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[1]); ?>
             </div>
         </div>
         <div class="col-12 col-lg-6 mb-1 d-flex flex-column h-50">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[2]); ?>
             </div>
         </div>
     </div>
 <?php
         break;
     case "2c3ib":
 ?>
     <div class="row h-fullleft">
         <div class="col-12 col-lg-6 mb-1 d-flex flex-column h-100">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[0]); ?>
             </div>
         </div>
         <div class="col-12 col-lg-6 mb-1 d-flex flex-column h-50">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[1]); ?>
             </div>
         </div>
         <div class="col-12 mb-1 d-flex flex-column h-50">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[2]); ?>
             </div>
         </div>
     </div>
 <?php
         break;
     case "2c1f2h":
 ?>
     <div class="row h-fullleft">
         <div class="col-12 col-lg-6 mb-1 d-flex flex-column h-100">
             <div class="border rounded m-1 flex-fill">
                 <?php include("pages/dashboard/".$item[0]); ?>
             </div>
         </div>
         <div class="col-12 col-lg-6 d-flex flex-column mb-1 h-100">
             <div class="border rounded m-1 flex-fill h-halfleft">
                 <?php include("pages/dashboard/".$item[1]); ?>
             </div>
             <div class="border rounded m-1 flex-fill h-halfleft">
                 <?php include("pages/dashboard/".$item[2]); ?>
             </div>
         </div>
     </div>
 <?php
         break;
     case "2c4i":
     default:
 ?>
     <div class="row h-fullleft">
         <div class="col-12 col-lg-6 mb-1 d-flex flex-column h-halfleft">
             <div class="border rounded m-1 flex-fill h-fullleft">
                 <?php include("pages/dashboard/".$item[0]); ?>
             </div>
         </div>
         <div class="col-12 col-lg-6 mb-1 d-flex flex-column h-halfleft">
             <div class="border rounded m-1 flex-fill h-fullleft">
                 <?php include("pages/dashboard/".$item[1]); ?>
             </div>
         </div>
         <div class="col-12 col-lg-6 mb-1 d-flex flex-column h-halfleft">
             <div class="border rounded m-1 flex-fill h-fullleft">
                 <?php include("pages/dashboard/".$item[2]); ?>
             </div>
         </div>
         <div class="col-12 col-lg-6 mb-1 d-flex flex-column h-halfleft">
             <div class="border rounded m-1 flex-fill h-fullleft">
                 <?php include("pages/dashboard/".$item[3]); ?>
             </div>
         </div>
     </div>
 <?php
         break;
 }
 
 echo "</div>";
 ?> 

 
