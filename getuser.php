<?php
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('lib/scrap.php');
$objScrap = new clsScrap();
$jsnEmpleado = array('blnGo'=>'true','strError'=>'');
$intProc = $_REQUEST['intProc'];
switch ($intProc){
    case 0:
        $strUser = $_REQUEST['strUser'];
        $strSql = "SELECT * ";
        $strSql .= "FROM USR_USER ";
        $strSql .= "LEFT JOIN CNT_COUNTRY ON CNT_COUNTRY.CNT_ID = USR_USER.USR_COUNTRY ";
        $strSql .= "WHERE USR_USER.USR_STAFF_NUMBER = '" . $strUser . "' ";
        $strSql .= "AND USR_USER.USR_STATUS = 1";
        $rstEmployee = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstEmployee as $objEmployee){
                $_SESSION['intUser']=$objEmployee['USR_ID'];
                $_SESSION['strUser']=$objEmployee['USR_STAFF_NUMBER'];
                $_SESSION['strUName']=trim($objEmployee['USR_NAME']) . " " . trim($objEmployee['USR_LAST_NAME']);
                $_SESSION['intCountry']=$objEmployee['CNT_ID'];
                $_SESSION['strCountry']=$objEmployee['CNT_NAME'];
                $_SESSION['strCountryCode']=$objEmployee['CNT_CODE'];
                $_SESSION['intPlanta']=$objEmployee['USR_PLANT'];
                $_SESSION['strPlanta']='';//$objEmployee['USR_PLANT'];
                $_SESSION['intDivision']=$objEmployee['USR_DIVISION'];
                $_SESSION['strDivision']='';//$objEmployee['strDivision'];
            };
            unset($objEmployee);
        }else{
            $jsnEmpleado['blnGo'] = 'false';
            $jsnEmpleado['strError'] = "El numero de personal <b>" . $strUser . "</b> no se encuentra registrado en la Base de Datos";
        }
        unset($rstEmployee);
        break;
};
unset($objScrap);
echo json_encode($jsnEmpleado);
?>