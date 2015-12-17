<?php
date_default_timezone_set('America/Mexico_City');
$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");

$intArea = $_REQUEST['intArea'];

$jsnData = array('Estaciones'=>array());

$strSql = "SELECT estaciones.id, estaciones.nombre FROM estaciones WHERE id_area = " . $intArea . " AND activo = 1 ORDER BY 2;";

$rstData = mysqli_query($objCon, $strSql);
while($objData = mysqli_fetch_assoc($rstData)){
    array_push($jsnData['Estaciones'],array('intEstacion'=>$objData['id'],'strEstacion'=>$objData['nombre']));
}
unset($objData);
mysqli_free_result($rstData);
unset($rstData);

echo json_encode($jsnData);

mysqli_close($objCon);
unset($objCon);
?>