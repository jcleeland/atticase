<?php
require __DIR__ . '/../vendor/autoload.php';

use Laminas\Mail\Storage\Pop3;
use Laminas\Mail\Storage\Message;

class POP3EmailHandler {
    private $mailbox;

    public function __construct($server, $username, $password, $port = 110, $options = '') {
        $this->initializeMailbox($server, $username, $password, $port, $options);
    }

    // Initialize the POP3 mailbox
    private function initializeMailbox($server, $username, $password, $port, $options) {
        try {
            $this->mailbox = new Pop3([
                'host'     => $server,
                'user'     => $username,
                'password' => $password,
                'port'     => $port,
                'ssl'      => $options
            ]);
        } catch (\Laminas\Mail\Storage\Exception\InvalidArgumentException $e) {
            echo 'Invalid login credentials. Please check your username and password.';
            exit;
        } catch (\Exception $e) {
            echo 'An error occurred while trying to connect to the POP3 server: ' . $e->getMessage();
            exit;
        }
    }

    // List all emails in the inbox and get their individual ID
    public function listEmails($username) {
        try {
            $emailList = [];
            foreach ($this->mailbox as $messageNum => $message) {
                $emailList[] = [
                    'id' => $messageNum,
                    'subject' => $message->subject
                ];
            }
            return $emailList;
        } catch (\Exception $e) {
            echo 'Error listing emails: ' . $e->getMessage();
        }
    }

    // Download/read an email using the ID obtained in step 1
    public function readEmail($username, $emailId) {
        try {
            $message = $this->mailbox->getMessage($emailId);
            return [
                'id' => $emailId,
                'subject' => $message->subject,
                'body' => $message->getContent(),
                'attachments' => $this->getAttachments($message)
            ];
        } catch (\Exception $e) {
            echo 'Error reading email: ' . $e->getMessage();
        }
    }

    // Save the email and any attachments
    public function saveEmailAndAttachments($username, $emailId, $savePath) {
        try {
            $message = $this->mailbox->getMessage($emailId);

            // Save email content
            file_put_contents($savePath . '/' . $emailId . '.eml', $message->getContent());

            // Save attachments
            foreach ($this->getAttachments($message) as $attachment) {
                file_put_contents($savePath . '/' . $attachment['filename'], $attachment['content']);
            }
            return true;
        } catch (\Exception $e) {
            echo 'Error saving email and attachments: ' . $e->getMessage();
        }
    }

    // Get email attachments
    public function getEmailAttachments($username, $emailId) {
        try {
            $message = $this->mailbox->getMessage($emailId);
            return $this->getAttachments($message);
        } catch (\Exception $e) {
            echo 'Error reading attachments: ' . $e->getMessage();
        }
    }

    // Delete the email using the ID obtained in step 1
    public function deleteEmail($username, $emailId) {
        try {
            $this->mailbox->removeMessage($emailId);
            return true;
        } catch (\Exception $e) {
            echo 'Error deleting email: ' . $e->getMessage();
        }
    }

    // Helper function to extract attachments from a message
    private function getAttachments(Message $message) {
        $attachments = [];
        foreach (new \RecursiveIteratorIterator($message) as $part) {
            if ($part->getHeaders()->has('content-disposition') &&
                preg_match('/attachment/', $part->getHeaders()->get('content-disposition')->getFieldValue())) {
                $attachments[] = [
                    'filename' => $part->getHeaders()->get('content-disposition')->getParameter('filename'),
                    'content' => $part->getContent()
                ];
            }
        }
        return $attachments;
    }
}
