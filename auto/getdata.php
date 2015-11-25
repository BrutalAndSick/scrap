<?php
date_default_timezone_set('America/Mexico_City');
$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");

$strData = $_REQUEST['strNumerodeParte'];
$intProc = $_REQUEST['intProc'];

$jsnData = array();

switch($intProc){
    case 0:
        $strSql = "SELECT nombre FROM numeros_parte WHERE activo = 1 AND nombre LIKE ('" . $strData . "%') ORDER BY nombre LIMIT 500;";
        $rstData = mysqli_query($objCon, $strSql);
        while ($objData = mysqli_fetch_assoc($rstData)) {
            array_push($jsnData, $objData['nombre']);
        }
        unset($objData);
        mysqli_free_result($rstData);
        unset($rstData);
        break;
    case 1:
        $strSql = "SELECT * FROM numeros_parte WHERE nombre = '" . $strData . "';";
        $rstData = mysqli_query($objCon, $strSql);
        while ($objData = mysqli_fetch_assoc($rstData)) {
            array_push($jsnData, array('intNumerodeParte'=>$objData['id'],'strDescripcionParte'=>$objData['descripcion']));
        }
        unset($objData);
        mysqli_free_result($rstData);
        unset($rstData);
        break;
}

echo json_encode($jsnData);

mysqli_close($objCon);
unset($objCon);
?>