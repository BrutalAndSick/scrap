<?php
require_once('../../include/config.php');
require_once(LIB_PATH .  'scrap.php');
$objScrap = new clsScrap();
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../../css/scrap.css">
    </head>
    <body>
    <div class=" divTitles ">Reportes Gráficos</div>
    </body>
    </html>
<?php
unset($objScrap);
?>