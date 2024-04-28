<?php
//Turn off for production installation
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../config/config.php';
require_once "../../helpers/oct.php";

$oct=new oct;
$oct->dbuser=$settings['dbuser'];
$oct->dbpass=$settings['dbpass'];
$oct->dbhost=$settings['dbhost'];
$oct->dbname=$settings['dbname'];
$oct->dbprefix=$settings['dbprefix'];
$oct->connect();

require_once "../../helpers/configsettings.php";

require_once("oms.php");
$oms=new oms;

$oms->username=$configsettings['externaldb']['extdbuser']['value'];
$oms->password=$configsettings['externaldb']['extdbpass']['value'];

include "../../scripts/authenticate.php";   

$method=isset($_POST['method']) ? $_POST['method'] : null;

if($method=="getPerson") {
    $member=isset($_POST['member']) ? $_POST['member'] : null;
    
    $output=$oms->getOmsMemberSummary($member, 1);
    echo json_encode($output);
}
?>