<?php
$file_name = "base_ab.dbf"; 
$file_path = "../rk/out/base_ab.dbf";

header('Content-Disposition: attachment;filename="' . $file_name . '"');
header('Pragma: no-cache');
readfile($file_path);
?>