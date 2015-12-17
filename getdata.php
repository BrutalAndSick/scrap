<?php
session_start();
date_default_timezone_set('America/Mexico_City');
$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");

$jsnData = array('arrData'=>array());

$strProc = $_REQUEST['intProc'];

switch($strProc){
    case "0":
        $intProyecto = $_REQUEST['intProyecto'];
        $_SESSION['intProyecto'] = $intProyecto;
        $_SESSION['strProyecto'] = $_REQUEST['strProyecto'];
        $_SESSION['intSegmento'] = $_REQUEST['intSegmento'];
        $_SESSION['strSegmento'] = $_REQUEST['strSegmento'];
        $_SESSION['intProfitCenter'] = $_REQUEST['intProfitCenter'];
        $_SESSION['strProfitCenter'] = $_REQUEST['strProfitCenter'];
        $strSql = "SELECT apd.id AS 'intAPD', apd.nombre AS 'strAPD' ";
        $strSql .= "FROM proyectos ";
        $strSql .= "LEFT JOIN apd ON apd.id_division = proyectos.id_division AND apd.id_segmento = proyectos.id_segmento ";
        $strSql .= "WHERE proyectos.id = " . $intProyecto . " ORDER BY strAPD;";
        $rstData = mysqli_query($objCon, $strSql);
        while($objData = mysqli_fetch_assoc($rstData)){
            array_push($jsnData['arrData'],array('intAPD'=>$objData['intAPD'],'strAPD'=>$objData['strAPD']));
        }
        unset($objData);
        mysqli_free_result($rstData);
        unset($rstData);
        break;
    case "1":
        $intProyecto = $_SESSION['intProyecto'];
        $_SESSION['intAPD'] = $_REQUEST['intAPD'];
        $_SESSION['strAPD'] = $_REQUEST['strAPD'];
        if($_SESSION['strAPD']=='WHSE'){
            $strSql = "SELECT areas.id AS 'intArea', areas.nombre AS 'strArea' FROM areas WHERE id IN (SELECT DISTINCT(id_area) FROM estaciones WHERE id IN (SELECT DISTINCT(id_tecnologia) FROM est_proyecto WHERE id_proyecto = " . $intProyecto . ") AND activo = 1) AND activo = 1 AND areas.nombre = 'WHSE' ORDER BY strArea;";
        }else{
            $strSql = "SELECT areas.id AS 'intArea', areas.nombre AS 'strArea' FROM areas WHERE id IN (SELECT DISTINCT(id_area) FROM estaciones WHERE id IN (SELECT DISTINCT(id_tecnologia) FROM est_proyecto WHERE id_proyecto = " . $intProyecto . ") AND activo = 1) AND activo = 1 ORDER BY strArea;";
        }
        $rstData = mysqli_query($objCon, $strSql);
        while($objData = mysqli_fetch_assoc($rstData)){
            array_push($jsnData['arrData'],array('intArea'=>$objData['intArea'],'strArea'=>$objData['strArea']));
        }
        unset($objData);
        mysqli_free_result($rstData);
        unset($rstData);
        break;
    case "2":
        $intArea = $_REQUEST['intArea'];
        $_SESSION['intArea'] = $intArea;
        $_SESSION['strArea'] = $_REQUEST['strArea'];
        $strSql = "SELECT estaciones.id AS 'intTecnologia', estaciones.nombre AS 'strTecnologia' ";
        $strSql .= "FROM estaciones, est_proyecto ";
        $strSql .= "WHERE estaciones.activo = 1 ";
        $strSql .= "AND estaciones.id_area = " . $intArea . " ";
        $strSql .= "AND est_proyecto.id_tecnologia = estaciones.id ";
        $strSql .= "AND est_proyecto.id_proyecto = " . $_SESSION['intProyecto'] ." ";
        $strSql .= "ORDER BY strTecnologia;";
        $rstData = mysqli_query($objCon, $strSql);
        while($objData = mysqli_fetch_assoc($rstData)){
            array_push($jsnData['arrData'],array('intTecnologia'=>$objData['intTecnologia'],'strTecnologia'=>$objData['strTecnologia']));
        }
        unset($objData);
        mysqli_free_result($rstData);
        unset($rstData);
        break;
    case "3":
        $intTecnologia = $_REQUEST['intTecnologia'];
        $_SESSION['intTecnologia'] = $intTecnologia;
        $_SESSION['strTecnologia'] = $_REQUEST['strTecnologia'];
        $strSql = "SELECT lineas.id AS 'intLinea', lineas.nombre AS 'strLinea' ";
        $strSql .= "FROM lineas, lineas_proy ";
        $strSql .= "WHERE lineas_proy.id_proyecto = " . $_SESSION['intProyecto'] . " ";
        $strSql .= "AND lineas.id_area = " . $_SESSION['intArea'] . " ";
        $strSql .= "AND lineas.id_estacion = " . $intTecnologia . " ";
        $strSql .= "AND lineas_proy.id_linea = lineas.id ";
        $strSql .= "AND lineas.activo = 1 ";
        $strSql .= "ORDER BY nombre;";
        $rstData = mysqli_query($objCon, $strSql);
        while($objData = mysqli_fetch_assoc($rstData)){
            array_push($jsnData['arrData'],array('intLinea'=>$objData['intLinea'],'strLinea'=>$objData['strLinea']));
        }
        unset($objData);
        mysqli_free_result($rstData);
        unset($rstData);
        break;
    case "4":
        $intLinea = $_REQUEST['intLinea'];
        $_SESSION['intLinea'] = $intLinea;
        $_SESSION['strLinea'] = $_REQUEST['strLinea'];
        $strSql = "SELECT defectos.id AS 'intDefecto', defectos.nombre AS 'strDefecto' ";
        $strSql .= "FROM defectos, def_proyecto ";
        $strSql .= "WHERE defectos.activo = 1 ";
        $strSql .= "AND defectos.id_area = " . $_SESSION['intArea'] . " ";
        $strSql .= "AND defectos.id_estacion = " . $_SESSION['intTecnologia'] . " ";
        $strSql .= "AND def_proyecto.id_defecto = defectos.id ";
        $strSql .= "AND def_proyecto.id_proyecto = " . $_SESSION['intProyecto'] . " ";
        $strSql .= "ORDER BY strDefecto;";
        $rstData = mysqli_query($objCon, $strSql);
        while($objData = mysqli_fetch_assoc($rstData)){
            array_push($jsnData['arrData'],array('intDefecto'=>$objData['intDefecto'],'strDefecto'=>$objData['strDefecto']));
        }
        unset($objData);
        mysqli_free_result($rstData);
        unset($rstData);
        break;
    case "5":
        $intDefecto = $_REQUEST['intDefecto'];
        $_SESSION['intDefecto'] = $intDefecto;
        $_SESSION['strDefecto'] = $_REQUEST['strDefecto'];
        $strSql = "SELECT causas.id AS 'intCausa', causas.nombre AS 'strCausa' ";
        $strSql .= "FROM causas, defecto_causa ";
        $strSql .= "WHERE causas.activo = 1 ";
        $strSql .= "AND defecto_causa.id_defecto = " . $intDefecto . " ";
        $strSql .= "AND defecto_causa.id_causa = causas.id ";
        $strSql .= "ORDER BY strCausa;";
        $rstData = mysqli_query($objCon, $strSql);
        while($objData = mysqli_fetch_assoc($rstData)){
            array_push($jsnData['arrData'],array('intCausa'=>$objData['intCausa'],'strCausa'=>$objData['strCausa']));
        }
        unset($objData);
        mysqli_free_result($rstData);
        unset($rstData);
        break;
    case "6":
        $intCausa = $_REQUEST['intCausa'];
        $_SESSION['intCausa'] = $intCausa;
        $_SESSION['strCausa'] = $_REQUEST['strCausa'];
        $strSql = "SELECT codigo_scrap.id AS 'intCodigoScrap', CONCAT(codigo_scrap.codigo, ' ', codigo_scrap.descripcion) AS 'strCodigoScrap' ";
        $strSql .= "FROM codigo_scrap, causa_codigo ";
        $strSql .= "WHERE codigo_scrap.activo = 1 ";
        $strSql .= "AND causa_codigo.id_causa = " . $intCausa . " ";
        $strSql .= "AND causa_codigo.id_codigo = codigo_scrap.id ";
        $strSql .= "GROUP BY codigo ";
        $strSql .= "ORDER BY strCodigoScrap;";
        $rstData = mysqli_query($objCon, $strSql);
        while($objData = mysqli_fetch_assoc($rstData)){
            array_push($jsnData['arrData'],array('intCodigoScrap'=>$objData['intCodigoScrap'],'strCodigoScrap'=>$objData['strCodigoScrap']));
        }
        unset($objData);
        mysqli_free_result($rstData);
        unset($rstData);
        break;
    case "7":
        $intCodigoScrap = $_REQUEST['intCodigoScrap'];
        $_SESSION['intCodigoScrap'] = $intCodigoScrap;
        $_SESSION['strCodigoScrap'] = $_REQUEST['strCodigoScrap'];
        $strSql = "SELECT proy_ubicacion.id AS 'intUbicacion', proy_ubicacion.ubicacion AS 'strUbicacion' ";
        $strSql .= "FROM proy_ubicacion ";
        $strSql .= "WHERE proy_ubicacion.id_proyecto = " . $_SESSION['intProyecto']  . " ";
        $strSql .= "ORDER BY strUbicacion;";
        $rstData = mysqli_query($objCon, $strSql);
        while($objData = mysqli_fetch_assoc($rstData)){
            array_push($jsnData['arrData'],array('intUbicacion'=>$objData['intUbicacion'],'strUbicacion'=>$objData['strUbicacion']));
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