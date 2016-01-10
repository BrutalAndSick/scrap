<?php
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('../../lib/scrap.php');
$objScrap = new clsScrap();
$strProcess = $_REQUEST['strProcess'];
switch ($strProcess){
    case 'updateGrid':
        $jsnData = array('grid'=>'','pagination'=>'','intSqlNumberOfRecords'=>0);
        $strSql = $_REQUEST['strSql'];
        $strSqlOrder = $_REQUEST['strSqlOrder'];
        $intSqlPage = $_REQUEST['intSqlPage'];
        $intSqlLimit = $_REQUEST['intSqlLimit'];
        $intSqlNumberOfColumns = $_REQUEST['intSqlNumberOfColumns'];
        $rstData = $objScrap->dbQuery($strSql . $strSqlOrder);
        $intSqlNumberOfRecords = $objScrap->getProperty('intAffectedRows');
        $jsnData['intSqlNumberOfRecords'] = $intSqlNumberOfRecords;
        if($intSqlNumberOfRecords!=0){
            $intPages = ceil($intSqlNumberOfRecords / $intSqlLimit);
        }else{
            $intPages = 1;
        }
        $intFirstRecord = ($intSqlLimit * $intSqlPage) - $intSqlLimit;
        $intLastRecord = $intFirstRecord + $intSqlLimit - 1;
        $strGrid = '';
        if($intSqlNumberOfRecords!=0){
            for ($intIndex = $intFirstRecord; $intIndex <= $intLastRecord; $intIndex++) {
                $strGrid .= '<tr id="trGrid_' . $rstData[$intIndex]['UNT_ID'] . '">';

                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstData[$intIndex]['UNT_ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstData[$intIndex]['UNT_NAME'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstData[$intIndex]['UNT_CODE'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                if ($rstData[$intIndex]['UNT_STATUS'] == 1) {
                    $strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex]['UNT_ID'] . '" currentValue="' . $rstData[$intIndex]['UNT_STATUS'] . '" onclick="deactivateUnit(' . $rstData[$intIndex]['UNT_ID'] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                } else {
                    $strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex]['UNT_ID'] . '" currentValue="' . $rstData[$intIndex]['UNT_STATUS'] . '" onclick="deactivateUnit(' . $rstData[$intIndex]['UNT_ID'] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                }
                $strGrid .= '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $strGrid .= '<label id="lblEdit_' . $rstData[$intIndex]['UNT_ID'] . '" countryname="' . $rstData[$intIndex]['UNT_NAME'] . '" onclick="showModal(' . $rstData[$intIndex]['UNT_ID'] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
                $strGrid .= '</td>';

                $strGrid .= '</tr>';
                if ($intIndex == ($intSqlNumberOfRecords - 1)) {
                    break;
                }
            };
        }else{
            $strGrid .= '<tr><td class="tdGrid" style="text-align: center" colspan="' . $intSqlNumberOfColumns . '">No existen registros</td></tr>';
        }
        unset($rstData);
        $jsnData['grid'] = $strGrid;
        require_once('../../lib/scrap_grid/class.php');
        $objGrid = new clsGrid();
        $jsnData['pagination'] = $objGrid->gridPagination($intSqlPage,$intPages,$intSqlNumberOfRecords,$intSqlLimit);
        unset($objGrid);
        break;
    case 'insertUnit':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $strUnit = $_REQUEST['strUnit'];
        $strCode = $_REQUEST['strCode'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM UNT_UNIT WHERE UNT_NAME = '" . $strUnit . "'";
        $rstCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstCount[0]['COUNT']!=0){
                unset($rstCount);
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                $jsnData['strError'] = "La unidad " . $strUnit . " ya existe";
            }else{
                unset($rstCount);
                $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM UNT_UNIT WHERE UNT_CODE = '" . $strCode . "'";
                $rstCount = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    if($rstCount[0]['COUNT']!=0){
                        unset($rstCount);
                        $jsnData['blnGo'] = 'false';
                        $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                        $jsnData['strError'] = "El codigo de pais " . $strCode . " ya existe";
                    }else{
                        unset($rstCount);
                        $strSql = "INSERT INTO UNT_UNIT(UNT_NAME,UNT_CODE,UNT_STATUS) VALUES ('" . $strUnit . "','" . $strCode . "',1) RETURNING UNT_ID INTO :intInsertedID";
                        $objScrap->dbInsert($strSql);
                        if($objScrap->getProperty('strDBError')!=''){
                            $jsnData['blnGo'] = 'false';
                            $jsnData['strError'] = $objScrap->getProperty('strDBError');
                        }
                    }
                }else{
                    $jsnData['blnGo'] = 'false';
                    $jsnData['strError'] = $objScrap->getProperty('strDBError');
                    unset($rstCount);
                }
            }
        }else{
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
            unset($rstCount);
        }
        break;
    case 'updateUnit':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $intUnitId = $_REQUEST['intUnitId'];
        $strUnit = $_REQUEST['strUnit'];
        $strCode = $_REQUEST['strCode'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM UNT_UNIT WHERE UNT_NAME = '" . $strUnit . "' AND UNT_ID <> " . $intUnitId;
        $rstCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstCount[0]['COUNT']!=0){
                unset($rstCount);
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                $jsnData['strError'] = "La unidad " . $strUnit . " ya existe";
            }else{
                unset($rstCount);
                $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM UNT_UNIT WHERE UNT_CODE = '" . $strCode . "' AND UNT_ID <> " . $intUnitId;
                $rstCount = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    if($rstCount[0]['COUNT']!=0){
                        unset($rstCount);
                        $jsnData['blnGo'] = 'false';
                        $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                        $jsnData['strError'] = "El codigo de pais " . $strCode . " ya existe";
                    }else{
                        unset($rstCount);
                        $strSql = "UPDATE UNT_UNIT SET UNT_NAME = '" . $strUnit . "', UNT_CODE = '" . $strCode . "' WHERE UNT_ID = " . $intUnitId;
                        $objScrap->dbUpdate($strSql);
                        if($objScrap->getProperty('strDBError')!=''){
                            $jsnData['blnGo'] = 'false';
                            $jsnData['strError'] = $objScrap->getProperty('strDBError');
                        }
                    }
                }else{
                    $jsnData['blnGo'] = 'false';
                    $jsnData['strError'] = $objScrap->getProperty('strDBError');
                    unset($rstCount);
                }
            }
        }else{
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
            unset($rstCount);
        }
        break;
    case 'deactivateUnit':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $intUnitId = $_REQUEST['intUnitId'];
        $intStatus = $_REQUEST['intStatus'];
        $strSql = "UPDATE UNT_UNIT SET UNT_STATUS = " . $intStatus . " WHERE UNT_ID = " . $intUnitId;
        $objScrap->dbUpdate($strSql);
        if($objScrap->getProperty('strDBError')!=''){
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        break;
};
unset($objScrap);
echo json_encode($jsnData);
?>