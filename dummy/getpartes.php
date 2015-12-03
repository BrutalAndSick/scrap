<?php
date_default_timezone_set('America/Mexico_City');
$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");

$strData = $_REQUEST['strNumerodeParte'];
$intProc = $_REQUEST['intProc'];

$jsnData = array();

switch($intProc){
    case 0:
        $strSql = "SELECT nombre, descripcion FROM numeros_parte WHERE activo = 1 AND nombre LIKE ('" . $strData . "%') ORDER BY nombre LIMIT 500;";
        $rstData = mysqli_query($objCon, $strSql);
        while ($objData = mysqli_fetch_assoc($rstData)) {
            array_push($jsnData, array($objData['nombre'],$objData['descripcion']));
        }
        unset($objData);
        mysqli_free_result($rstData);
        unset($rstData);
        break;
    case 1:
        $strSql = "SELECT numeros_parte.id AS 'intNumerodeParte', numeros_parte.nombre AS 'strNumerodeParte', numeros_parte.descripcion AS 'strDescripcionParte', numeros_parte.tabla AS 'strTablaParte', numeros_parte.tipo AS 'strTipoParte', numeros_parte.costo AS 'decCostoParte', numeros_parte.unidad AS 'strUnidadParte', numeros_parte.global_pc AS 'strGlobalPCParte', unidades.descripcion AS 'strUnidadDescripcionParte', unidades.decimales AS 'intDecimalesParte' ";
        $strSql .= "FROM numeros_parte, unidades ";
        $strSql .= "WHERE numeros_parte.unidad = unidades.unidad ";
        $strSql .= "AND numeros_parte.nombre = '" . $strData . "';";
        $rstData = mysqli_query($objCon, $strSql);
        while ($objData = mysqli_fetch_assoc($rstData)) {
            array_push($jsnData,
                array('intNumerodeParte'=>$objData['intNumerodeParte'],
                    'strNumerodeParte'=>$objData['strNumerodeParte'],
                    'strDescripcionParte'=>$objData['strDescripcionParte'],
                    'strTablaParte'=>$objData['strTablaParte'],
                    'strTipoParte'=>$objData['strTipoParte'],
                    'decCostoParte'=>$objData['decCostoParte'],
                    'strUnidadParte'=>$objData['strUnidadParte'],
                    'strGlobalPCParte'=>$objData['strGlobalPCParte'],
                    'strUnidadDescripcionParte'=>$objData['strUnidadDescripcionParte'],
                    'intDecimalesParte'=>$objData['intDecimalesParte']));
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