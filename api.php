<?php
checkApiKey();  
  
function checkApiKey()
{
    $headers = apache_request_headers();
    if (!isset($headers['Api-Key']) || $headers['Api-Key'] != 'incre#dhask%009xdyyYYY') {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(array('error' => 'Invalid API key.'));
        die();
    }
}      
?>
