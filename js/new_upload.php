<?php
var_dump($_POST);
$directory = "/upload/".$_POST['user']."/";
print_r($_FILES);
//print_r($_POST);
//enctype="multipart/form-data"
$filename = round(microtime(true) * 1000);

$fname = $filename."_".$_POST['nome']."_".$_POST['idade']."_".$_POST['sexo'].".mp4";

move_uploaded_file($_FILES['data']['tmp_name'], __DIR__.$directory.$fname);
?>