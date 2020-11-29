<?php
echo "Hi";
if(!isset($_POST['filename'])) {
    echo json_encode("No filename provided");
    die();
}
$fileName=$_POST['filename'];
$message="";
if(!file_exists($fileName)) {
    echo "File not found $fileName";
}

if(@$fh=fopen($fileName, 'r')) {
    while(!feof($fh)) {
        $message .= fgets($fh);
    } 
    fclose($fh);
} else {
    echo "Could not open file $fileName";
    $message = "";
}
echo $filename."-".$output;
$output=$message;
  
?>
