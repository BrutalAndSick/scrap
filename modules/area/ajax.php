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
                $strGrid .= '<tr id="trGrid_' . $rstData[$intIndex]['ARE_ID'] . '">';
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstData[$intIndex]['ARE_ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstData[$intIndex]['ARE_NAME'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                if ($rstData[$intIndex]['ARE_STATUS'] == 1) {
                    $strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex]['ARE_ID'] . '" currentValue="' . $rstData[$intIndex]['ARE_STATUS'] . '" onclick="deactivateArea(' . $rstData[$intIndex]['ARE_ID'] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                } else {
                    $strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex]['ARE_ID'] . '" currentValue="' . $rstData[$intIndex]['ARE_STATUS'] . '" onclick="deactivateArea(' . $rstData[$intIndex]['ARE_ID'] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                }
                $strGrid .= '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $strGrid .= '<label id="lblEdit_' . $rstData[$intIndex]['ARE_ID'] . '" areaname="' . $rstData[$intIndex]['ARE_NAME'] . '" onclick="showModal(' . $rstData[$intIndex]['ARE_ID'] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
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
    case 'insertArea':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $strArea = $_REQUEST['strArea'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM ARE_AREA WHERE ARE_NAME = '" . $strArea . "'";
        $rstCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                $jsnData['strError'] = "El área " . $strArea . " ya existe";
            }else{
                $strSql = "INSERT INTO ARE_AREA(ARE_NAME,ARE_STATUS) VALUES ('" . $strArea . "',1) RETURNING ARE_ID INTO :intInsertedID";
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
    case 'updateArea':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $intAreaId = $_REQUEST['intAreaId'];
        $strArea = $_REQUEST['strArea'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM ARE_AREA WHERE ARE_NAME = '" . $strArea . "' AND ARE_ID <> " . $intAreaId;
        $rstCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstCount[0]['COUNT'];
                $jsnData['strError'] = "El área " . $strArea . " ya existe";
            }else{
                $strSql = "UPDATE ARE_AREA SET ARE_NAME = '" . $strArea . "' WHERE ARE_ID = " . $intAreaId;
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
    case 'deactivateArea':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $intAreaId = $_REQUEST['intAreaId'];
        $intStatus = $_REQUEST['intStatus'];
        $strSql = "UPDATE ARE_AREA SET ARE_STATUS = " . $intStatus . " WHERE ARE_ID = " . $intAreaId;
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