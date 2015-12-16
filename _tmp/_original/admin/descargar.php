<?php
$enlace = "archivos/".$id; 
header ("Content-Disposition: attachment; filename=".$id."\n\n"); 
header ("Content-Type: application/octet-stream");
header ("Content-Length: ".filesize($enlace));
readfile($enlace);
?>

