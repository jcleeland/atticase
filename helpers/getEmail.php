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


if(isset($configsettings['emailretrieval']['retrievalmethod']['value'])) {
    $emailretrievalmethod=$configsettings['emailretrieval']['retrievalmethod']['value'];
    if($emailretrievalmethod=="microsoft") {
        require_once __DIR__."/../helpers/emails_msgraph.php";

        $oauth_app_id = $configsettings['emailretrieval']['microsoftappid']['value'];
        $oauth_app_secret = $configsettings['emailretrieval']['microsoftappsecret']['value'];
        $oauth_tenant_id = $configsettings['emailretrieval']['microsofttenantid']['value'];
        //$oct->showArray($configsettings['emailretrieval']);
        $userEmail = $configsettings['emailretrieval']['microsoftaccount']['value'];
        
        if (empty($oauth_app_id) || empty($oauth_app_secret) || empty($oauth_tenant_id)) {
            die("Microsoft Graph API settings not set");
        }
        
        $mailbox = new MSGraphEmailHandler($oauth_app_id, $oauth_app_secret, $oauth_tenant_id);

    } elseif($emailretrievalmethod=="imap") {
        require_once __DIR__ . '/../helpers/emails_imap.php';
        $imap_server = $configsettings['emailretrieval']['imapretrievalhost']['value'];
        $imap_username = $configsettings['emailretrieval']['imapretrievalaccount']['value'];
        $imap_password = $configsettings['emailretrieval']['imapretrievalpass']['value'];
        $imap_port = $configsettings['emailretrieval']['imapport']['value'];
        $imap_options = $configsettings['emailretrieval']['imapoptions']['value'];
        $imap_mailbox = $configsettings['emailretrieval']['imapmailbox']['value'];
        $userEmail = $imap_username;
        if (empty($imap_server) || empty($imap_username) || empty($imap_password)) {
            die("IMAP settings not set");
        }
        
        $mailbox = new IMAPEmailHandler($imap_server, $imap_username, $imap_password, $imap_port, $imap_mailbox, $imap_options);        
    } elseif($emailretrievalmethod=="pop") {
        require_once __DIR__."/../helpers/emails_pop.php";
        $oct->showArray($configsettings['emailretrieval'], "Email Retrieval Settings");
        $pop3_server = $configsettings['emailretrieval']['popretrievalhost']['value'];
        $pop3_username = $configsettings['emailretrieval']['popretrievalaccount']['value'];
        $pop3_password = $configsettings['emailretrieval']['popretrievalpassword']['value'];
        $pop3_port = $configsettings['emailretrieval']['pop3port']['value'] ?? 110;
        $pop3_options = $configsettings['emailretrieval']['pop3options']['value'] ?? '';
        $userEmail = $pop3_username;
        if (empty($pop3_server) || empty($pop3_username) || empty($pop3_password)) {
            die("POP3 settings not set");
        }
        $mailbox=new POP3EmailHandler($pop3_server, $pop3_username, $pop3_password, $pop3_port, $pop3_options);

    }
} else {
    die("No email retrieval method set");
}


$emails = $mailbox->listEmails($userEmail);
if(!$emails || !is_array($emails) || count($emails)==0) {
    die("No emails found");
}

foreach($emails as $emailidentifier) {
    $oct->showArray($emailidentifier, "Email Identifier");
    $email=$mailbox->readEmail($userEmail, $emailidentifier['id']);
    $oct->showArray($email, "Email Message");
    $attachments=$mailbox->getEmailAttachments($userEmail, $emailidentifier['id']);
    $oct->showArray($attachments, "Email Attachments");
}

?>