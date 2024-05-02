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

    <?php

        if(isset($_POST['initialise']) && $_POST['initialise']=="true" && !empty($_POST['dbname']) && !empty($_POST['dbhost']) && !empty($_POST['dbuser']) && !empty($_POST['dbprefix']) && ($_POST['useexternaldb']=="false" || ($_POST['useexternaldb']=="true" && !empty($_POST['externaldb'])))) {
            
            include("pages/initialise.php");
            
        } else {
    
            include("pages/initial.php");
            //echo "<hr /><pre>";print_r($_POST);
        }
    ?>
    </body>
</html>