<?php
session_start();
date_default_timezone_set('America/Mexico_City');
$objCon = mysqli_connect("localhost","root","","scrap_gdl");
mysqli_query($objCon, "SET NAMES 'utf8'");

$jsnEmpleado = array('blnGo'=>'false','strError'=>'');

$intProc = $_REQUEST['intProc'];
if($intProc==0){
    $strUser = $_REQUEST['strUser'];
    $strSql = "SELECT * FROM empleados WHERE usuario = '" . $strUser . "';";
    $rstEmpleado = mysqli_query($objCon, $strSql);
    if(mysqli_num_rows($rstEmpleado)!=0){
        while($objEmpleado=mysqli_fetch_assoc($rstEmpleado)){
            if($objEmpleado['activo']==1){
                if($objEmpleado['capturista']==1){
                    $strSql = "SELECT divisiones.id AS 'intDivision', divisiones.nombre AS 'strDivision', plantas.id AS 'intPlanta', plantas.nombre AS 'strPlanta' FROM capturistas, divisiones, plantas WHERE capturistas.id_emp = " . $objEmpleado['id'] . " AND capturistas.id_division = divisiones.id AND divisiones.id_planta = plantas.id;";
                    $rstData = mysqli_query($objCon,$strSql);
                    while($objData=mysqli_fetch_assoc($rstData)){
                        $_SESSION['intUser']=$objEmpleado['id'];
                        $_SESSION['strUser']=$objEmpleado['usuario'];
                        $_SESSION['strUName']=trim($objEmpleado['nombre']) . " " . trim($objEmpleado['apellidos']);
                        $_SESSION['intPlanta']=$objData['intPlanta'];
                        $_SESSION['strPlanta']=$objData['strPlanta'];
                        $_SESSION['intDivision']=$objData['intDivision'];
                        $_SESSION['strDivision']=$objData['strDivision'];
                        $jsnEmpleado['blnGo'] = true;
                    }
                    mysqli_free_result($rstData);
                    unset($rstData);
                }else{
                    $jsnEmpleado['strError'] = "El usuario " . $strUser . " no cuenta con el privilegio de captura";
                }
            }else{
                $jsnEmpleado['strError'] = "El usuario " . $strUser . " no se encuentra activo";
            }
        };
        unset($objEmpleado);
    }else{
        $jsnEmpleado['strError'] = "El numero de personal " . $strUser . " no se encuentra registrado en la Base de Datos";
    }
    mysqli_free_result($objEmpleado);
    unset($objEmpleado);
};

echo json_encode($jsnEmpleado);

mysqli_close($objCon);
unset($objCon);
?>