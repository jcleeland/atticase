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
<html>
    <head>
        <title>
            AttiCase 3
        </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">        
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="css/default.css" />
    <!-- Jquery -->
    <script src="js/jquery/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="js/jquery/jquery-ui-1.12.1/jquery-ui.min.css" />
    <script src="js/jquery/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <!-- Popper.js (must be before bootstrap and after jquery) -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap -->
    <script src="js/bootstrap/bootstrap.min.js"></script>

    <!-- Casetracker javascripts -->
    <script src="js/default.js"></script>
    <script src="js/index.js"></script>

    </head>
    <body>

    <input type='hidden' name='today_start' id='today_start' value='<?php echo $todaystart ?>' />
    <input type='hidden' name='today_end' id='today_end' value='<?php echo $todayend ?>' />
    <input type='hidden' name='user_id' id='user_id' value='<?php echo $user_id ?>' />
    <input type='hidden' name='user_name' id='user_name' value='<?php echo $user_name ?>' />
    <input type='hidden' name='user_real_name' id='user_real_name' value='<?php echo $user_real_name ?>' />
    <input type='hidden' name='attachments_dir' id='attachments_dir' value='/var/attachments/' />
<?php
$filecontents='<?php
  /** File created by AttiCase initialisation system **/
  
  $settings["dbtype"]="mysql";
  $settings["dbname"]="'.$_POST['dbname'].'";
  $settings["dbhost"]="'.$_POST['dbhost'].'";
  $settings["dbuser"]="'.$_POST['dbuser'].'";
  $settings["dbpass"]="'.$_POST['dbpass'].'";
  $settings["dbprefix"]="'.$_POST['dbprefix'].'";
  
  $settings["useexternaldb"]='.$_POST['useexternaldb'].';
  $settings["externaldb"]="'.$_POST['externaldb'].'";
?>';
$dirname=dirname(__FILE__)."/../config";
if(!is_dir($dirname)) {
    if(@!mkdir($dirname)) {
        die("Could not create configuration directory. See your system administrator.");
    }
}
$filename=dirname(__FILE__)."/../config/config.php";
$file=@fopen($filename, "w") or die("Unable to open config.php for writing</body></html>");
fwrite($file, $filecontents);
fclose($file);

//TODO: Now check to see if there is actually a database that matches this configuration.
// - if there is, check if it contains any tables.
//    - if there are, then check the version number of the database
//    - if there aren't, then offer the opportunity to setup the database
// - if there isn't, offer the opportunity to create the database (including setup)
//
?>
        <h1>Basic Configuration File has been created.</h1>
        <a href='index.php'>Start</a>
    </body>
</html>