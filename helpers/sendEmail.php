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
ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__.'/../vendor/autoload.php';
session_start();
require_once __DIR__."/../helpers/startup.php";



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$mail = new PHPMailer(true);

//Check for variables required
if (!isset($_POST['to']) || !isset($_POST['message']) || !isset($_POST['subject'])) {
    return 'Missing required variables';
}

$fromemail=isset($_POST['from']) ? $_POST['from'] : $configsettings['emailsending']['fromemail']['value'];
$fromname=isset($_POST['fromname']) ? $_POST['fromname'] : $configsettings['general']['project_title']['value'];
$cc=isset($_POST['cc']) ? $_POST['cc'] : array();
$bcc=isset($_POST['bcc']) ? $_POST['bcc'] : array();
$attachments=isset($_POST['attachments']) ? $_POST['attachments'] : array();
$to=$_POST['to'];
$message=$_POST['message'];
$subject=$_POST['subject'];
$isHtml=isset($_POST['isHtml']) ? $_POST['isHtml'] : true;
$debuglevel=isset($configsettings['emailsending']['sendemaildebug']['value']) && $configsettings['emailsending']['sendemaildebug']['value'] ==true &&  isset($configsettings['emailsending']['smtp_debug']['value']) ? $configsettings['emailsending']['smtp_debug']['value'] : 0;

if($configsettings['emailsending']['email_testmode']['value']==true) {
    $to=$configsettings['emailsending']['email_test_address']['value'];
    $bcc=array(); //Overwrite any bcc settings
    $cc=array(); //Overwrite any cc settings
}

ob_start();
try {
    //Server settings
    $mail->isSMTP();     
    $mail->SMTPDebug = $debuglevel;                                 
    $mail->Host = $configsettings['emailsending']['smtphost']['value'];  
    $mail->SMTPAuth = $configsettings['emailsending']['smtp_auth']['value'];                               
    $mail->Username = $configsettings['emailsending']['smtpaccount']['value'];                 
    $mail->Password = $configsettings['emailsending']['smtppassword']['value'];                          
    if($configsettings['emailsending']['smtp_tls']['value']=="true") $mail->SMTPSecure = 'tls';                            
    $mail->Port = $configsettings['emailsending']['smtp_port']['value'];                                    

    //Recipients
    $mail->setFrom($fromemail, $fromname);
    $mail->addAddress($to);     

    if (!empty($cc)) {
        foreach ($cc as $address) {
            $mail->addCC($address['email'], $address['name']);
        }
    }

    if (!empty($bcc)) {
        foreach ($bcc as $address) {
            $mail->addBCC($address['email'], $address['name']);
        }
    }

    //Attachments
    if (!empty($attachments)) {
        foreach ($attachments as $attachment) {
            $mail->addAttachment($attachment['path'], $attachment['name']);
        }
    }

    //Content
    $mail->isHTML($isHtml);                                  
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = strip_tags($message);

    $mail->send();

    $debugOutput = ob_get_contents();

    $output=array(
        "status"=>"success",
        "message"=>'Message has been sent to '.$to,
        "debug"=>$debugOutput
    );

} catch (Exception $e) {
    $debugOutput = ob_get_contents();
    $output=array("status"=>"error", "message"=>'Message could not be sent to '.$to.'. Mailer Error: ' . $mail->ErrorInfo, "debug"=>$debutOutput);
    //echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
} finally {
    ob_end_clean();
    echo json_encode($output);
    exit();
}
?>