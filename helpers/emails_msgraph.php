<?php
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

class MSGraphEmailHandler {
    private $clientId;
    private $clientSecret;
    private $tenantId;
    private $graphClient;
    private $accessToken;

    public function __construct($clientId, $clientSecret, $tenantId) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tenantId = $tenantId;

        $this->initializeGraphClient();
    }

    // Get the access token using client credentials
    private function getAccessToken() {
        $client = new Client();
        $url = 'https://login.microsoftonline.com/' . $this->tenantId . '/oauth2/v2.0/token';

        $response = $client->post($url, [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => 'https://graph.microsoft.com/.default',
                'grant_type' => 'client_credentials',
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['access_token'];
    }

    // Initialize the Graph client
    private function initializeGraphClient() {
        $this->accessToken = $this->getAccessToken();

        $this->graphClient = new Client([
            'base_uri' => 'https://graph.microsoft.com/v1.0/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    // List all emails in the inbox and get their individual ID
    public function listEmails($userEmail) {
        try {
            $response = $this->graphClient->get('users/'.$userEmail.'/mailFolders/Inbox/messages');
            $messages = json_decode($response->getBody(), true);

            $emailList = [];
            foreach ($messages['value'] as $message) {
                $emailList[] = [
                    'id' => $message['id'],
                    'subject' => $message['subject']
                ];
            }
            return $emailList;
        } catch (\Exception $e) {
            echo 'Error listing emails: ' . $e->getMessage();
        }
    }

    // Download/read an email using the ID obtained in step 1
    public function readEmail($userEmail, $emailId) {
        try {
            $response = $this->graphClient->get("users/".$userEmail."/messages/$emailId");
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            echo 'Error reading email: ' . $e->getMessage();
        }
    }

    // Save the email and any attachments
    public function saveEmailAndAttachments($userEmail, $emailId, $savePath) {
        try {
            $email = $this->readEmail($userEmail, $emailId);

            // Save email content
            file_put_contents($savePath . '/' . $emailId . '.eml', $email['body']['content']);

            // Save attachments
            $response = $this->graphClient->get("users/".$userEmail."/messages/$emailId/attachments");
            $attachments = json_decode($response->getBody(), true);

            foreach ($attachments['value'] as $attachment) {
                if ($attachment['@odata.type'] === '#microsoft.graph.fileAttachment') {
                    file_put_contents($savePath . '/' . $attachment['name'], base64_decode($attachment['contentBytes']));
                }
            }
            return true;
        } catch (\Exception $e) {
            echo 'Error saving email and attachments: ' . $e->getMessage();
        }
    }

    public function getEmailAttachments($userEmail, $emailId) {
        try {
            $response = $this->graphClient->get("users/".$userEmail."/messages/$emailId/attachments");
            return json_decode($response->getBody(), true);
            //return json_decode($attachments, true);
        } catch (\Exception $e) {
            echo 'Error reading attachments: ' . $e->getMessage();
        }
    }

    // Delete the email using the ID obtained in step 1
    public function deleteEmail($userEmail, $emailId) {
        try {
            $this->graphClient->delete("users/".$userEmail."/messages/$emailId");
            return true;
        } catch (\Exception $e) {
            echo 'Error deleting email: ' . $e->getMessage();
        }
    }
}
