<?php
    
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
        if(isset($_POST['initialise']) && $_POST['initialise']=="true" && !empty($_POST['dbname']) && !empty($_POST['dbhost']) && !empty($_POST['dbuser']) && !empty($_POST['dbpass']) && !empty($_POST['dbprefix']) && ($_POST['useexternaldb']=="false" || ($_POST['useexternaldb']=="true" && !empty($_POST['externaldb'])))) {
            
            include("pages/initialise.php");
            
        } else {
    
            include("pages/initial.php");
            //echo "<hr /><pre>";print_r($_POST);
        }
    ?>
    </body>
</html>