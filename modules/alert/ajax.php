<?php
require_once('../../include/config.php');
require_once(LIB_PATH .  'scrap.php');
$objScrap = new clsScrap();
$strProcess = $_REQUEST['strProcess'];

$blnFromAjax= true;
if($_SERVER['HTTP_X_REQUESTED_WITH']==''){
    $blnFromAjax=false;
}

switch ($strProcess) {
    case 'Country':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_CNT_ID'] = '';
        $_SESSION['ALERT_PLN_ID'] = '';
        $_SESSION['ALERT_SHP_ID'] = '';
        $_SESSION['ALERT_DVS_ID'] = '';
        $_SESSION['ALERT_SGM_ID'] = '';
        $_SESSION['ALERT_PRF_ID'] = '';
        $_SESSION['ALERT_APD_ID'] = '';
        $_SESSION['ALERT_ARE_ID'] = '';
        $_SESSION['ALERT_STT_ID'] = '';
        $_SESSION['ALERT_LIN_ID'] = '';
        $_SESSION['ALERT_FLT_ID'] = '';
        $_SESSION['ALERT_CAS_ID'] = '';
        $_SESSION['ALERT_SCD_ID'] = '';
        $_SESSION['ALERT_PRJ_ID'] = '';
        $strSql = "SELECT CNT_ID AS FIELD_ID, CNT_NAME AS FIELD_NAME FROM CNT_COUNTRY WHERE CNT_STATUS = 1 ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todos --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Plant':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_CNT_ID'] = $_REQUEST['intCountry'];
        $strSql = "SELECT PLN_ID AS FIELD_ID, PLN_NAME AS FIELD_NAME FROM PLN_PLANT WHERE PLN_STATUS = 1 AND PLN_ID IN (SELECT PLN_PLANT FROM PLN_PLANT_RELATION WHERE PLN_STATUS = 1 AND PLN_COUNTRY = " . $_SESSION['ALERT_CNT_ID'] .") ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todas --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Ship':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_PLN_ID'] = $_REQUEST['intPlant'];
        $strSql = "SELECT SHP_ID AS FIELD_ID, SHP_NAME AS FIELD_NAME FROM SHP_SHIP WHERE SHP_STATUS = 1 AND SHP_ID IN (SELECT SHP_SHIP FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_PLANT IN (SELECT PLN_ID FROM PLN_PLANT_RELATION WHERE PLN_STATUS = 1 AND PLN_PLANT = " . $_SESSION['ALERT_PLN_ID'] . " AND PLN_COUNTRY = " . $_SESSION['ALERT_CNT_ID'] . ")) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todas --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Division':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_SHP_ID'] = $_REQUEST['intShip'];
        $strSql = "SELECT DVS_ID AS FIELD_ID, DVS_NAME AS FIELD_NAME FROM DVS_DIVISION WHERE DVS_STATUS = 1 AND DVS_ID IN (SELECT DVS_DIVISION FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_SHIP IN (SELECT SHP_ID FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_SHIP = " . $_SESSION['ALERT_SHP_ID'] . " AND SHP_PLANT IN (SELECT PLN_ID FROM PLN_PLANT_RELATION WHERE PLN_STATUS = 1 AND PLN_PLANT = " . $_SESSION['ALERT_PLN_ID'] . " AND PLN_COUNTRY = " . $_SESSION['ALERT_CNT_ID'] . "))) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todas --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Segment':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_DVS_ID'] = $_REQUEST['intDivision'];
        $strSql = "SELECT SGM_ID AS FIELD_ID, SGM_NAME AS FIELD_NAME FROM SGM_SEGMENT WHERE SGM_STATUS = 1 AND SGM_ID IN (SELECT SGM_SEGMENT FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['ALERT_DVS_ID'] . " AND DVS_SHIP IN (SELECT SHP_ID FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_SHIP = " . $_SESSION['ALERT_SHP_ID'] . " AND SHP_PLANT IN (SELECT PLN_ID FROM PLN_PLANT_RELATION WHERE PLN_STATUS = 1 AND PLN_PLANT = " . $_SESSION['ALERT_PLN_ID'] . " AND PLN_COUNTRY = " . $_SESSION['ALERT_CNT_ID'] . ")))) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todos --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'ProfitCenter':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_SGM_ID'] = $_REQUEST['intSegment'];
        $strSql = "SELECT PRF_ID AS FIELD_ID, PRF_NAME AS FIELD_NAME FROM PRF_PROFITCENTER WHERE PRF_STATUS = 1 AND PRF_ID IN (SELECT PRF_PROFITCENTER FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_SEGMENT = " . $_SESSION['ALERT_SGM_ID'] . " AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['ALERT_DVS_ID'] . " AND DVS_SHIP IN (SELECT SHP_ID FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_SHIP = " . $_SESSION['ALERT_SHP_ID'] . " AND SHP_PLANT IN (SELECT PLN_ID FROM PLN_PLANT_RELATION WHERE PLN_STATUS = 1 AND PLN_PLANT = " . $_SESSION['ALERT_PLN_ID'] . " AND PLN_COUNTRY = " . $_SESSION['ALERT_CNT_ID'] . "))))) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todos --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'APD':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_PRF_ID'] = $_REQUEST['intProfitCenter'];
        $strSql = "SELECT APD_ID AS FIELD_ID, APD_NAME AS FIELD_NAME FROM APD_APD WHERE APD_STATUS = 1 AND APD_ID IN (SELECT APD_APD FROM APD_APD_RELATION WHERE APD_STATUS = 1 AND APD_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_SEGMENT = " . $_SESSION['ALERT_SGM_ID'] . " AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['ALERT_DVS_ID'] . " AND DVS_SHIP IN (SELECT SHP_ID FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_SHIP = " . $_SESSION['ALERT_SHP_ID'] . " AND SHP_PLANT IN (SELECT PLN_ID FROM PLN_PLANT_RELATION WHERE PLN_STATUS = 1 AND PLN_PLANT = " . $_SESSION['ALERT_PLN_ID'] . " AND PLN_COUNTRY = " . $_SESSION['ALERT_CNT_ID'] . "))))) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todas --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Area':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_APD_ID'] = $_REQUEST['intAPD'];
        $strSql = "SELECT ARE_ID AS FIELD_ID, ARE_NAME AS FIELD_NAME FROM ARE_AREA WHERE ARE_STATUS = 1 AND ARE_ID IN (SELECT STT_AREA FROM STT_STATION_RELATION WHERE STT_STATUS = 1 AND STT_ID IN (SELECT PRJ_STATION FROM PRJ_PROJECT_STATION WHERE PRJ_STATUS = 1 AND PRJ_PROJECT IN (SELECT PRJ_PROJECT FROM PRJ_PROJECT_PROFITCENTER WHERE PRJ_STATUS = 1 AND PRJ_PROFITCENTER IN (SELECT PRF_ID FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['ALERT_DVS_ID'] . ")))))) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todas --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Station':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_ARE_ID'] = $_REQUEST['intArea'];
        $strSql = "SELECT STT_ID AS FIELD_ID, STT_NAME AS FIELD_NAME FROM STT_STATION WHERE STT_STATUS = 1 AND STT_ID IN (SELECT STT_STATION FROM STT_STATION_RELATION WHERE STT_STATUS = 1 AND STT_AREA = " . $_SESSION['ALERT_ARE_ID'] . " AND STT_ID IN (SELECT PRJ_STATION FROM PRJ_PROJECT_STATION WHERE PRJ_STATUS = 1 AND PRJ_PROJECT IN (SELECT PRJ_PROJECT FROM PRJ_PROJECT_PROFITCENTER WHERE PRJ_STATUS = 1 AND PRJ_PROFITCENTER IN (SELECT PRF_ID FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['ALERT_DVS_ID'] . ")))))) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todas --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Line':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_STT_ID'] = $_REQUEST['intStation'];
        $strSql = "SELECT LIN_ID AS FIELD_ID, LIN_NAME AS FIELD_NAME FROM LIN_LINE WHERE LIN_STATUS = 1 AND LIN_ID IN (SELECT DISTINCT(LIN_LINE) FROM LIN_LINE_RELATION WHERE LIN_STATUS = 1 AND LIN_STATION IN (SELECT STT_ID FROM STT_STATION_RELATION WHERE STT_STATION = " . $_SESSION['ALERT_STT_ID'] . " AND STT_AREA = " . $_SESSION['ALERT_ARE_ID'] . ")) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todas --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Fault':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_LIN_ID'] = $_REQUEST['intLine'];
        $strSql = "SELECT FLT_ID AS FIELD_ID, FLT_NAME AS FIELD_NAME FROM FLT_FAULT WHERE FLT_STATUS = 1 AND FLT_ID IN (SELECT DISTINCT(FLT_FAULT) FROM FLT_FAULT_RELATION WHERE FLT_STATUS = 1 AND FLT_STATION IN (SELECT STT_ID FROM STT_STATION_RELATION WHERE STT_STATION = " . $_SESSION['ALERT_STT_ID'] . " AND STT_AREA = " . $_SESSION['ALERT_ARE_ID'] . ")) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todos --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Cause':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_FLT_ID'] = $_REQUEST['intFault'];
        $strSql = "SELECT CAS_ID AS FIELD_ID, CAS_NAME AS FIELD_NAME FROM CAS_CAUSE WHERE CAS_STATUS = 1 AND CAS_ID IN (SELECT DISTINCT(CAS_CAUSE) FROM CAS_CAUSE_RELATION WHERE CAS_STATUS = 1 AND CAS_FAULT IN (SELECT FLT_ID FROM FLT_FAULT_RELATION WHERE FLT_STATUS = 1 AND FLT_FAULT = " . $_SESSION['ALERT_FLT_ID'] . " AND FLT_STATION IN (SELECT STT_ID FROM STT_STATION_RELATION WHERE STT_STATION = " . $_SESSION['ALERT_STT_ID'] . " AND STT_AREA = " . $_SESSION['ALERT_ARE_ID'] . "))) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todas --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'ScrapCode':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_CAS_ID'] = $_REQUEST['intCause'];
        $strSql = "SELECT SCD_ID AS FIELD_ID, SCD_CODE||' - '||SCD_NAME AS FIELD_NAME FROM SCD_SCRAPCODE WHERE SCD_STATUS = 1 AND SCD_ID IN (SELECT DISTINCT(SCD_SCRAPCODE) FROM SCD_SCRAPCODE_RELATION WHERE SCD_STATUS = 1 AND SCD_CAUSE = " . $_SESSION['ALERT_CAS_ID'] .") ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todos --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Project':
        $jsnPhpScriptResponse = array('strResponse' => '<option selected="selected" value="-1">-- no se encontraron registros --</option>', 'strError' => '', 'intRecordCount' => 0);
        $_SESSION['ALERT_SCD_ID'] = $_REQUEST['intCause'];
        $strSql = "SELECT PRJ_ID AS FIELD_ID, PRJ_NAME AS FIELD_NAME FROM PRJ_PROJECT WHERE PRJ_STATUS = 1 AND PRJ_ID IN (SELECT PRJ_ID FROM PRJ_PROJECT WHERE PRJ_STATUS = 1 AND PRJ_ID IN (SELECT PRJ_PROJECT FROM PRJ_PROJECT_PROFITCENTER WHERE PRJ_STATUS = 1 AND PRJ_PROFITCENTER IN (SELECT PRF_ID FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['ALERT_DVS_ID'] . "))))) AND PRJ_ID IN (SELECT PRJ_PROJECT FROM PRJ_PROJECT_STATION WHERE PRJ_STATION = (SELECT STT_ID FROM STT_STATION_RELATION WHERE STT_STATION = " . $_SESSION['ALERT_STT_ID'] . " AND STT_AREA = " . $_SESSION['ALERT_ARE_ID'] . " AND STT_STATUS = 1) AND PRJ_STATUS = 1) ORDER BY FIELD_NAME, FIELD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if ($objScrap->intAffectedRows > 0) {
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if ($objScrap->intAffectedRows > 1) {
                $jsnPhpScriptResponse['strResponse'] = '<option selected="selected" value="-1">-- todos --</option>';
            } else {
                $jsnPhpScriptResponse['strResponse'] = '';
            }
            foreach ($rstResponse as $objResponse) {
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FIELD_ID'] . '">' . $objResponse['FIELD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'CreateAlert':
        $jsnPhpScriptResponse = '';
        $arrSelects = array('Country', 'Plant', 'Ship', 'Division', 'Segment', 'ProfitCenter', 'APD', 'Area', 'Station', 'Line', 'Fault', 'Cause', 'ScrapCode', 'Project');
        $strSql = "INSERT INTO ALR_ALERT(";
        $strSqlFields = "ALR_USER, ALR_NAME, ALR_DATE, ALR_COST";
        $strSqlValues = "'" . $_SESSION['USR_PERSONALNUMBER'] . "','" . $_REQUEST['txtAlert'] . "'," . date('Ymd') . "," . $_REQUEST['intCost'];
        foreach($arrSelects as $strSelect){
            if($_REQUEST['int' . $strSelect]!='null' && $_REQUEST['int' . $strSelect]!=-1){
                $strSqlFields .= ",ALR_" . strtoupper($strSelect);
                $strSqlValues .= "," . $_REQUEST['int' . $strSelect];
            }
        }
        $strSql = $strSql . $strSqlFields . ") VALUES (" . $strSqlValues . ")";
        $objScrap->dbInsert($strSql);
        break;
    case 'getAlertList':
        $jsnPhpScriptResponse = array('strResponse' => '', 'strError' => '', 'intFirstAlert' => 0);
        $strSql = "SELECT ALR_ALERT.ALR_ID, ALR_ALERT.ALR_NAME, (SELECT COUNT(*) FROM ALR_ALERT_USER WHERE ALR_ALERT_USER.ALR_ALERT = ALR_ALERT.ALR_ID AND ALR_ALERT_USER.ALR_READ = 0) AS COUNT FROM ALR_ALERT WHERE ALR_ALERT.ALR_USER = '" . $_SESSION['USR_PERSONALNUMBER'] . "' ORDER BY ALR_ALERT.ALR_ID DESC";
        $rstAlerts = $objScrap->dbQuery($strSql);
        foreach($rstAlerts as $objAlerts){
            if($_REQUEST['intAlert']==-1){
                $jsnPhpScriptResponse['intFirstAlert'] = $rstAlerts[0]['ALR_ID'];
            }else{
                $jsnPhpScriptResponse['intFirstAlert'] = $_REQUEST['intAlert'];
            }
            $jsnPhpScriptResponse['strResponse'] .= '<label class="label_alert" id="lblAlert_'.  $objAlerts['ALR_ID'] . '" onclick="getAlerts(' . $objAlerts['ALR_ID'] . ')">&#8226; ' . $objAlerts['ALR_NAME'] . ' (' . $objAlerts['COUNT'] . ')</label><br />';
        }
        break;
    case 'getAlerts':
        $jsnPhpScriptResponse = array('strResponse' => '', 'strError' => '', 'intRecordCount' => 0);
        $strSql = "SELECT SCR_SCRAP.SCR_ID, SCR_SCRAP_STATUS.SCR_DATE, SCR_SCRAP.SCR_COST, SCR_SCRAP.SCR_PROJECT, SCR_SCRAP.SCR_COUNTRY, SCR_SCRAP.SCR_PLANT, SCR_SCRAP.SCR_SHIP, SCR_SCRAP.SCR_DIVISION, SCR_SCRAP.SCR_DIVISION, SCR_SCRAP.SCR_SEGMENT, SCR_SCRAP.SCR_PROFITCENTER, SCR_SCRAP.SCR_APD, SCR_SCRAP.SCR_AREA ";
        $strSql .= "FROM SCR_SCRAP ";
        $strSql .= "LEFT JOIN SCR_SCRAP_STATUS ON SCR_SCRAP_STATUS.SCR_SCRAP = SCR_SCRAP.SCR_ID AND SCR_SCRAP_STATUS.SCR_STATUS = 0 ";
        $strSql .= "WHERE SCR_SCRAP.SCR_ID IN (SELECT ALR_ALERT_USER.ALR_SCRAP FROM ALR_ALERT_USER WHERE ALR_ALERT_USER.ALR_ALERT = " . $_REQUEST['intAlert'] . " AND ALR_ALERT_USER.ALR_READ = 0) ";
        $strSql .= "ORDER BY SCR_SCRAP_STATUS.SCR_DATE DESC";
        $rstAlerts = $objScrap->dbQuery($strSql);
        foreach($rstAlerts as $objAlerts){
            $jsnPhpScriptResponse['strResponse'] .= '<tr style="cursor: pointer;" id="trAlert_' . $objAlerts['SCR_ID'] . '" onclick=viewAlert(' . $objAlerts['SCR_ID'] . ');>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:50px; text-align: right">' . $objAlerts['SCR_ID'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:66px; text-align: center">' . substr($objAlerts['SCR_DATE'],6,2) . '-' . substr($objAlerts['SCR_DATE'],4,2) . '-' . substr($objAlerts['SCR_DATE'],0,4) . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:50px; text-align: right">' . number_format($objAlerts['SCR_COST'],2,'.',',') . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:150px; display: block">' . $objAlerts['SCR_PROJECT'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:49px">' . $objAlerts['SCR_COUNTRY'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:80px">' . $objAlerts['SCR_PLANT'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:33px">' . $objAlerts['SCR_SHIP'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:49px">' . $objAlerts['SCR_DIVISION'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:61px">' . $objAlerts['SCR_SEGMENT'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:76px">' . $objAlerts['SCR_PROFITCENTER'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:30px">' . $objAlerts['SCR_APD'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdAlert" style="width:120px; display: block">' . $objAlerts['SCR_AREA'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '</tr>';
        }
        unset($rstAlerts);
        break;
    case 'updateAlert':
        $jsnPhpScriptResponse = "";
        $strSql = "UPDATE ALR_ALERT_USER SET ALR_READ = 1 WHERE ALR_ALERT IN (SELECT ALR_ID FROM ALR_ALERT WHERE ALR_USER = '" . $_SESSION['USR_PERSONALNUMBER'] . "') AND ALR_READ = 0 AND ALR_SCRAP = " . $_REQUEST['intScrap'];
        $objScrap->dbUpdate($strSql);
        break;
    case 'viewAlert':
        $jsnPhpScriptResponse = array('strResponse' => '', 'strError' => '', 'intRecordCount' => 0);
        $strSql = "SELECT * FROM SCR_SCRAP WHERE SCR_ID = " . $_REQUEST['intScrap'];
        $rstScrap = $objScrap->dbQuery($strSql);
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Costo</label><label class="alert_display_label">' . number_format($rstScrap[0]['SCR_COST'],2,'.',',') . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Pa&iacute;s</label><label class="alert_display_label">' . $rstScrap[0]['SCR_COUNTRY'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Planta</label><label class="alert_display_label">' . $rstScrap[0]['SCR_PLANT'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Nave</label><label class="alert_display_label">' . $rstScrap[0]['SCR_SHIP'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Divisi&oacute;n</label><label class="alert_display_label">' . $rstScrap[0]['SCR_DIVISION'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Segmento</label><label class="alert_display_label">' . $rstScrap[0]['SCR_SEGMENT'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Profit Center</label><label class="alert_display_label">' . $rstScrap[0]['SCR_PROFITCENTER'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">APD</label><label class="alert_display_label">' . $rstScrap[0]['SCR_APD'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Centro de Costos</label><label class="alert_display_label">' . $rstScrap[0]['SCR_COSTCENTER'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">&Aacute;rea</label><label class="alert_display_label">' . $rstScrap[0]['SCR_AREA'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Tecnolog&iacute;a</label><label class="alert_display_label">' . $rstScrap[0]['SCR_STATION'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">L&iacute;nea</label><label class="alert_display_label">' . $rstScrap[0]['SCR_LINE'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Defecto</label><label class="alert_display_label">' . $rstScrap[0]['SCR_FAULT'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Causa</label><label class="alert_display_label">' . $rstScrap[0]['SCR_CAUSE'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">C&oacute;digo de Scrap</label><label class="alert_display_label">' . $rstScrap[0]['SCR_SCRAPCODE'] . '</label><br />';
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Proyecto</label><label class="alert_display_label">' . $rstScrap[0]['SCR_PROJECT'] . '</label><br />';
        unset($rstScrap);
        $strSql = "SELECT * FROM SCR_SCRAP_STATUS WHERE SCR_SCRAP = " . $_REQUEST['intScrap'] . " ORDER BY SCR_ID";
        $rstScrap = $objScrap->dbQuery($strSql);
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Estatus</label><br /><div style="padding-left: 50px; padding-top: 10px">';
        foreach($rstScrap as $objRecords){
            switch($objRecords['SCR_STATUS']){
                case 0:
                    $jsnPhpScriptResponse['strResponse'] .= 'Capturado: ';
                    break;
                case 1:
                    $jsnPhpScriptResponse['strResponse'] .= 'Autorizado: ';
                    break;
                case 2:
                    $jsnPhpScriptResponse['strResponse'] .= 'Cancelado: ';
                    break;
                case 3:
                    $jsnPhpScriptResponse['strResponse'] .= 'Rechazado: ';
                    break;
                case 4:
                    $jsnPhpScriptResponse['strResponse'] .= 'Enviado a SAP: ';
                    break;
                case 5:
                    $jsnPhpScriptResponse['strResponse'] .= 'Procesado en SAP: ';
                    break;
            }
            $jsnPhpScriptResponse['strResponse'] .= $objRecords['SCR_USER'] . ', ';
            $jsnPhpScriptResponse['strResponse'] .= substr($objRecords['SCR_DATE'],6,2) . '-' . substr($objRecords['SCR_DATE'],4,2) . '-' . substr($objRecords['SCR_DATE'],0,4) . ', ';
            switch(strlen($objRecords['SCR_TIME'])){
                case 4:
                    $jsnPhpScriptResponse['strResponse'] .= '00' . ':' . substr($objRecords['SCR_TIME'],0,2) . ':' . substr($objRecords['SCR_TIME'],2,2);
                    break;
                case 5:
                    $jsnPhpScriptResponse['strResponse'] .= '0' . substr($objRecords['SCR_TIME'],0,1) . ':' . substr($objRecords['SCR_TIME'],1,2) . ':' . substr($objRecords['SCR_TIME'],3,2);
                    break;
                case 6:
                    $jsnPhpScriptResponse['strResponse'] .= substr($objRecords['SCR_TIME'],0,2) . ':' . substr($objRecords['SCR_TIME'],2,2) . ':' . substr($objRecords['SCR_TIME'],4,2);
                    break;
            }
            $jsnPhpScriptResponse['strResponse'] .= '<br />';
        }
        $jsnPhpScriptResponse['strResponse'] .= '</div>';
        unset($rstScrap);
        $strSql = "SELECT PRT_NUMBER, SCR_DESCRIPTION, SCR_QUANTITY, SCR_LOCATION, SCR_TYPE, SCR_UNIT, SCR_AMOUNT FROM SCR_SCRAP_PART LEFT JOIN PRT_PART ON PRT_ID = SCR_PART WHERE SCR_SCRAP = " . $_REQUEST['intScrap'] . " ORDER BY SCR_ID";
        $rstScrap = $objScrap->dbQuery($strSql);
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Partes</label><br /><div style="padding-left: 50px; padding-top: 10px"><table>';
        $jsnPhpScriptResponse['strResponse'] .= '<tr>';
        $jsnPhpScriptResponse['strResponse'] .= '<td class="thAlert">Parte</td>';
        $jsnPhpScriptResponse['strResponse'] .= '<td class="thAlert">Descripci&oacute;n</td>';
        $jsnPhpScriptResponse['strResponse'] .= '<td class="thAlert">Cantidad</td>';
        $jsnPhpScriptResponse['strResponse'] .= '<td class="thAlert">Ubicaci&oacute;n</td>';
        $jsnPhpScriptResponse['strResponse'] .= '<td class="thAlert">Tipo</td>';
        $jsnPhpScriptResponse['strResponse'] .= '<td class="thAlert">Unidad</td>';
        $jsnPhpScriptResponse['strResponse'] .= '<td class="thAlert">Importe</td>';
        $jsnPhpScriptResponse['strResponse'] .= '</tr>';
        foreach($rstScrap as $objRecords){
            $jsnPhpScriptResponse['strResponse'] .= '<tr>';
            $jsnPhpScriptResponse['strResponse'] .= '<td>' . $objRecords['PRT_NUMBER'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td>' . $objRecords['SCR_DESCRIPTION'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td>' . $objRecords['SCR_QUANTITY'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td>' . $objRecords['SCR_LOCATION'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td>' . $objRecords['SCR_TYPE'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td>' . $objRecords['SCR_UNIT'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td>' . $objRecords['SCR_AMOUNT'] . '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '</tr>';
        }
        $jsnPhpScriptResponse['strResponse'] .= '</table></div>';
        unset($rstScrap);
        $strSql = "SELECT * FROM SCR_SCRAP_COMMENT WHERE SCR_SCRAP = " . $_REQUEST['intScrap'];
        $rstScrap = $objScrap->dbQuery($strSql);
        $jsnPhpScriptResponse['strResponse'] .= '<label class="capture_label">Comentarios</label><br /><div style="padding-left: 50px; padding-top: 10px">';
        $jsnPhpScriptResponse['strResponse'] .= $rstScrap[0]['SCR_COMMENT'];
        $jsnPhpScriptResponse['strResponse'] .= '</div>';
        unset($rstScrap);
        break;
}
unset($objScrap);
echo json_encode($jsnPhpScriptResponse);
?>