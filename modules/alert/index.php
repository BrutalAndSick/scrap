<?php
ini_set("display_errors",1);
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('../../lib/scrap.php');
$objScrap = new clsScrap();
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/scrap.css">
    </head>
    <body>
    <div class=" divTitles ">Alertas</div>
    </body>
    </html>
<?php
unset($objScrap);
?>