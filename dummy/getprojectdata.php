<?php
date_default_timezone_set('America/Mexico_City');
$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");

$intProject = $_REQUEST['intProject'];

$jsnData = array('intPlanta'=>'','strPlanta'=>'','intDivision'=>'','strDivision'=>'','intSegmento'=>'','strSegmento'=>'','intPC'=>'','strPC'=>'','arrAPD'=>array());

$strSql = "SELECT plantas.id AS 'id_planta', plantas.nombre AS 'planta', ";
$strSql .= "divisiones.id AS 'id_division', divisiones.nombre AS 'division', ";
$strSql .= "segmentos.id AS 'id_segmento', segmentos.nombre AS 'segmento', ";
$strSql .= "profit_center.id AS 'id_pc', profit_center.nombre AS 'pc' ";
$strSql .= "FROM proyectos ";
$strSql .= "LEFT JOIN plantas ON plantas.id = proyectos.id_planta ";
$strSql .= "LEFT JOIN divisiones ON divisiones.id = proyectos.id_division ";
$strSql .= "LEFT JOIN segmentos ON segmentos.id = proyectos.id_segmento ";
$strSql .= "LEFT JOIN profit_center ON profit_center.id = proyectos.id_pc ";
$strSql .= "WHERE proyectos.id = " . $intProject . ";";

$rstData = mysqli_query($objCon, $strSql);
while($objData = mysqli_fetch_assoc($rstData)){
    $jsnData['intPlanta'] = $objData['id_planta'];
    $jsnData['strPlanta'] = $objData['planta'];
    $jsnData['intDivision'] = $objData['id_division'];
    $jsnData['strDivision'] = $objData['division'];
    $jsnData['intSegmento'] = $objData['id_segmento'];
    $jsnData['strSegmento'] = $objData['segmento'];
    $jsnData['intPC'] = $objData['id_pc'];
    $jsnData['strPC'] = $objData['pc'];
}
unset($objData);
mysqli_free_result($rstData);
unset($rstData);

$strSql = "SELECT 	apd.id AS 'id_apd', apd.nombre AS 'apd' ";
$strSql .= "FROM proyectos ";
$strSql .= "LEFT JOIN apd ON apd.id_division = proyectos.id_division AND apd.id_segmento = proyectos.id_segmento ";
$strSql .= "WHERE proyectos.id = " . $intProject . ";";

$rstData = mysqli_query($objCon, $strSql);
while($objData = mysqli_fetch_assoc($rstData)){
    array_push($jsnData['arrAPD'],array('intAPD'=>$objData['id_apd'],'strAPD'=>$objData['apd']));
}
unset($objData);
mysqli_free_result($rstData);
unset($rstData);


echo json_encode($jsnData);

mysqli_close($objCon);
unset($objCon);
?>