<?php
    session_start();
    /* REMOVE THE FOLLOWING LINES IN PRODUCTION ENVIRONMENT */
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    /*                                                      */
    
    require_once("helpers/startup.php");
    
    //NAVIGATION
    $page=isset($_GET['page']) ? $_GET['page'] : "dashboard";
    
    //Some useful values for each page
    $todaystart=mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $todayend=mktime(23,59,59, date("m"), date("d"), date("Y"));
    
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
    <script src="js/index.js"></script>    
    </head>
    <body>
    <input type='hidden' name='user_id' id='user_id' value='<?php echo $_SESSION['user_id'] ?>' />
    <input type='hidden' name='user_name' id='user_name' value='<?php echo $_SESSION['user_name'] ?>' />
    <input type='hidden' name='real_name' id='real_name' value='<?php echo $_SESSION['real_name'] ?>' />
    <input type='hidden' name='today_start' id='today_start' value='<?php echo $todaystart ?>' />
    <input type='hidden' name='today_end' id='today_end' value='<?php echo $todayend ?>' />
    <input type='hidden' name='admin_status' id='admin_status' value='<?php echo "" ?>' />
    <input type='hidden' name='attachments_dir' id='attachments_dir' value='/var/attachments/' />
        <?php
            if(!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']){
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
