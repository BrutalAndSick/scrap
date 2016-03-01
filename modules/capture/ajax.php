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
    case 'Ship':
        $jsnPhpScriptResponse = array('strResponse'=>'<option selected="selected" value="-1">-- no se encontraron registros --</option>','strError'=>'','intRecordCount'=>0);
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
        $_SESSION['PART_ID'] = '';
        $_SESSION['PART_QUANTITY'] = '';
        $_SESSION['PART_LOCATION'] = '';
        $_SESSION['PART_SERIAL'] = '';
        $_SESSION['PART_DESCRIPTION'] = '';
        $_SESSION['PART_COST'] = '';
        $_SESSION['PART_TYPE_ID'] = '';
        $_SESSION['PART_TYPE_NAME'] = '';
        $_SESSION['PART_UNIT_ID'] = '';
        $_SESSION['PART_UNIT_NAME'] = '';
        $_SESSION['COMMENTS'] = '';
        $_SESSION['WHY1'] = '';
        $_SESSION['WHY2'] = '';
        $_SESSION['WHY3'] = '';
        $_SESSION['WHY4'] = '';
        $_SESSION['WHY5'] = '';
        $_SESSION['ACTION1'] = '';
        $_SESSION['ACTION2'] = '';
        $_SESSION['ACTION3'] = '';
        $_SESSION['ACTION4'] = '';
        $_SESSION['ACTION5'] = '';
        $strSql = "SELECT SHP_ID, SHP_NAME FROM SHP_SHIP WHERE SHP_STATUS = 1 AND SHP_ID IN (SELECT SHP_SHIP FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_ID IN (SELECT DVS_SHIP FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['DVS_DIVISION_ID'] . ")) ORDER BY SHP_NAME, SHP_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if($objScrap->intAffectedRows>0){
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if($objScrap->intAffectedRows>1){
                $jsnPhpScriptResponse['strResponse']='<option selected="selected" value="-1">-- selecciona --</option>';
            }else{
                $jsnPhpScriptResponse['strResponse']='';
            }
            foreach($rstResponse as $objResponse){
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['SHP_ID'] . '">' . $objResponse['SHP_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Area':
        $jsnPhpScriptResponse = array('strResponse'=>'<option selected="selected" value="-1">-- no se encontraron registros --</option>','strError'=>'','intRecordCount'=>0);
        $_SESSION['SHP_SHIP_ID'] = $_REQUEST['intShip'];
        $_SESSION['SHP_SHIP_NAME'] = $_REQUEST['strShip'];
        $strSql = "SELECT PLN_ID, PLN_NAME FROM PLN_PLANT WHERE PLN_STATUS = 1 AND PLN_ID IN (SELECT PLN_PLANT FROM PLN_PLANT_RELATION WHERE PLN_STATUS = 1 AND PLN_ID IN (SELECT SHP_PLANT FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_SHIP = " . $_SESSION['SHP_SHIP_ID'] . ")) ORDER BY PLN_NAME, PLN_ID";
        $rstData = $objScrap->dbQuery($strSql);
        $_SESSION['PLN_PLANT_ID'] = $rstData[0]['PLN_ID'];
        $_SESSION['PLN_PLANT_NAME'] = $rstData[0]['PLN_NAME'];
        unset($rstData);
        $strSql = "SELECT PLN_PLANT_RELATION.PLN_COUNTRY, PLN_PLANT.PLN_ID, PLN_PLANT.PLN_NAME FROM PLN_PLANT_RELATION, PLN_PLANT WHERE PLN_PLANT_RELATION.PLN_ID = " . $_SESSION['PLN_PLANT_ID'] . " AND PLN_PLANT_RELATION.PLN_PLANT = PLN_PLANT.PLN_ID AND PLN_PLANT_RELATION.PLN_STATUS = 1 AND PLN_PLANT.PLN_STATUS = 1";
        $rstData = $objScrap->dbQuery($strSql);
        $_SESSION['CNT_COUNTRY_ID'] = $rstData[0]['PLN_COUNTRY'];
        unset($rstData);
        $strSql = "SELECT CNT_COUNTRY.CNT_ID, CNT_COUNTRY.CNT_NAME FROM CNT_COUNTRY WHERE CNT_COUNTRY.CNT_STATUS = 1 AND CNT_COUNTRY.CNT_ID = (SELECT PLN_COUNTRY FROM PLN_PLANT_RELATION WHERE PLN_STATUS = 1 AND PLN_PLANT = " . $_SESSION['PLN_PLANT_ID'] . ")";
        $rstData = $objScrap->dbQuery($strSql);
        $_SESSION['CNT_COUNTRY_ID'] = $rstData[0]['CNT_ID'];
        $_SESSION['CNT_COUNTRY_NAME'] = $rstData[0]['CNT_NAME'];
        unset($rstData);
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
        $_SESSION['PART_ID'] = '';
        $_SESSION['PART_QUANTITY'] = '';
        $_SESSION['PART_LOCATION'] = '';
        $_SESSION['PART_SERIAL'] = '';
        $strSql = "SELECT ARE_ID, ARE_NAME FROM ARE_AREA WHERE ARE_STATUS = 1 AND ARE_ID IN (SELECT STT_AREA FROM STT_STATION_RELATION WHERE STT_STATUS = 1 AND STT_ID IN (SELECT PRJ_STATION FROM PRJ_PROJECT_STATION WHERE PRJ_STATUS = 1 AND PRJ_PROJECT IN (SELECT PRJ_PROJECT FROM PRJ_PROJECT_PROFITCENTER WHERE PRJ_STATUS = 1 AND PRJ_PROFITCENTER IN (SELECT PRF_ID FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['DVS_DIVISION_ID'] . ")))))) ORDER BY ARE_NAME, ARE_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if($objScrap->intAffectedRows>0){
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if($objScrap->intAffectedRows>1){
                $jsnPhpScriptResponse['strResponse']='<option selected="selected" value="-1">-- selecciona --</option>';
            }else{
                $jsnPhpScriptResponse['strResponse']='';
            }
            foreach($rstResponse as $objResponse){
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['ARE_ID'] . '">' . $objResponse['ARE_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Station':
        $jsnPhpScriptResponse = array('strResponse'=>'<option selected="selected" value="-1">-- no se encontraron registros --</option>','strError'=>'','intRecordCount'=>0);
        $_SESSION['ARE_AREA_ID'] = $_REQUEST['intArea'];
        $_SESSION['ARE_AREA_NAME'] = $_REQUEST['strArea'];
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
        $_SESSION['PART_ID'] = '';
        $_SESSION['PART_QUANTITY'] = '';
        $_SESSION['PART_LOCATION'] = '';
        $_SESSION['PART_SERIAL'] = '';
        $strSql = "SELECT STT_ID, STT_NAME FROM STT_STATION WHERE STT_STATUS = 1 AND STT_ID IN (SELECT STT_STATION FROM STT_STATION_RELATION WHERE STT_STATUS = 1 AND STT_AREA = " . $_SESSION['ARE_AREA_ID'] . " AND STT_ID IN (SELECT PRJ_STATION FROM PRJ_PROJECT_STATION WHERE PRJ_STATUS = 1 AND PRJ_PROJECT IN (SELECT PRJ_PROJECT FROM PRJ_PROJECT_PROFITCENTER WHERE PRJ_STATUS = 1 AND PRJ_PROFITCENTER IN (SELECT PRF_ID FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['DVS_DIVISION_ID'] . ")))))) ORDER BY STT_NAME, STT_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if($objScrap->intAffectedRows>0){
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if($objScrap->intAffectedRows>1){
                $jsnPhpScriptResponse['strResponse']='<option selected="selected" value="-1">-- selecciona --</option>';
            }else{
                $jsnPhpScriptResponse['strResponse']='';
            }
            foreach($rstResponse as $objResponse){
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['STT_ID'] . '">' . $objResponse['STT_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Line':
        $jsnPhpScriptResponse = array('strResponse'=>'<option selected="selected" value="-1">-- no se encontraron registros --</option>','strError'=>'','intRecordCount'=>0);
        $_SESSION['STT_STATION_ID'] = $_REQUEST['intStation'];
        $_SESSION['STT_STATION_NAME'] = $_REQUEST['strStation'];
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
        $_SESSION['PART_ID'] = '';
        $_SESSION['PART_QUANTITY'] = '';
        $_SESSION['PART_LOCATION'] = '';
        $_SESSION['PART_SERIAL'] = '';
        $strSql = "SELECT LIN_ID, LIN_NAME FROM LIN_LINE WHERE LIN_STATUS = 1 AND LIN_ID IN (SELECT DISTINCT(LIN_LINE) FROM LIN_LINE_RELATION WHERE LIN_STATUS = 1 AND LIN_STATION IN (SELECT STT_ID FROM STT_STATION_RELATION WHERE STT_STATION = " . $_SESSION['STT_STATION_ID'] . " AND STT_AREA = " . $_SESSION['ARE_AREA_ID'] . ")) ORDER BY LIN_NAME, LIN_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if($objScrap->intAffectedRows>0){
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if($objScrap->intAffectedRows>1){
                $jsnPhpScriptResponse['strResponse']='<option selected="selected" value="-1">-- selecciona --</option>';
            }else{
                $jsnPhpScriptResponse['strResponse']='';
            }
            foreach($rstResponse as $objResponse){
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['LIN_ID'] . '">' . $objResponse['LIN_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Fault':
        $jsnPhpScriptResponse = array('strResponse'=>'<option selected="selected" value="-1">-- no se encontraron registros --</option>','strError'=>'','intRecordCount'=>0);
        $_SESSION['LIN_LINE_ID'] = $_REQUEST['intLine'];
        $_SESSION['LIN_LINE_NAME'] = $_REQUEST['strLine'];
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
        $_SESSION['PART_ID'] = '';
        $_SESSION['PART_QUANTITY'] = '';
        $_SESSION['PART_LOCATION'] = '';
        $_SESSION['PART_SERIAL'] = '';
        $strSql = "SELECT FLT_ID, FLT_NAME FROM FLT_FAULT WHERE FLT_STATUS = 1 AND FLT_ID IN (SELECT DISTINCT(FLT_FAULT) FROM FLT_FAULT_RELATION WHERE FLT_STATUS = 1 AND FLT_STATION IN (SELECT STT_ID FROM STT_STATION_RELATION WHERE STT_STATION = " . $_SESSION['STT_STATION_ID'] . " AND STT_AREA = " . $_SESSION['ARE_AREA_ID'] . ")) ORDER BY FLT_NAME, FLT_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if($objScrap->intAffectedRows>0){
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if($objScrap->intAffectedRows>1){
                $jsnPhpScriptResponse['strResponse']='<option selected="selected" value="-1">-- selecciona --</option>';
            }else{
                $jsnPhpScriptResponse['strResponse']='';
            }
            foreach($rstResponse as $objResponse){
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['FLT_ID'] . '">' . $objResponse['FLT_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Cause':
        $jsnPhpScriptResponse = array('strResponse'=>'<option selected="selected" value="-1">-- no se encontraron registros --</option>','strError'=>'','intRecordCount'=>0);
        $_SESSION['FLT_FAULT_ID'] = $_REQUEST['intFault'];
        $_SESSION['FLT_FAULT_NAME'] = $_REQUEST['strFault'];
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
        $_SESSION['PART_ID'] = '';
        $_SESSION['PART_QUANTITY'] = '';
        $_SESSION['PART_LOCATION'] = '';
        $_SESSION['PART_SERIAL'] = '';
        $strSql = "SELECT CAS_ID, CAS_NAME FROM CAS_CAUSE WHERE CAS_STATUS = 1 AND CAS_ID IN (SELECT DISTINCT(CAS_CAUSE) FROM CAS_CAUSE_RELATION WHERE CAS_STATUS = 1 AND CAS_FAULT IN (SELECT FLT_ID FROM FLT_FAULT_RELATION WHERE FLT_STATUS = 1 AND FLT_FAULT = " . $_SESSION['FLT_FAULT_ID'] . " AND FLT_STATION IN (SELECT STT_ID FROM STT_STATION_RELATION WHERE STT_STATION = " . $_SESSION['STT_STATION_ID'] . " AND STT_AREA = " . $_SESSION['ARE_AREA_ID'] . "))) ORDER BY CAS_NAME, CAS_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if($objScrap->intAffectedRows>0){
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if($objScrap->intAffectedRows>1){
                $jsnPhpScriptResponse['strResponse']='<option selected="selected" value="-1">-- selecciona --</option>';
            }else{
                $jsnPhpScriptResponse['strResponse']='';
            }
            foreach($rstResponse as $objResponse){
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['CAS_ID'] . '">' . $objResponse['CAS_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'ScrapCode':
        $jsnPhpScriptResponse = array('strResponse'=>'<option selected="selected" value="-1">-- no se encontraron registros --</option>','strError'=>'','intRecordCount'=>0);
        $_SESSION['CAS_CAUSE_ID'] = $_REQUEST['intCause'];
        $_SESSION['CAS_CAUSE_NAME'] = $_REQUEST['strCause'];
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
        $_SESSION['PART_ID'] = '';
        $_SESSION['PART_QUANTITY'] = '';
        $_SESSION['PART_LOCATION'] = '';
        $_SESSION['PART_SERIAL'] = '';
        $strSql = "SELECT SCD_ID, SCD_CODE||' - '||SCD_NAME AS SCD_NAME FROM SCD_SCRAPCODE WHERE SCD_STATUS = 1 AND SCD_ID IN (SELECT DISTINCT(SCD_SCRAPCODE) FROM SCD_SCRAPCODE_RELATION WHERE SCD_STATUS = 1 AND SCD_CAUSE = " . $_SESSION['CAS_CAUSE_ID'] .") ORDER BY SCD_NAME, SCD_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if($objScrap->intAffectedRows>0){
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if($objScrap->intAffectedRows>1){
                $jsnPhpScriptResponse['strResponse']='<option selected="selected" value="-1">-- selecciona --</option>';
            }else{
                $jsnPhpScriptResponse['strResponse']='';
            }
            foreach($rstResponse as $objResponse){
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['SCD_ID'] . '">' . $objResponse['SCD_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Project':
        $jsnPhpScriptResponse = array('strResponse'=>'<option selected="selected" value="-1">-- no se encontraron registros --</option>','strError'=>'','intRecordCount'=>0);
        $_SESSION['SCD_SCRAPCODE_ID'] = $_REQUEST['intScrapCode'];
        $_SESSION['SCD_SCRAPCODE_NAME'] = $_REQUEST['strScrapCode'];
        $_SESSION['PRJ_PROJECT_ID'] = '';
        $_SESSION['PRJ_PROJECT_NAME'] = '';
        $_SESSION['PRF_PROFITCENTER_ID'] = '';
        $_SESSION['PRF_PROFITCENTER_NAME'] = '';
        $_SESSION['SGM_SEGMENT_ID'] = '';
        $_SESSION['SGM_SEGMENT_NAME'] = '';
        $_SESSION['APD_APD_ID'] = '';
        $_SESSION['APD_APD_NAME'] = '';
        $_SESSION['PART_ID'] = '';
        $_SESSION['PART_QUANTITY'] = '';
        $_SESSION['PART_LOCATION'] = '';
        $_SESSION['PART_SERIAL'] = '';
        $strSql = "SELECT PRJ_ID, PRJ_NAME FROM PRJ_PROJECT WHERE PRJ_STATUS = 1 AND PRJ_ID IN (SELECT PRJ_ID FROM PRJ_PROJECT WHERE PRJ_STATUS = 1 AND PRJ_ID IN (SELECT PRJ_PROJECT FROM PRJ_PROJECT_PROFITCENTER WHERE PRJ_STATUS = 1 AND PRJ_PROFITCENTER IN (SELECT PRF_ID FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['DVS_DIVISION_ID'] . "))))) AND PRJ_ID IN (SELECT PRJ_PROJECT FROM PRJ_PROJECT_STATION WHERE PRJ_STATION = (SELECT STT_ID FROM STT_STATION_RELATION WHERE STT_STATION = " . $_SESSION['STT_STATION_ID'] . " AND STT_AREA = " . $_SESSION['ARE_AREA_ID'] . " AND STT_STATUS = 1) AND PRJ_STATUS = 1) ORDER BY PRJ_NAME, PRJ_ID";
        $rstResponse = $objScrap->dbQuery($strSql);
        if($objScrap->intAffectedRows>0){
            $jsnPhpScriptResponse['intRecordCount'] = $objScrap->intAffectedRows;
            if($objScrap->intAffectedRows>1){
                $jsnPhpScriptResponse['strResponse']='<option selected="selected" value="-1">-- selecciona --</option>';
            }else{
                $jsnPhpScriptResponse['strResponse']='';
            }
            foreach($rstResponse as $objResponse){
                $jsnPhpScriptResponse['strResponse'] .= '<option value="' . $objResponse['PRJ_ID'] . '">' . $objResponse['PRJ_NAME'] . '</option>';
            }
            unset($objResponse);
        }
        unset($rstResponse);
        break;
    case 'Part':
        $jsnPhpScriptResponse = '';
        $_SESSION['PRJ_PROJECT_ID'] = $_REQUEST['intProject'];
        $_SESSION['PRJ_PROJECT_NAME'] = $_REQUEST['strProject'];
        $strSql = "SELECT PRF_ID, PRF_NAME FROM PRF_PROFITCENTER WHERE PRF_STATUS = 1 AND PRF_ID IN (SELECT PRF_PROFITCENTER FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_ID IN (SELECT PRJ_PROFITCENTER FROM PRJ_PROJECT_PROFITCENTER WHERE PRJ_STATUS = 1 AND PRJ_PROJECT = " . $_SESSION['PRJ_PROJECT_ID'] . " AND PRJ_PROFITCENTER IN (SELECT PRF_ID FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['DVS_DIVISION_ID'] . " AND DVS_SHIP IN (SELECT SHP_ID FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_SHIP = " . $_SESSION['SHP_SHIP_ID'] . "))))))";
        $rstData = $objScrap->dbQuery($strSql);
        $_SESSION['PRF_PROFITCENTER_ID'] = $rstData[0]['PRF_ID'];
        $_SESSION['PRF_PROFITCENTER_NAME'] = $rstData[0]['PRF_NAME'];
        unset($rstData);
        $strSql = "SELECT SGM_ID, SGM_NAME FROM SGM_SEGMENT WHERE SGM_STATUS = 1 AND SGM_ID IN (SELECT SGM_SEGMENT FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_ID IN (SELECT PRF_SEGMENT FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_ID IN (SELECT PRJ_PROFITCENTER FROM PRJ_PROJECT_PROFITCENTER WHERE PRJ_STATUS = 1 AND PRJ_PROJECT = " . $_SESSION['PRJ_PROJECT_ID'] . " AND PRJ_PROFITCENTER IN (SELECT PRF_ID FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['DVS_DIVISION_ID'] . " AND DVS_SHIP IN (SELECT SHP_ID FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_SHIP = " . $_SESSION['SHP_SHIP_ID'] . ")))))))";
        $rstData = $objScrap->dbQuery($strSql);
        $_SESSION['SGM_SEGMENT_ID'] = $rstData[0]['SGM_ID'];
        $_SESSION['SGM_SEGMENT_NAME'] = $rstData[0]['SGM_NAME'];
        unset($rstData);
        $strSql = "SELECT APD_ID, APD_NAME FROM APD_APD WHERE APD_STATUS = 1 AND APD_ID IN (SELECT APD_APD FROM APD_APD_RELATION WHERE APD_STATUS  = 1 AND APD_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_ID IN (SELECT PRF_SEGMENT FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_ID IN (SELECT PRJ_PROFITCENTER FROM PRJ_PROJECT_PROFITCENTER WHERE PRJ_STATUS = 1 AND PRJ_PROJECT = " . $_SESSION['PRJ_PROJECT_ID'] . " AND PRJ_PROFITCENTER IN (SELECT PRF_ID FROM PRF_PROFITCENTER_RELATION WHERE PRF_STATUS = 1 AND PRF_SEGMENT IN (SELECT SGM_ID FROM SGM_SEGMENT_RELATION WHERE SGM_STATUS = 1 AND SGM_DIVISION IN (SELECT DVS_ID FROM DVS_DIVISION_RELATION WHERE DVS_STATUS = 1 AND DVS_DIVISION = " . $_SESSION['DVS_DIVISION_ID'] . " AND DVS_SHIP IN (SELECT SHP_ID FROM SHP_SHIP_RELATION WHERE SHP_STATUS = 1 AND SHP_SHIP = " . $_SESSION['SHP_SHIP_ID'] . "))))))))";
        $rstData = $objScrap->dbQuery($strSql);
        $_SESSION['APD_APD_ID'] = $rstData[0]['APD_ID'];
        $_SESSION['APD_APD_NAME'] = $rstData[0]['APD_NAME'];
        $_SESSION['PART_ID'] = '';
        $_SESSION['PART_QUANTITY'] = '';
        $_SESSION['PART_LOCATION'] = '';
        $_SESSION['PART_SERIAL'] = '';
        unset($rstData);
        break;
    case 'getParts':
        $jsnPhpScriptResponse = array();
        $strSql = "SELECT PRT_ID, PRT_NUMBER, PRT_DESCRIPTION, PRT_COST, PRT_GLOBALPC FROM PRT_PART WHERE PRT_STATUS = 1 AND PRT_NUMBER LIKE ('" . $_REQUEST['strPart'] . "%') AND PRT_ID IN (SELECT PRJ_PART FROM PRJ_PROJECT_PART WHERE PRJ_STATUS = 1 AND PRJ_PROJECT = " . $_SESSION['PRJ_PROJECT_ID'] . ")";
        $rstResponse = $objScrap->dbQuery($strSql);
        $strPartPhoto = '';
        foreach($rstResponse as $objResponse){
            if(file_exists(PART_IMAGE_PATH . $objResponse['PRT_NUMBER'] . '.jpg')){
                $strPartPhoto = $objResponse['PRT_NUMBER'] . '.jpg';
            }else{
                $strPartPhoto = 'no_photo.png';
            }
            array_push($jsnPhpScriptResponse, array($objResponse['PRT_NUMBER'],$objResponse['PRT_DESCRIPTION'],$strPartPhoto));
        }
        unset($objResponse);
        unset($rstResponse);
        break;
    case 'PartData':
        $jsnPhpScriptResponse = array('strResponse'=>'','intPartId'=>'0','strError'=>'');
        $strSql = "SELECT * FROM PRT_PART WHERE PRT_STATUS = 1 AND PRT_NUMBER = '" . $_REQUEST['strPart'] . "'";
        $rstData = $objScrap->dbQuery($strSql);
        if($objScrap->intAffectedRows!=0){
            $jsnPhpScriptResponse['intPartId'] = $rstData[0]['PRT_ID'];
            $jsnPhpScriptResponse['strResponse'] = '<tr id="trPart_' . $rstData[0]['PRT_ID'] . '">';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdGridQty"><input id="txtPartQty_' . $rstData[0]['PRT_ID'] . '" type="text" disabled="disabled" class="inputGridQty" value="' . $_REQUEST['intQty'] . '" /></td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdGridPrt"><input id="txtPartPrt_' . $rstData[0]['PRT_ID'] . '" type="text" disabled="disabled" class="inputGridPrt" value="' . $rstData[0]['PRT_NUMBER'] . '" /></td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdGridDsc"><input id="txtPartDsc_' . $rstData[0]['PRT_ID'] . '" type="text" disabled="disabled" class="inputGridDsc" value="' . $rstData[0]['PRT_DESCRIPTION'] . '" /></td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdGridCst"><input id="txtPartCst_' . $rstData[0]['PRT_ID'] . '" type="text" disabled="disabled" class="inputGridCst" value="' . number_format(($rstData[0]['PRT_COST'] * $_REQUEST['intQty']),2,'.',',') . '" /></td>';
            $jsnPhpScriptResponse['strResponse'] .= '<td class="tdGridEdt">';
            $jsnPhpScriptResponse['strResponse'] .= '<label class="lblEdit" onclick="editPart(' . $rstData[0]['PRT_ID'] . ')">&#9998;</label>&nbsp;';
            $jsnPhpScriptResponse['strResponse'] .= '<label class="lblRemove" onclick="removePart(' . $rstData[0]['PRT_ID'] . ')">&#10006;</label>';
            $jsnPhpScriptResponse['strResponse'] .= '<input id="txtPartLoc_' . $rstData[0]['PRT_ID'] . '" type="hidden" value="' . $_REQUEST['strLocation'] . '" /></td>';
            $jsnPhpScriptResponse['strResponse'] .= '<input id="txtPartSrl_' . $rstData[0]['PRT_ID'] . '" type="hidden" value="' . $_REQUEST['strSerials'] . '" /></td>';
            $jsnPhpScriptResponse['strResponse'] .= '</td>';
            $jsnPhpScriptResponse['strResponse'] .= '</tr>';
        }
        unset($rstData);
        break;
    case 'addParts':
        $_SESSION['PART_ID'] = '';
        $_SESSION['PART_QUANTITY'] = '';
        $_SESSION['PART_LOCATION'] = '';
        $_SESSION['PART_SERIAL'] = '';
        $jsnPhpScriptResponse = '';
        for($intIndex=0;$intIndex<$_REQUEST['intParts'];$intIndex++){
            $_SESSION['PART_ID'][] = $_REQUEST['intPartId_' . $intIndex];
            $_SESSION['PART_QUANTITY'][] = $_REQUEST['intPartQty_' . $intIndex];
            $_SESSION['PART_LOCATION'][] = $_REQUEST['intPartLoc_' . $intIndex];
            $_SESSION['PART_SERIAL'][] = $_REQUEST['intPartSrl_' . $intIndex];
        }
        break;
    case 'insertScrapRecord':
        $intRecordDate = date('Ymd');
        $intRecordTime = date('His');
        $_SESSION['COMMENTS'] = $_REQUEST['strComments'];
        $_SESSION['WHY1'] = $_REQUEST['strWhy1'];
        $_SESSION['WHY2'] = $_REQUEST['strWhy2'];
        $_SESSION['WHY3'] = $_REQUEST['strWhy3'];
        $_SESSION['WHY4'] = $_REQUEST['strWhy4'];
        $_SESSION['WHY5'] = $_REQUEST['strWhy5'];
        $_SESSION['ACTION1'] = $_REQUEST['strAction1'];
        $_SESSION['ACTION2'] = $_REQUEST['strAction2'];
        $_SESSION['ACTION3'] = $_REQUEST['strAction3'];
        $_SESSION['ACTION4'] = $_REQUEST['strAction4'];
        $_SESSION['ACTION5'] = $_REQUEST['strAction5'];

        $intAmount = 0;

        $strLabelParts = 'Parte(s) [Cantidad]: ';
        foreach($_SESSION['PART_ID'] as $intArrIndex=>$objPartId){
            $strSql = "SELECT PRT_DESCRIPTION, PRT_COST, PRT_NUMBER FROM PRT_PART WHERE PRT_ID = " . $objPartId;
            $rstPart = $objScrap->dbQuery($strSql);
            $_SESSION['PART_DESCRIPTION'][$intArrIndex] = $rstPart[0]['PRT_DESCRIPTION'];
            $_SESSION['PART_COST'][$intArrIndex] = $rstPart[0]['PRT_COST'];
            $strLabelParts .= $rstPart[0]['PRT_NUMBER'] . '[' . $_SESSION['PART_QUANTITY'][$intArrIndex] . '] ';
            unset($rstPart);
            $strSql = "SELECT TYP_TYPE.TYP_ID, TYP_TYPE.TYP_NAME FROM PRT_PART_TYPE, TYP_TYPE WHERE PRT_PART_TYPE.PRT_PART = " . $objPartId . " AND PRT_PART_TYPE.PRT_TYPE = TYP_TYPE.TYP_ID";
            $rstPart = $objScrap->dbQuery($strSql);
            $_SESSION['PART_TYPE_ID'][$intArrIndex] = $rstPart[0]['TYP_ID'];
            $_SESSION['PART_TYPE_NAME'][$intArrIndex] = $rstPart[0]['TYP_NAME'];
            unset($rstPart);
            $strSql = "SELECT UNT_UNIT.UNT_ID, UNT_UNIT.UNT_CODE FROM PRT_PART_UNIT, UNT_UNIT WHERE PRT_PART_UNIT.PRT_PART = " . $objPartId . " AND PRT_PART_UNIT.PRT_UNIT = UNT_UNIT.UNT_ID";
            $rstPart = $objScrap->dbQuery($strSql);
            $_SESSION['PART_UNIT_ID'][$intArrIndex] = $rstPart[0]['UNT_ID'];
            $_SESSION['PART_UNIT_NAME'][$intArrIndex] = $rstPart[0]['UNT_CODE'];
            unset($rstPart);
            $intAmount = $intAmount + ($_SESSION['PART_COST'][$intArrIndex] * $_SESSION['PART_QUANTITY'][$intArrIndex]);
        }
        $strSql = "INSERT INTO SCR_SCRAP(SCR_COUNTRY_ID,SCR_COUNTRY,SCR_PLANT_ID,SCR_PLANT,SCR_SHIP_ID,SCR_SHIP,SCR_DIVISION_ID,SCR_DIVISION,SCR_SEGMENT_ID,SCR_SEGMENT,SCR_PROFITCENTER_ID,SCR_PROFITCENTER,SCR_APD_ID,SCR_APD,SCR_COSTCENTER_ID,SCR_COSTCENTER,SCR_AREA_ID,SCR_AREA,SCR_STATION_ID,SCR_STATION,SCR_LINE_ID,SCR_LINE,SCR_FAULT_ID,SCR_FAULT,SCR_CAUSE_ID,SCR_CAUSE,SCR_SCRAPCODE_ID,SCR_SCRAPCODE,SCR_COST,SCR_PROJECT_ID,SCR_PROJECT) VALUES(" . $_SESSION['CNT_COUNTRY_ID'] . ",'" . $_SESSION['CNT_COUNTRY_NAME'] . "'," . $_SESSION['PLN_PLANT_ID'] . ",'" . $_SESSION['PLN_PLANT_NAME'] . "'," . $_SESSION['SHP_SHIP_ID'] . ",'" . $_SESSION['SHP_SHIP_NAME'] . "'," . $_SESSION['DVS_DIVISION_ID'] . ",'" . $_SESSION['DVS_DIVISION_NAME'] . "'," . $_SESSION['SGM_SEGMENT_ID'] . ",'" . $_SESSION['SGM_SEGMENT_NAME'] . "'," . $_SESSION['PRF_PROFITCENTER_ID'] . ",'" . $_SESSION['PRF_PROFITCENTER_NAME'] . "',1,'15'," . $_SESSION['CST_COSTCENTER_ID'] . ",'" . $_SESSION['CST_COSTCENTER_NAME'] . "'," . $_SESSION['ARE_AREA_ID'] . ",'" . $_SESSION['ARE_AREA_NAME'] . "'," . $_SESSION['STT_STATION_ID'] . ",'" . $_SESSION['STT_STATION_NAME'] . "'," . $_SESSION['LIN_LINE_ID'] . ",'" . $_SESSION['LIN_LINE_NAME'] . "'," . $_SESSION['FLT_FAULT_ID'] . ",'" . $_SESSION['FLT_FAULT_NAME'] . "'," . $_SESSION['CAS_CAUSE_ID'] . ",'" . $_SESSION['CAS_CAUSE_NAME'] . "'," . $_SESSION['SCD_SCRAPCODE_ID'] . ",'" . $_SESSION['SCD_SCRAPCODE_NAME'] . "'," . $intAmount . "," . $_SESSION['PRJ_PROJECT_ID'] . ",'" . $_SESSION['PRJ_PROJECT_NAME'] . "') RETURNING SCR_ID INTO :intInsertedID";
        $objScrap->dbInsert($strSql);
        $intScrapId = $objScrap->intLastInsertId;

        $strSql = "INSERT INTO SCR_SCRAP_STATUS(SCR_SCRAP,SCR_STATUS,SCR_PERSONALNUMBER,SCR_USER,SCR_DATE,SCR_TIME) VALUES(" . $intScrapId . ",0," . $_SESSION['USR_PERSONALNUMBER'] . ",'" . $_SESSION['USR_FULLNAME'] . "'," . $intRecordDate . "," . $intRecordTime . ")";
        $objScrap->dbInsert($strSql);

        foreach($_SESSION['PART_ID'] as $intArrIndex=>$objPartId){

            $strSql = "INSERT INTO SCR_SCRAP_PART(SCR_SCRAP,SCR_PART,SCR_DESCRIPTION,SCR_QUANTITY,SCR_COST,SCR_LOCATION,SCR_TYPE_ID,SCR_TYPE,SCR_UNIT_ID,SCR_UNIT,SCR_AMOUNT) VALUES(" . $intScrapId . "," . $objPartId . ",'" . $_SESSION['PART_DESCRIPTION'][$intArrIndex] . "'," . $_SESSION['PART_QUANTITY'][$intArrIndex] . "," . $_SESSION['PART_COST'][$intArrIndex] . ",'" . $_SESSION['PART_LOCATION'][$intArrIndex] . "'," . $_SESSION['PART_TYPE_ID'][$intArrIndex] . ",'" . $_SESSION['PART_TYPE_NAME'][$intArrIndex] . "'," . $_SESSION['PART_UNIT_ID'][$intArrIndex] . ",'" . $_SESSION['PART_UNIT_NAME'][$intArrIndex] . "'," . $_SESSION['PART_QUANTITY'][$intArrIndex] * $_SESSION['PART_COST'][$intArrIndex] . ") RETURNING SCR_ID INTO :intInsertedID";
            $objScrap->dbInsert($strSql);
            $intScrapPartId = $objScrap->intLastInsertId;
            if($_SESSION['PART_SERIAL'][$intArrIndex]!=''){
                $arrSerials = explode("|%|",$_SESSION['PART_SERIAL'][$intArrIndex]);
                foreach($arrSerials as $objSerials){
                    if($objSerials!=''){
                        $strSql = "INSERT INTO SCR_SCRAP_PART_SERIAL(SCR_SCRAP,SCR_PART,SCR_SERIAL) VALUES(" . $intScrapId . "," . $intScrapPartId . ",'" . $objSerials . "')";
                        $objScrap->dbInsert($strSql);
                    }
                }
            }
        }
        $strLabelCommentsActions = 'Comentarios y/o Acciones Correctivas: ';
        $strSql = "INSERT INTO SCR_SCRAP_COMMENT(SCR_SCRAP,SCR_COMMENT) VALUES(" . $intScrapId . ",'" . $_SESSION['COMMENTS'] . "')";
        $objScrap->dbInsert($strSql);
        $strLabelCommentsActions .= trim($_SESSION['COMMENTS']) . ' ';
        $intRecIndex = 0;
        if($_SESSION['WHY1']!=''){
            $intRecIndex++;
            $strSql = "INSERT INTO SCR_SCRAP_WHY(SCR_SCRAP,SCR_NUMBER,SCR_WHY) VALUES (" . $intScrapId . "," . $intRecIndex . ",'" . $_SESSION['WHY1'] . "')";
            $objScrap->dbInsert($strSql);
        }
        if($_SESSION['WHY2']!=''){
            $intRecIndex++;
            $strSql = "INSERT INTO SCR_SCRAP_WHY(SCR_SCRAP,SCR_NUMBER,SCR_WHY) VALUES (" . $intScrapId . "," . $intRecIndex . ",'" . $_SESSION['WHY2'] . "')";
            $objScrap->dbInsert($strSql);
        }
        if($_SESSION['WHY3']!=''){
            $intRecIndex++;
            $strSql = "INSERT INTO SCR_SCRAP_WHY(SCR_SCRAP,SCR_NUMBER,SCR_WHY) VALUES (" . $intScrapId . "," . $intRecIndex . ",'" . $_SESSION['WHY3'] . "')";
            $objScrap->dbInsert($strSql);
        }
        if($_SESSION['WHY4']!=''){
            $intRecIndex++;
            $strSql = "INSERT INTO SCR_SCRAP_WHY(SCR_SCRAP,SCR_NUMBER,SCR_WHY) VALUES (" . $intScrapId . "," . $intRecIndex . ",'" . $_SESSION['WHY4'] . "')";
            $objScrap->dbInsert($strSql);
        }
        if($_SESSION['WHY5']!=''){
            $intRecIndex++;
            $strSql = "INSERT INTO SCR_SCRAP_WHY(SCR_SCRAP,SCR_NUMBER,SCR_WHY) VALUES (" . $intScrapId . "," . $intRecIndex . ",'" . $_SESSION['WHY5'] . "')";
            $objScrap->dbInsert($strSql);
        }
        $intRecIndex = 0;
        if($_SESSION['ACTION1']!=''){
            $intRecIndex++;
            $strSql = "INSERT INTO SCR_SCRAP_ACTION(SCR_SCRAP,SCR_NUMBER,SCR_ACTION) VALUES (" . $intScrapId . "," . $intRecIndex . ",'" . $_SESSION['ACTION1'] . "')";
            $objScrap->dbInsert($strSql);
            $strLabelCommentsActions .= trim($_SESSION['ACTION1']) . ' ';
        }
        if($_SESSION['ACTION2']!=''){
            $intRecIndex++;
            $strSql = "INSERT INTO SCR_SCRAP_ACTION(SCR_SCRAP,SCR_NUMBER,SCR_ACTION) VALUES (" . $intScrapId . "," . $intRecIndex . ",'" . $_SESSION['ACTION2'] . "')";
            $objScrap->dbInsert($strSql);
            $strLabelCommentsActions .= trim($_SESSION['ACTION2']) . ' ';
        }
        if($_SESSION['ACTION3']!=''){
            $intRecIndex++;
            $strSql = "INSERT INTO SCR_SCRAP_ACTION(SCR_SCRAP,SCR_NUMBER,SCR_ACTION) VALUES (" . $intScrapId . "," . $intRecIndex . ",'" . $_SESSION['ACTION3'] . "')";
            $objScrap->dbInsert($strSql);
            $strLabelCommentsActions .= trim($_SESSION['ACTION3']) . ' ';
        }
        if($_SESSION['ACTION4']!=''){
            $intRecIndex++;
            $strSql = "INSERT INTO SCR_SCRAP_ACTION(SCR_SCRAP,SCR_NUMBER,SCR_ACTION) VALUES (" . $intScrapId . "," . $intRecIndex . ",'" . $_SESSION['ACTION4'] . "')";
            $objScrap->dbInsert($strSql);
            $strLabelCommentsActions .= trim($_SESSION['ACTION4']) . ' ';
        }
        if($_SESSION['ACTION5']!=''){
            $intRecIndex++;
            $strSql = "INSERT INTO SCR_SCRAP_ACTION(SCR_SCRAP,SCR_NUMBER,SCR_ACTION) VALUES (" . $intScrapId . "," . $intRecIndex . ",'" . $_SESSION['ACTION5'] . "')";
            $objScrap->dbInsert($strSql);
            $strLabelCommentsActions .= trim($_SESSION['ACTION5']) . ' ';
        }

        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY IS NULL AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'] . " AND ALR_PLANT IS NULL AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'] . " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'] . " AND ALR_SHIP IS NULL AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'] . " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'] . " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'] . " AND ALR_DIVISION IS NULL AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT IS NULL";
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT = " . $_SESSION['SGM_SEGMENT_ID'];
        $strSql .= " AND ALR_PROFITCENTER IS NULL";
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT = " . $_SESSION['SGM_SEGMENT_ID'];
        $strSql .= " AND ALR_PROFITCENTER = " . $_SESSION['PRF_PROFITCENTER_ID'];
        $strSql .= " AND ALR_APD IS NULL";
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT = " . $_SESSION['SGM_SEGMENT_ID'];
        $strSql .= " AND ALR_APD = " . $_SESSION['APD_APD_ID'];
        $strSql .= " AND ALR_AREA IS NULL";
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT = " . $_SESSION['SGM_SEGMENT_ID'];
        $strSql .= " AND ALR_APD = " . $_SESSION['APD_APD_ID'];
        $strSql .= " AND ALR_AREA = " . $_SESSION['ARE_AREA_ID'];
        $strSql .= " AND ALR_STATION IS NULL";
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT = " . $_SESSION['SGM_SEGMENT_ID'];
        $strSql .= " AND ALR_APD = " . $_SESSION['APD_APD_ID'];
        $strSql .= " AND ALR_AREA = " . $_SESSION['ARE_AREA_ID'];
        $strSql .= " AND ALR_STATION = " . $_SESSION['STT_STATION_ID'];
        $strSql .= " AND ALR_LINE IS NULL";
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT = " . $_SESSION['SGM_SEGMENT_ID'];
        $strSql .= " AND ALR_APD = " . $_SESSION['APD_APD_ID'];
        $strSql .= " AND ALR_AREA = " . $_SESSION['ARE_AREA_ID'];
        $strSql .= " AND ALR_STATION = " . $_SESSION['STT_STATION_ID'];
        $strSql .= " AND ALR_LINE = " . $_SESSION['LIN_LINE_ID'];
        $strSql .= " AND ALR_FAULT IS NULL";
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT = " . $_SESSION['SGM_SEGMENT_ID'];
        $strSql .= " AND ALR_APD = " . $_SESSION['APD_APD_ID'];
        $strSql .= " AND ALR_AREA = " . $_SESSION['ARE_AREA_ID'];
        $strSql .= " AND ALR_STATION = " . $_SESSION['STT_STATION_ID'];
        $strSql .= " AND ALR_LINE = " . $_SESSION['LIN_LINE_ID'];
        $strSql .= " AND ALR_FAULT = " . $_SESSION['FLT_FAULT_ID'];
        $strSql .= " AND ALR_CAUSE IS NULL";
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT = " . $_SESSION['SGM_SEGMENT_ID'];
        $strSql .= " AND ALR_APD = " . $_SESSION['APD_APD_ID'];
        $strSql .= " AND ALR_AREA = " . $_SESSION['ARE_AREA_ID'];
        $strSql .= " AND ALR_STATION = " . $_SESSION['STT_STATION_ID'];
        $strSql .= " AND ALR_LINE = " . $_SESSION['LIN_LINE_ID'];
        $strSql .= " AND ALR_FAULT = " . $_SESSION['FLT_FAULT_ID'];
        $strSql .= " AND ALR_CAUSE = " . $_SESSION['CAS_CAUSE_ID'];
        $strSql .= " AND ALR_SCRAPCODE IS NULL";
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT = " . $_SESSION['SGM_SEGMENT_ID'];
        $strSql .= " AND ALR_APD = " . $_SESSION['APD_APD_ID'];
        $strSql .= " AND ALR_AREA = " . $_SESSION['ARE_AREA_ID'];
        $strSql .= " AND ALR_STATION = " . $_SESSION['STT_STATION_ID'];
        $strSql .= " AND ALR_LINE = " . $_SESSION['LIN_LINE_ID'];
        $strSql .= " AND ALR_FAULT = " . $_SESSION['FLT_FAULT_ID'];
        $strSql .= " AND ALR_CAUSE = " . $_SESSION['CAS_CAUSE_ID'];
        $strSql .= " AND ALR_SCRAPCODE = " . $_SESSION['SCD_SCRAPCODE_ID'];
        $strSql .= " AND ALR_PROJECT IS NULL";
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $strSql = "SELECT ALR_ID FROM ALR_ALERT WHERE ALR_COUNTRY = " . $_SESSION['CNT_COUNTRY_ID'];
        $strSql .= " AND ALR_PLANT = " . $_SESSION['PLN_PLANT_ID'];
        $strSql .= " AND ALR_SHIP = " . $_SESSION['SHP_SHIP_ID'];
        $strSql .= " AND ALR_DIVISION = " . $_SESSION['DVS_DIVISION_ID'];
        $strSql .= " AND ALR_SEGMENT = " . $_SESSION['SGM_SEGMENT_ID'];
        $strSql .= " AND ALR_APD = " . $_SESSION['APD_APD_ID'];
        $strSql .= " AND ALR_AREA = " . $_SESSION['ARE_AREA_ID'];
        $strSql .= " AND ALR_STATION = " . $_SESSION['STT_STATION_ID'];
        $strSql .= " AND ALR_LINE = " . $_SESSION['LIN_LINE_ID'];
        $strSql .= " AND ALR_FAULT = " . $_SESSION['FLT_FAULT_ID'];
        $strSql .= " AND ALR_CAUSE = " . $_SESSION['CAS_CAUSE_ID'];
        $strSql .= " AND ALR_SCRAPCODE = " . $_SESSION['SCD_SCRAPCODE_ID'];
        $strSql .= " AND ALR_PROJECT = " . $_SESSION['PRJ_PROJECT_ID'];
        $strSql .= " AND ALR_COST <= " . $intAmount;
        $rstAlert = $objScrap->dbQuery($strSql);
        foreach($rstAlert as $objAlert){
            $strSql = "INSERT INTO ALR_ALERT_USER(ALR_ALERT,ALR_SCRAP) VALUES(" . $objAlert['ALR_ID'] . "," . $intScrapId . ")";
            $objScrap->dbInsert($strSql);
        };
        unset($rstAlert);
        $jsnPhpScriptResponse = array('strFolio'=>$intScrapId,'intAmount'=>number_format($intAmount,2,'.',','),'intDate'=>$intRecordDate,'intTime'=>$intRecordTime,'strUser'=>$_SESSION['USR_FULLNAME'],'strArea'=>$_SESSION['ARE_AREA_NAME'],'strStation'=>$_SESSION['STT_STATION_NAME'],'strLine'=>$_SESSION['LIN_LINE_NAME'],'strFault'=>$_SESSION['FLT_FAULT_NAME'],'strCause'=>$_SESSION['CAS_CAUSE_NAME'],'strScrapCode'=>$_SESSION['SCD_SCRAPCODE_NAME'],'strParts'=>$strLabelParts,'strCommentsActions'=>$strLabelCommentsActions);
        break;
};
unset($objScrap);
echo json_encode($jsnPhpScriptResponse);

if(!$blnFromAjax){
    ini_set("display_errors",1);
    var_dump($_SESSION) . "<br /><br />";
}

?>