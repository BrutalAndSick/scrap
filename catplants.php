<?php
ini_set('display_errors',1);
session_start();

if(!isset($_SESSION["intUser"]))
{
//    header("location: index.php");
}

date_default_timezone_set('America/Mexico_City');

$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");

?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>:: CONTINENTAL :: SCRAP ::</title>
        <link rel="stylesheet" type="text/css" href="css/scrap.css">
        <link rel="stylesheet" type="text/css" href="css/scrapcapture.css">
    </head>
    <body>
    <?php include_once('inc/header.php'); ?>
    <?php include_once('inc/menu.php'); ?>
    <script src="js/jquery-1.11.3.min.js"></script>
    </body>
    </html>
<?php
mysqli_close($objCon);
unset($objCon);
?>