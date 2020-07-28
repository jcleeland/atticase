<?php
    session_start();
    include("scripts/authenticate.php"); 
    
    //NAVIGATION
    $page=isset($_GET['page']) ? $_GET['page'] : "dashboard";
?>
<html>
    <head>
        <title>
            OpenCaseTracker 3
        </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">        
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="css/default.css" />
    <script src="js/jquery/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="js/jquery/jquery-ui-1.12.1/jquery-ui.min.css" />
    <script src="js/jquery/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/default.js"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        //Load the Visualization API and the corechart package
        google.charts.load('current', {'packages':['corechart']});
    </script>    
    </head>
    <body>
        <?php
            if(!$_SESSION['authenticated']){
                include("pages/login.php");
            } else {
            ?>
         
            <?php
                include("pages/navbar.php");
            ?>

            <?php
                include("pages/header.php");
            ?>
            
            <?php
                include("pages/".$page.".php");
            ?>
            
            <?php    
            }
            ?>

    </body>
</html>
