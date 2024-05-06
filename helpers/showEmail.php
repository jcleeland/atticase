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
require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../helpers/oct.php';

$oct=new oct;
$oct->dbuser=$settings['dbuser'];
$oct->dbpass=$settings['dbpass'];
$oct->dbhost=$settings['dbhost'];
$oct->dbname=$settings['dbname'];
$oct->dbprefix=$settings['dbprefix'];
$oct->connect();

require_once __DIR__."/../helpers/configsettings.php";

use Laminas\Mail\Storage\Part as LaminasPart;
use Laminas\Mail\Storage\Message as LaminasMessage;
use Hfig\MAPI;
use Hfig\MAPI\OLE\Pear;

//Clean up old temporary attachment files
$tempPath = __DIR__."/../tmp";
$attachmentPath = __DIR__."/../tmp/attachments";
$attachmentWebPath="tmp/attachments";
$expirationTime=3600; //3600 seconds = 1 hour
if(@$handle=opendir($attachmentPath)) {
    while(false !== ($file = readdir($handle))) {
        if($file != "." && $file != "..") {
            $filePath=$attachmentPath.'/'.$file;
            if(filemtime($filePath) < time() - $expirationTime) {
                if(is_file($filePath)) {
                    @unlink($filePath);
                }
            }
        }
    }
}

if(isset($_POST['attachmentId'])) {
    $attachmentId=$_POST['attachmentId'];
    $fileinfo=$oct->getAttachment($attachmentId);
    $attachmentdir=$oct->getSetting("installation", "attachmentdir");
    $filename=$fileinfo['results']['file_name'];
    $origname=$fileinfo['results']['orig_name'];
    $filetype=$fileinfo['results']['file_type'];
    
    if(file_exists("$attachmentdir/$filename")) {
        $path="$attachmentdir/$filename";
        $orig_name=urlencode($origname);
        $extension = strtolower(pathinfo($origname, PATHINFO_EXTENSION));
        $content = '';
        $attachments = [];  // Array to store attachment data


        if ($extension === 'msg') {
/**
 * Handling *.MSG files
 */

            // Handle MSG file using Hfig\MAPI
            try {
                $messageFactory = new MAPI\MapiMessageFactory();
                $documentFactory = new Pear\DocumentFactory();
                @$ole = $documentFactory->createFromFile($path);
                @$msg = $messageFactory->parseMessage($ole);
                //echo "<pre>"; var_dump(get_class_methods($msg)); echo "</pre>";
                
                $subject = $msg->properties['subject'];
                //print_r($subject); die();
                $from = $msg->getSender();  // or $msg->properties['sender'] depending on library implementation
                $to='';
                foreach($msg->getRecipients() as $recipient) {
                    $to.=$recipient->getType().(string)$recipient."\n";
                }
                //$to = $msg->getRecipients(); // Similar access might be needed
                //print_r($to);
                $msgdate = $msg->getSendTime(); // or another method depending on the exact property names
                $date=$msgdate->format("Y-m-d H:i");
                $content = $msg->getBodyHTML(); // Assuming simple retrieval of the body
                
                $msgattachments=$msg->getAttachments();
                foreach($msgattachments as $msgattachment) {
                    //echo "<pre>"; var_dump(get_class_methods($msgattachment)); echo "</pre>";
                    $filename=$msgattachment->getFileName();
                    $attachmentfilecontent=$msgattachment->getData();
                    file_put_contents($attachmentPath."/$filename", $attachmentfilecontent);
                    $attachments[] = [
                        'path' => $attachmentPath."/$filename",
                        'url'  => $attachmentWebPath."/$filename",
                        'name' => $filename
                    ];
                }
            } catch (\Throwable $e) {
                echo "Error: ". $e->getMessage();
            }






/**
 * Handling *.EML files
 */

        } elseif ($extension === 'eml') {
            // Handle EML file using Laminas\Mail
            $eml = file_get_contents($path);
            //echo "<pre style='scroll-y: auto; max-height: 300px'>"; print_r($eml); echo "</pre>";
            $message = new LaminasMessage(['raw' => $eml]);
            $headers=$message->getHeaders();
            //echo "<pre style='scroll-y: auto; max-height: 300px'>"; print_r($headers); echo "</pre>";
            $imageCidMap = [];

            $subject = $message->subject;
            $fromAddress = $headers->get('from')->getAddressList()->current();
            $from=$fromAddress->getName()." &lt;".$fromAddress->getEmail()."&gt;";
            $toAddress=$headers->get('to')->getAddressList()->current();
            $to=$toAddress->getName()." &lt;".$toAddress->getEmail()."&gt;";
            $date = new DateTime($message->date);
            $date = $date->format('Y-m-d H:i a');
            //print_r($date);
            //echo "<pre style='scroll-y: auto; max-height: 500px'>"; var_dump($message->from); echo "</pre>";
            
            if ($message->isMultipart()) {
                foreach (new \RecursiveIteratorIterator($message) as $part) {
                    
                    $contentType = strtok($part->getHeaderField('Content-Type'), ';');
                    
                    
                    $contentTransferEncoding = '8bit';  // Default to '8bit'
                    //print_r($part); echo "<hr />";

                    try {
                        // Attempt to retrieve the Content-Transfer-Encoding header
                        $contentTransferEncoding = strtolower($part->getHeaderField('Content-Transfer-Encoding'));
                    } catch (\Laminas\Mail\Storage\Exception\InvalidArgumentException $e) {
                        // If the header is not found, it defaults to '8bit'
                    }

                    if ($contentType === 'text/html' || $contentType === 'text/plain') {
                        $partContent = $part->getContent();
                        if ($contentTransferEncoding === 'base64') {
                            $partContent = base64_decode($partContent);
                        }
                        if ($contentType === 'text/html') {
                            $content = $partContent;  // Prefer HTML content if available
                            break;
                        } else {
                            $content = nl2br($partContent);  // Convert to HTML for display
                            //$content=$partContent;
                        }
                    } elseif ($part->getHeaderField('Content-Disposition') === 'inline' &&
                            $part->getHeaderField('Content-ID') != '') {
                        $cid = str_replace(['<', '>'], '', $part->getHeaderField('Content-ID'));
                        $fileExtension = substr($contentType, strpos($contentType, '/') + 1);
                        $imagePath =  $tempPath."/".$cid.$fileExtension;
                        $imageData = $part->getContent();
                        if ($contentTransferEncoding === 'base64') {
                            $imageData = base64_decode($imageData);
                        }
                        file_put_contents($imagePath, $imageData);
                        $imageCidMap[$cid] = $imagePath;
                    } else if ($part->getHeaderField('Content-Disposition', \Laminas\Mail\Header\HeaderInterface::FORMAT_RAW) == 'attachment') {
                        $filename = $part->getHeaderField('Content-Disposition', \Laminas\Mail\Header\HeaderInterface::FORMAT_PARAMETER)['filename'];
                        $attachmentData = $part->getContent();
                        if ($contentTransferEncoding === 'base64') {
                            $attachmentData = base64_decode($attachmentData);
                        }
                        file_put_contents($attachmentPath."/$filename", $attachmentData);
                        $attachments[] = [
                            'path' => $attachmentPath."/$filename",
                            'url'  => $attachmentWebPath."/$filename",
                            'name' => $filename
                        ];
                    }
                }
                
                foreach ($message as $part) {
                    try {
                        $contentDisposition = $part->getHeaderField('Content-Disposition', \Laminas\Mail\Header\HeaderInterface::FORMAT_RAW);
                        //print_r($contentDisposition);
                        if (strpos(strtolower($contentDisposition[0]), 'attachment') !== false) {
                            $filename = $contentDisposition['filename']; // Get the filename from the part
                            $attachmentData = $part->getContent();
                            //print_r($attachmentData);
                            $contentTransferEncoding = $part->getHeaderField('Content-Transfer-Encoding', \Laminas\Mail\Header\HeaderInterface::FORMAT_RAW);
                            //print_r($contentTransferEncoding);
                            if (strtolower($contentTransferEncoding[0]) === 'base64') {
                                $attachmentData = base64_decode($attachmentData);
                            }
                            if (strtolower($contentTransferEncoding[0]) === 'quoted-printable') {
                                $attachmentData = quoted_printable_decode($attachmentData);
                            }

                            file_put_contents($attachmentPath."/$filename", $attachmentData);
                            $attachments[] = [
                                'path' => $attachmentPath."/$filename",
                                'url'  => $attachmentWebPath."/$filename",
                                'name' => $filename
                            ];
                        }
                    } catch (\Exception $e) {
                        // Handle exceptions or missing headers gracefully
                    }
                }

            } else {
                // Non-multipart (simple single part message)
                $content = $message->getContent();
                $contentType = strtok($message->getHeaderField('Content-Type'), ';');
                $contentTransferEncoding='8bit';
                try {
                    $contentTransferEncoding = $message->getHeaderField('Content-Transfer-Encoding');
                } catch (\Laminas\Mail\Storage\Exception\InvalidArgumentException $e) {
                    
                }
                if (strtolower($contentTransferEncoding) == 'base64') {
                    $content = base64_decode($content);
                }
                if (strtolower($contentTransferEncoding) == 'quoted-printable') {
                    $content = quoted_printable_decode($content);
                }
                if ((isset($contentType) && $contentType !== 'text/html') || (!isset($contentType))) {
                    $content = nl2br($content);
                }
            }

            // Replace CID references in the content
            foreach ($imageCidMap as $cid => $url) {
                $content = str_replace("cid:$cid", $url, $content);
            }
        } else {
            $content = "Unsupported file type.";
        }

        $header=<<<HTML
        <div class="email-header">
            <div class="email-header-row">
                <h1><b>$subject</b></h1>
            </div>
            <div class="email-header-row">
                <div class='emailheaderlabel'>From:</div><div class="emailheadercontent">$from</div>
            </div>
            <div class="email-header-row">
                <div class='emailheaderlabel'>To:</div><div class="emailheadercontent">$to</div>
            </div>
            <div class="email-header-row">
                <div class='emailheaderlabel'>Date:</div><div class="emailheadercontent">$date</div>
            </div>
        </div>
        HTML;
        if(isset($attachments) && count($attachments) > 0) {
            $attachmentaddon='<div class="email-header email-header-row"><p><div class="emailheaderlabel">Attachments:</div><div class="emailheadercontent">';
            foreach ($attachments as $attachment) {
                $attachmentaddon.='<div class="emailattachment floatleft smaller"><a href="' . htmlspecialchars($attachment['url']) . '" download="' . htmlspecialchars($attachment['name']) . '"><img src="images/portfolio.svg" title="' . htmlspecialchars($attachment['name']) . '" />'.htmlspecialchars($attachment['name']).'</a></div>';
            }
            $attachmentaddon.="<div style='clear: both'></div></div></div>";
            $header.=$attachmentaddon;
        }
        echo $header.$content;
    } else {
        echo "<center><i>The attachment does not exist in AttiCase's file storage. See your system administrator.</i></center>";
    }
} else {
    echo "No attachment ID provided.";
}
?>