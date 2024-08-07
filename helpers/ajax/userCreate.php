<?php
/**
 * This script is - one time - called without the ajax.php parent script
 * So we need to check if the $oct object exists, and if it doesn't load it
 */
$normal=true;
//Check if session is started, and if not start it
if(session_status() == PHP_SESSION_NONE) {
    session_start();
    $normal=false;

}
if(!isset($oct)) {
    //echo __DIR__;
    require_once __DIR__.'/../../config/config.php';
    require_once __DIR__."/../oct.php";

    $oct=new oct;
    $oct->dbuser=$settings['dbuser'];
    $oct->dbpass=$settings['dbpass'];
    $oct->dbhost=$settings['dbhost'];
    $oct->dbname=$settings['dbname'];
    $oct->dbprefix=$settings['dbprefix'];
    $output=$oct->connect();
    $normal=false;
}



$username=isset($_POST['username']) ? $_POST['username'] : null;
$realname=isset($_POST['realname']) ? $_POST['realname'] : null;
$password=isset($_POST['password']) ? $_POST['password'] : null;
$email=isset($_POST['email']) ? $_POST['email'] : null;
$group=isset($_POST['group']) ? $_POST['group'] : null;
$phone=isset($_POST['phone']) ? $_POST['phone'] : null;
$notifytype=isset($_POST['notifytype']) ? $_POST['notifytype'] : null;
$notifyrate=isset($_POST['notifyrate']) ? $_POST['notifyrate'] : null;
$selfnotify=isset($_POST['selfnotify']) ? $_POST['selfnotify'] : null;
$strategyenabled=isset($_POST['strategyenabled']) ? $_POST['strategyenabled'] : null;
$defaulttaskview=isset($_POST['defaulttaskview']) ? $_POST['defaulttaskview'] : null;
$accountenabled=isset($_POST['accountenabled']) ? $_POST['accountenabled'] : null;

if($password) $password_hash = crypt($password, '4t6dcHiefIkeYcn48B');

$output=$oct->insertTable('users', array('user_name'=>$username, 'real_name'=>$realname, 'user_pass'=>$password_hash, 'email_address'=>$email, 'group_in'=>$group, 'jabber_id'=>$phone, 'notify_type'=>$notifytype, 'notify_rate'=>$notifyrate, 'self_notify'=>$selfnotify, 'strategy_enabled'=>$strategyenabled, 'default_task_view'=>$defaulttaskview, 'account_enabled'=>$accountenabled));

if($normal===false) {
    echo json_encode($output);
} else {
    return($output);
}

?>