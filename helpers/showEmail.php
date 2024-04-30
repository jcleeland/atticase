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

        if ($extension === 'msg') {
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
                /*$content = nl2br($content);
                $pattern = '/(<br\s*\/?>\s*)+/i';
                $content = preg_replace_callback($pattern, function($matches) {
                    // Count how many <br /> are in the full match
                    $count = substr_count($matches[0], '<br />');
                
                    // Return half the number of <br /> tags, rounded up
                    return str_repeat("<br />\n", ceil($count / 2));
                }, $content); */
            } catch (\Throwable $e) {
                echo "Error: ". $e->getMessage();
            }
        } elseif ($extension === 'eml') {
            // Handle EML file using Laminas\Mail
            $eml = file_get_contents($path);
            $message = new LaminasMessage(['raw' => $eml]);
            $headers=$message->getHeaders();
            //echo "<pre>"; print_r($headers); echo "</pre>";
            $imageCidMap = [];

            $subject = $message->subject;
            $fromAddress = $headers->get('from')->getAddressList()->current();
            $from=$fromAddress->getName()." &lt;".$fromAddress->getEmail()."&gt;";
            $toAddress=$headers->get('to')->getAddressList()->current();
            $to=$toAddress->getName()." &lt;".$toAddress->getEmail()."&gt;";
            $date = new DateTime($message->date);
            $date = $date->format('Y-m-d H:i a');
            //print_r($date);
            //echo "<pre>"; var_dump($message->from); echo "</pre>";
            
            if ($message->isMultipart()) {
                foreach (new \RecursiveIteratorIterator($message) as $part) {
                    $contentType = strtok($part->getHeaderField('Content-Type'), ';');
                    $contentTransferEncoding = strtolower($part->getHeaderField('Content-Transfer-Encoding'));

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
                        }
                    } elseif ($part->getHeaderField('Content-Disposition') === 'inline' &&
                              $part->getHeaderField('Content-ID') != '') {
                        $cid = str_replace(['<', '>'], '', $part->getHeaderField('Content-ID'));
                        $fileExtension = substr($contentType, strpos($contentType, '/') + 1);
                        $imagePath = __DIR__."/../tmp/$cid.$fileExtension";
                        $imageData = $part->getContent();
                        if ($contentTransferEncoding === 'base64') {
                            $imageData = base64_decode($imageData);
                        }
                        file_put_contents($imagePath, $imageData);
                        $imageCidMap[$cid] = $imagePath;
                    }
                }
            } else {
                // Non-multipart (simple single part message)
                $content = $message->getContent();
                if (strtolower($message->getHeaderField('Content-Transfer-Encoding')) == 'base64') {
                    $content = base64_decode($content);
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
            <h1><b>$subject</b></h1>
            <p><div class='emailheaderlabel'>From:</div> $from</p>
            <p><div class='emailheaderlabel'>To:</div> $to</p>
            <p><div class='emailheaderlabel'>Date:</div> $date</p>
        </div>
        HTML;
        echo $header.$content;
    } else {
        echo "File does not exist.";
    }
} else {
    echo "No attachment ID provided.";
}
?>