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
                $strGrid .= '<tr id="trGrid_' . $rstData[$intIndex]['CNT_ID'] . '">';

                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstData[$intIndex]['CNT_ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstData[$intIndex]['CNT_NAME'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstData[$intIndex]['CNT_CODE'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: right">' . $rstData[$intIndex]['CNT_NUMBER'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                if ($rstData[$intIndex]['CNT_STATUS'] == 1) {
                    $strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex]['CNT_ID'] . '" currentValue="' . $rstData[$intIndex]['CNT_STATUS'] . '" onclick="deactivateCountry(' . $rstData[$intIndex]['CNT_ID'] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                } else {
                    $strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex]['CNT_ID'] . '" currentValue="' . $rstData[$intIndex]['CNT_STATUS'] . '" onclick="deactivateCountry(' . $rstData[$intIndex]['CNT_ID'] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                }
                $strGrid .= '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $strGrid .= '<label id="lblEdit_' . $rstData[$intIndex]['CNT_ID'] . '" countryname="' . $rstData[$intIndex]['CNT_NAME'] . '" onclick="showModal(' . $rstData[$intIndex]['CNT_ID'] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
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
    case 'insertCountry':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $strCountry = $_REQUEST['strCountry'];
        $strCode = $_REQUEST['strCode'];
        $strNumber = $_REQUEST['strNumber'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM CNT_COUNTRY WHERE CNT_NAME = '" . $strCountry . "'";
        $rstCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstCount[0]['COUNT']!=0){
                unset($rstCount);
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                $jsnData['strError'] = "El pais " . $strCountry . " ya existe";
            }else{
                unset($rstCount);
                $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM CNT_COUNTRY WHERE CNT_CODE = '" . $strCode . "'";
                $rstCount = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    if($rstCount[0]['COUNT']!=0){
                        unset($rstCount);
                        $jsnData['blnGo'] = 'false';
                        $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                        $jsnData['strError'] = "El codigo de pais " . $strCode . " ya existe";
                    }else{
                        unset($rstCount);
                        $strSql = "INSERT INTO CNT_COUNTRY(CNT_NAME,CNT_CODE,CNT_NUMBER,CNT_STATUS) VALUES ('" . $strCountry . "','" . $strCode . "'," . $strNumber . ",1) RETURNING CNT_ID INTO :intInsertedID";
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
    case 'updateCountry':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $intCountryId = $_REQUEST['intCountryId'];
        $strCountry = $_REQUEST['strCountry'];
        $strCode = $_REQUEST['strCode'];
        $strNumber = $_REQUEST['strNumber'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM CNT_COUNTRY WHERE CNT_NAME = '" . $strCountry . "' AND CNT_ID <> " . $intCountryId;
        $rstCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstCount[0]['COUNT']!=0){
                unset($rstCount);
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                $jsnData['strError'] = "El pais " . $strCountry . " ya existe";
            }else{
                unset($rstCount);
                $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM CNT_COUNTRY WHERE CNT_CODE = '" . $strCode . "' AND CNT_ID <> " . $intCountryId;
                $rstCount = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    if($rstCount[0]['COUNT']!=0){
                        unset($rstCount);
                        $jsnData['blnGo'] = 'false';
                        $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                        $jsnData['strError'] = "El codigo de pais " . $strCode . " ya existe";
                    }else{
                        unset($rstCount);
                        $strSql = "UPDATE CNT_COUNTRY SET CNT_NAME = '" . $strCountry . "', CNT_CODE = '" . $strCode . "', CNT_NUMBER = " . $strNumber . " WHERE CNT_ID = " . $intCountryId;
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
    case 'deactivateCountry':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $intCountryId = $_REQUEST['intCountryId'];
        $intStatus = $_REQUEST['intStatus'];
        $strSql = "UPDATE CNT_COUNTRY SET CNT_STATUS = " . $intStatus . " WHERE CNT_ID = " . $intCountryId;
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