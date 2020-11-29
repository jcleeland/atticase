<?php

if(!isset($_POST['filename'])) {
    echo json_encode("No filename provided");
    die();
}
/* if(!isset($_POST['contents'])) {
    echo json_encode("No contents provided (\"".$_POST['contents']."\")");
    die();
}*/

if($_POST['contents']=='') {
    //Empties out a file
    fclose(fopen($_POST['filename'],'w'));
    $output="Saved";
} else {
    if(substr($_POST['filename'], -3)=="pdf") {
        if(mb_detect_encoding($_POST['contents'], "auto")=="UTF-8") {
            $_POST['contents']=utf8_decode($_POST['contents']);
        }
    }
    if($myfile=fopen($_POST['filename'], "w")) {
        if(fwrite($myfile, $_POST['contents'])) {
            $output="Saved";
        } else {
            $output="Unable to save: ".implode(" | ", error_get_last());
        }
        fclose($myfile);
    } else {
        $output="Unable to open file (".implode("|", error_get_last()).")";
    }
}


echo json_encode($output);
  
?>
