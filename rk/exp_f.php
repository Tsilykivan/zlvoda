<?php
$file_name = "pok.dbf"; 
$file_path = "../rk/out/pok.dbf";

header('Content-Disposition: attachment;filename="' . $file_name . '"');
header('Pragma: no-cache');
readfile($file_path);
?>