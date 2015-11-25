<?php
ini_set('display_errors',1);
date_default_timezone_set('America/Mexico_City');
$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
        body {
            font-family: Arial;
            font-size: 11pt;
            font-weight: normal;
            padding: 0px 0px 0px 0px;
            background-color: #F1F1F1;
            margin: 0px 0px 0px 0px;
            color: #282828;
        }

        .grid {
            background-color: red;
            margin: 0px auto 0px auto;
        }
    </style>
</head>
<body>
    <div class=" grid ">
        <div>
        <?php
        $strSql = "SELECT COUNT(*) FROM numeros_parte;";
        $rstData = mysqli_query($objCon, $strSql);
        $intRecordCount = mysqli_num_rows($rstData);
        echo 'Registros: ' . $intRecordCount;
        if($intRecordCount>0){

        }else{
            echo "nada que hacer";
        };


        unset($rstData);
        ?>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);
?>