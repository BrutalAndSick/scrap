<?php
require_once('include/config.php');
require_once(LIB_PATH .  'scrap.php');
$objScrap = new clsScrap();
$strProcess = $_REQUEST['strProcess'];
switch($strProcess){
    case 'getWSUser':
        $jsnPhpScriptResponse = array('blnValidUser'=>false,'strError'=>'');
        $strPersonalNumber = $_REQUEST['strPersonalNumber'];
        $arrWSUserData = $objScrap->getWSUserData($strPersonalNumber,false);
        if($arrWSUserData['strCostCenter']==''){
            $jsnPhpScriptResponse['strError'] = 'El numero de personal <b>' . $strPersonalNumber . '</b> no se encuentra registrado en la BD de RH';
        }else{
            $jsnPhpScriptResponse['blnValidUser'] = true;
            fillSessionData($arrWSUserData);
        }
        break;
    case 'getADUser':
        $jsnPhpScriptResponse = array('blnValidUser'=>false,'strError'=>'');
        $strWindowsUserId = $_REQUEST['strWindowsUserId'];
        $strWindowsPassword = $_REQUEST['strWindowsPassword'];
        $arrADUserData = $objScrap->getADUserData($strWindowsUserId,$strWindowsPassword);
        if($arrADUserData['blnValid']){
            $arrWSUserData = $objScrap->getWSUserData($strWindowsUserId,true);
            if($arrWSUserData['strCostCenter']==''){
                $jsnPhpScriptResponse['strError'] = 'El UID <b>' . $strWindowsUserId . '</b> no se encuentra registrado en la BD de RH';
            }else{
                $strSql = "SELECT COUNT(*) AS COUNT FROM USR_USER WHERE USR_WINDOWSUSER = '" . strtoupper($strWindowsUserId) . "' AND USR_STATUS = 1";
                $rstValidUser = $objScrap->dbQuery($strSql);
                if($rstValidUser[0]['COUNT']==0){
                    $jsnPhpScriptResponse['strError'] = 'El UID <b>' . $strWindowsUserId . '</b> no se encuentra registrado en la BD del sistema SCRAP';
                }else{
                    $jsnPhpScriptResponse['blnValidUser'] = true;
                    fillSessionData($arrWSUserData);
                }
                unset($rstValidUser);
            }
        }else{
            $jsnPhpScriptResponse['strError'] = $arrADUserData['strError'];
        }
        break;
}
echo json_encode($jsnPhpScriptResponse);
unset($objScrap);

function fillSessionData($arrWSUserData){
    global $objScrap;
    $strSql = "SELECT CST_COSTCENTER.CST_ID, CST_COSTCENTER.CST_NAME, CST_COSTCENTER_RELATION.CST_DIVISION FROM CST_COSTCENTER, CST_COSTCENTER_RELATION WHERE CST_COSTCENTER.CST_NAME = '" . $arrWSUserData['strCostCenter'] . "' AND CST_COSTCENTER.CST_ID = CST_COSTCENTER_RELATION.CST_COSTCENTER AND CST_COSTCENTER.CST_STATUS = 1 AND CST_COSTCENTER_RELATION.CST_STATUS = 1";
    $rstData = $objScrap->dbQuery($strSql);
    if($objScrap->intAffectedRows!=0){
        $_SESSION['CST_COSTCENTER_ID'] = $rstData[0]['CST_ID'];
        $_SESSION['CST_COSTCENTER_NAME'] = $rstData[0]['CST_NAME'];
        $_SESSION['DVS_DIVISION_ID'] = $rstData[0]['CST_DIVISION'];
        unset($rstData);
        $strSql = "SELECT DVS_DIVISION.DVS_ID, DVS_DIVISION.DVS_NAME, DVS_DIVISION_RELATION.DVS_SHIP FROM DVS_DIVISION, DVS_DIVISION_RELATION WHERE DVS_DIVISION_RELATION.DVS_ID = " . $_SESSION['DVS_DIVISION_ID'] . " AND DVS_DIVISION_RELATION.DVS_DIVISION = DVS_DIVISION.DVS_ID AND DVS_DIVISION_RELATION.DVS_STATUS = 1 AND DVS_DIVISION.DVS_STATUS = 1";
        $rstData = $objScrap->dbQuery($strSql);
        $_SESSION['DVS_DIVISION_ID'] = $rstData[0]['DVS_ID'];
        $_SESSION['DVS_DIVISION_NAME'] = $rstData[0]['DVS_NAME'];
        unset($rstData);
    }else{
        $_SESSION['CST_COSTCENTER_ID'] = 0;
        $_SESSION['CST_COSTCENTER_NAME'] = '--ALL--';
        $_SESSION['DVS_DIVISION_ID'] = 0;
        $_SESSION['DVS_DIVISION_NAME'] = '--ALL--';
        unset($rstData);
    }
    $_SESSION['USR_PERSONALNUMBER'] = $arrWSUserData['strPersonalNumber'];
    $_SESSION['USR_FULLNAME'] = $arrWSUserData['strFullName'];
    $_SESSION['USR_WINDOWSUID'] = $arrWSUserData['strWindowsUserId'];
    $_SESSION['SHP_SHIP_ID'] = '';
    $_SESSION['SHP_SHIP_NAME'] = '';
    $_SESSION['PLN_PLANT_ID'] = '';
    $_SESSION['PLN_PLANT_NAME'] = '';
    $_SESSION['CNT_COUNTRY_ID'] = '';
    $_SESSION['CNT_COUNTRY_NAME'] = '';
    $_SESSION['ARE_AREA_ID'] = '';
    $_SESSION['ARE_AREA_NAME'] = '';
    $_SESSION['STT_STATION_ID'] = '';
    $_SESSION['STT_STATION_NAME'] = '';
    $_SESSION['LIN_LINE_ID'] = '';
    $_SESSION['LIN_LINE_NAME'] = '';
    $_SESSION['FLT_FAULT_ID'] = '';
    $_SESSION['FLT_FAULT_NAME'] = '';
    $_SESSION['CAS_CAUSE_ID'] = '';
    $_SESSION['CAS_CAUSE_NAME'] = '';
    $_SESSION['SCD_SCRAPCODE_ID'] = '';
    $_SESSION['SCD_SCRAPCODE_NAME'] = '';
    $_SESSION['PRJ_PROJECT_ID'] = '';
    $_SESSION['PRJ_PROJECT_NAME'] = '';
    $_SESSION['PRF_PROFITCENTER_ID'] = '';
    $_SESSION['PRF_PROFITCENTER_NAME'] = '';
    $_SESSION['SGM_SEGMENT_ID'] = '';
    $_SESSION['SGM_SEGMENT_NAME'] = '';
    $_SESSION['APD_APD_ID'] = '';
    $_SESSION['APD_APD_NAME'] = '';
}