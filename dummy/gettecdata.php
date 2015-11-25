<?php
date_default_timezone_set('America/Mexico_City');
$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");

$intArea = $_REQUEST['intArea'];
$intEstacion = $_REQUEST['intTecnologia'];
$jsnData = array('Lineas'=>array(),'Defectos'=>array());

$strSql = "SELECT lineas.id, lineas.nombre FROM lineas WHERE id_area = " . $intArea . " AND id_estacion = " . $intEstacion . " AND activo = 1 ORDER BY 2;";

$rstData = mysqli_query($objCon, $strSql);
while($objData = mysqli_fetch_assoc($rstData)){
    array_push($jsnData['Lineas'],array('intLinea'=>$objData['id'],'strLinea'=>$objData['nombre']));
}
unset($objData);
mysqli_free_result($rstData);
unset($rstData);

$strSql = "SELECT defectos.id, defectos.nombre FROM defectos WHERE id_area = " . $intArea . " AND id_estacion = " . $intEstacion . " AND activo = 1 ORDER BY 2;";

$rstData = mysqli_query($objCon, $strSql);
while($objData = mysqli_fetch_assoc($rstData)){
    array_push($jsnData['Defectos'],array('intDefecto'=>$objData['id'],'strDefecto'=>$objData['nombre']));
}
unset($objData);
mysqli_free_result($rstData);
unset($rstData);

echo json_encode($jsnData);

mysqli_close($objCon);
unset($objCon);
?>