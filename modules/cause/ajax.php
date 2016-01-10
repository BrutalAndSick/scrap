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
                $strGrid .= '<tr id="trGrid_' . $rstData[$intIndex]['CAS_ID'] . '">';
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstData[$intIndex]['CAS_ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstData[$intIndex]['CAS_NAME'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                if ($rstData[$intIndex]['CAS_STATUS'] == 1) {
                    $strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex]['CAS_ID'] . '" currentValue="' . $rstData[$intIndex]['CAS_STATUS'] . '" onclick="deactivateCause(' . $rstData[$intIndex]['CAS_ID'] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                } else {
                    $strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex]['CAS_ID'] . '" currentValue="' . $rstData[$intIndex]['CAS_STATUS'] . '" onclick="deactivateCause(' . $rstData[$intIndex]['CAS_ID'] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                }
                $strGrid .= '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $strGrid .= '<label id="lblEdit_' . $rstData[$intIndex]['CAS_ID'] . '" causename="' . $rstData[$intIndex]['CAS_NAME'] . '" onclick="showModal(' . $rstData[$intIndex]['CAS_ID'] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
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
    case 'insertCause':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $strCause = $_REQUEST['strCause'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM CAS_CAUSE WHERE CAS_NAME = '" . $strCause . "'";
        $rstCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                $jsnData['strError'] = "La causa " . $strCause . " ya existe";
            }else{
                $strSql = "INSERT INTO CAS_CAUSE(CAS_NAME,CAS_STATUS) VALUES ('" . $strCause . "',1) RETURNING CAS_ID INTO :intInsertedID";
                $objScrap->dbInsert($strSql);
                if($objScrap->getProperty('strDBError')!=''){
                    $jsnData['blnGo'] = 'false';
                    $jsnData['strError'] = $objScrap->getProperty('strDBError');
                }
            }
        }else{
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        unset($rstCount);
        break;
    case 'updateCause':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $intCauseId = $_REQUEST['intCauseId'];
        $strCause = $_REQUEST['strCause'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM CAS_CAUSE WHERE CAS_NAME = '" . $strCause . "' AND CAS_ID <> " . $intCauseId;
        $rstCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstCount[0]['COUNT'];
                $jsnData['strError'] = "La causa " . $strCause . " ya existe";
            }else{
                $strSql = "UPDATE CAS_CAUSE SET CAS_NAME = '" . $strCause . "' WHERE CAS_ID = " . $intCauseId;
                $objScrap->dbUpdate($strSql);
                if($objScrap->getProperty('strDBError')!=''){
                    $jsnData['blnGo'] = 'false';
                    $jsnData['strError'] = $objScrap->getProperty('strDBError');
                }
            }
        }else{
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        unset($rstCount);
        break;
    case 'deactivateCause':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $intCauseId = $_REQUEST['intCauseId'];
        $intStatus = $_REQUEST['intStatus'];
        $strSql = "UPDATE CAS_CAUSE SET CAS_STATUS = " . $intStatus . " WHERE CAS_ID = " . $intCauseId;
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