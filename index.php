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
    //echo PHP_VERSION;
    /* REMOVE THE FOLLOWING LINES IN PRODUCTION ENVIRONMENT */
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    require 'vendor/autoload.php';
    
    /* Attempt to start the session */
    if (!@session_start()) {
        // If session_start() fails, use database session storage
        class DbSessionHandler implements SessionHandlerInterface {
            private $pdo;
            private $table;
        
            public function __construct(PDO $pdo, $table = 'sessions') {
                $this->pdo = $pdo;
                $this->table = $table;
            }
        
            public function open(string $savePath, string $sessionName): bool {
                return true;
            }
        
            public function close(): bool {
                return true;
            }
        
            public function read(string $id): string {
                $stmt = $this->pdo->prepare("SELECT data FROM {$this->table} WHERE id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row ? $row['data'] : '';
            }
        
            public function write(string $id, string $data): bool {
                $timestamp = time();
                $stmt = $this->pdo->prepare("REPLACE INTO {$this->table} (id, data, timestamp) VALUES (:id, :data, :timestamp)");
                $stmt->bindParam(':id', $id, PDO::PARAM_STR);
                $stmt->bindParam(':data', $data, PDO::PARAM_STR);
                $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
                return $stmt->execute();
            }
        
            public function destroy(string $id): bool {
                $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_STR);
                return $stmt->execute();
            }
        
            public function gc(int $maxlifetime): int|false {
                $old = time() - $maxlifetime;
                $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE timestamp < :old");
                $stmt->bindParam(':old', $old, PDO::PARAM_INT);
                return $stmt->execute();
            }
        }
        require_once('config/config.php');
        // Database connection
        $pdo = new PDO('mysql:host='.$settings['dbhost'].';dbname='.$settings['dbname'], $settings['dbuser'], $settings['dbpass']);
        //Set the session handler table name
        $table = $settings['dbprefix'].'sessions';
        // Set custom session handler
        $handler = new DbSessionHandler($pdo, $table);
        session_set_save_handler($handler, true);
        
        // Start the session using the custom handler
        session_start();
    }
    /*                                                      */

    //Some useful values for each page
    $todaystart=mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $todayend=mktime(23,59,59, date("m"), date("d"), date("Y"));
    $monthstart=mktime(0,0,0, date("m"), 1, date("Y"));
    $yearstart=mktime(0,0,0, 1, 1, date("Y"));

    require_once "helpers/startup.php";

    //##########################################################################
    // COOKIE MANAGEMENT
    // First cookie is user/system preferences
    // Second cookie is current status, history, filter settings etc.
    $myDomain = preg_replace("/^[^.]*.([^.]*).(.*)$/", '1.2', $_SERVER['HTTP_HOST']);
    //$setDomain = ($_SERVER['HTTP_HOST']) != "localhost" ? ".$myDomain" : false;
    $cookiepath=dirname($_SERVER['PHP_SELF']);
    //$cookiepath="/atticase";
    //echo $cookiepath;
    $setDomain = ($_SERVER['HTTP_HOST']) != "localhost" ? $_SERVER['HTTP_HOST'] : false;
    
    $regex = "/^.*?\.([^:]*)/";
    preg_match($regex, $setDomain, $matches); //Remove the subdomain and any port information for the cookie storage to avoid "invalid domain" errors.
    if(count($matches) != 0) {
        $setDomain=$matches[count($matches)-1];
    }
    //echo $setDomain."<br />";
    $cookieOptions=array(
        "expires"=>time()+3600*24*(2),
        "path"=>$cookiepath,
        "domain"=>$setDomain,
        "secure"=>true,
        "httponly"=>false, //Set to true for better security - setting this to true will prevent the cookie from being accessed by JavaScript
        "samesite"=>"Strict"
    );
    
    // Read status cookie
    $status=isset($_COOKIE[$cookieStatusName]) ? $_COOKIE[$cookieStatusName] : array();

    if(!is_array($status)) { //This means that there IS a cookie
        $status=json_decode(stripslashes($status), true);
        if(json_last_error() !== JSON_ERROR_NONE) {
            $status=array("caseviews"=>array());
        }
        if(empty($status)) {
            $status=array("caseviews"=>array());
        }
        
        $case = filter_input(INPUT_GET, 'case', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($case) {
            if(isset($status['caseviews'][0]) && $status['caseviews'][0] != $case) {
                array_unshift($status['caseviews'], $_GET['case']);
            }
            if(isset($status['caseviews']) && count($status['caseviews']) > 10) {
                array_pop($status['caseviews']);
            }
        }
    }

    
    // Make any changes/alterations to status cookie
    //echo "<pre>PHP COOKIE BEFORE SET:"; print_r($_COOKIE); echo "</pre>";
    if(PHP_VERSION_ID < 70300) {
        setCookie($cookieSystemName, json_encode($_SESSION), $cookieOptions['expires'], $cookieOptions['path']."; samesite=".$cookieOptions['samesite'], $cookieOptions['domain'], $cookieOptions['secure'], $cookieOptions['httponly']);
        setCookie($cookieStatusName, json_encode($status), $cookieOptions['expires'], $cookieOptions['path']."; samesite=".$cookieOptions['samesite'], $cookieOptions['domain'], $cookieOptions['secure'], $cookieOptions['httponly']);
    } else {
        //echo "<pre>PHP SESSION:"; print_r($_SESSION); echo "</pre>";
        //echo $cookieSystemName;
        //print_r(urlencode(json_encode($_SESSION)));
        
        setCookie($cookieSystemName, json_encode($_SESSION), $cookieOptions);
        setCookie($cookieStatusName, json_encode($status), $cookieOptions);
        //test to see if these cookies have been succesfully set?


        //echo "Setting cookies - $cookieSystemName and $cookieStatusName<br />";
       // echo "<pre>PHP Cookie Setting"; print_r($_COOKIE); echo "</pre>"; 
    }   
    
    
    
    
    
    
    
    
    
    // #########################################################################
    // GENERIC SETTINGS    
    $user_id=isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $user_name=isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
    $user_real_name=isset($_SESSION['real_name']) ? $_SESSION['real_name'] : null;
    $is_admin=isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : null;
    
    // CURRENT FILTER SETTINGS
    $filter_user_id=isset($_SESSION['filter_user_id']) ? $_SESSION['filter_user_id'] : $user_id;
    $filter_text=isset($_SESSION['filter_text']) ? $_SESSION['filter_text'] : "";
    $filter_case_type=isset($_SESSION['filter_case_type']) ? $_SESSION['filter_case_type'] : null;
    $filter_case_status=isset($_SESSION['filter_case_status']) ? $_SESSION['filter_case_status'] : null;
    
    // NAVIGATION
    $page=isset($_GET['page']) ? $_GET['page'] : "dashboard";
    
?>
<html>
    <head>
        <title>
            AttiCase 3
        </title>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
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

    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
    </script>
    <!-- Encryption -->
     <script src="js/crypto-js.min.js"></script>
    <!-- Casetracker javascripts -->
    <script src="js/default.js"></script>
    <script src="js/index.js"></script>
    <?php
    if($oct->getSetting("externaldb", "useexternaldb")==1 && $oct->getSetting("externaldb", "externaldb") != "") {
        if(file_exists("helpers/externaldb/".$configsettings['externaldb']['externaldb']['value'].".js")) {
            ?>
            <script src="helpers/externaldb/<?= $configsettings['externaldb']['externaldb']['value'] ?>.js"></script>
            <?php
        };
    }
    ?>
    <script>
        var cookiePrefix='<?= $oct->cookiePrefix ?>';
        //console.log('Setting off search for global and status cookies')
        var globals=getSettings('<?= $cookieSystemName ?>');
        var status=getSettings('<?= $cookieStatusName ?>');
        //console.log('Globals');
        //console.log(globals);
        //status=getStatus();    
    </script>
    <!-- include summernote css/js -->
    <link href="js/summernote/summernote-bs4.css" rel="stylesheet">
    <script src="js/summernote/summernote-bs4.js"></script>    
    </head>
    <body>

    <!-- Message Centre -->
    <div class="modal fade" id="messageCentre" tabindex="-1" role="dialog" aria-labelledby="messageCentreTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="messageCentreModalContent">
            <div class="modal-header card-header">
            <h5 class="modal-title" id="messageCentreTitle">Message Centre</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" id="messageCentreMessage">
            ...
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>    

    <input type='hidden' name='today_start' id='today_start' value='<?php echo $todaystart ?>' />
    <input type='hidden' name='today_end' id='today_end' value='<?php echo $todayend ?>' />
    <input type='hidden' name='user_id' id='user_id' value='<?php echo $user_id ?>' />
    <input type='hidden' name='user_name' id='user_name' value='<?php echo $user_name ?>' />
    <input type='hidden' name='user_real_name' id='user_real_name' value='<?php echo $user_real_name ?>' />
    <input type='hidden' name='attachments_dir' id='attachments_dir' value='/var/attachments/' />
    <input type='hidden' name='set_domain' id='set_domain' value='<?=$setDomain ?>' />



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
            <!-- starting loaded page: <?= $page ?> -->
            <?php
            //check that the page exists
            if(file_exists("pages/".$page.".php")) {
                include("pages/".$page.".php");
            } else {
                //show a nice "can't find this" page
                include("pages/404.php");
            }
            ?>
            <?php    
            }
            ?>

    </body>
</html>
<?php 
    //echo "<pre>"; print_r($_COOKIE); echo "</pre>"; 
?>
