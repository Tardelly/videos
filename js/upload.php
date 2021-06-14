<?php
$directory = "/upload/".$_GET['user']."/";

if (!file_exists(__DIR__.$directory)){
    mkdir(__DIR__.$directory, 0777, true);
    $myfile = fopen(__DIR__.$directory."dados.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $_GET['nome']."\n");
    fwrite($myfile, $_GET['idade']."\n");
    fwrite($myfile, $_GET['sexo']."\n");
    fwrite($myfile, $_GET['obs']."\n");
    fclose($myfile);
}

$filename = 'AOI_'.$_GET['filename'].'_'.date('YmdHis') . '.jpeg';

$url = '';
if( move_uploaded_file($_FILES['webcam']['tmp_name'], __DIR__.$directory.$filename) ){
 $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . $directory . $filename;
}
// Return image url
echo $url;
?>