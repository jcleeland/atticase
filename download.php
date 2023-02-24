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
session_start();
//error_reporting(E_ALL);
require_once("helpers/startup.php");

if(isset($_GET['attachmentid'])) {
    $fileinfo=$oct->getAttachment($_GET['attachmentid']);
    $attachmentdir=$oct->getSetting("installation", "attachmentdir");
    $filename=$fileinfo['results']['file_name'];
    $origname=$fileinfo['results']['orig_name'];
    $filetype=$fileinfo['results']['file_type'];
    if(file_exists("$attachmentdir/$filename")) {
        $path="$attachmentdir/$filename";
        $orig_name=urlencode($origname);

        header("Cache-Control: max-age=60"); //Fix for Internet explorer bug
        header("Pragma: public");
        header("Content-type: $filetype");
        header("Content-Disposition: attachment; filename=$origname");
        header("Content-transfer-encoding: binary\n");
        header("Content-length: " . filesize($path) . "\n");
        
        readfile("$path");
    }
    
}
  
?>
