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
        $rstScrapCode = $objScrap->dbQuery($strSql . $strSqlOrder);
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
                $strGrid .= '<tr id="trGrid_' . $rstScrapCode[$intIndex]['SCD_ID'] . '">';
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstScrapCode[$intIndex]['SCD_ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstScrapCode[$intIndex]['SCD_NAME'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstScrapCode[$intIndex]['SCD_CODE'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstScrapCode[$intIndex]['SCD_REASON_CODE'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                if ($rstScrapCode[$intIndex]['SCD_STATUS'] == 1) {
                    $strGrid .= '<label id="lblDeactivate_' . $rstScrapCode[$intIndex]['SCD_ID'] . '" currentValue="' . $rstScrapCode[$intIndex]['SCD_STATUS'] . '" onclick="deactivateScrapCode(' . $rstScrapCode[$intIndex]['SCD_ID'] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                } else {
                    $strGrid .= '<label id="lblDeactivate_' . $rstScrapCode[$intIndex]['SCD_ID'] . '" currentValue="' . $rstScrapCode[$intIndex]['SCD_STATUS'] . '" onclick="deactivateScrapCode(' . $rstScrapCode[$intIndex]['SCD_ID'] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                }
                $strGrid .= '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $strGrid .= '<label id="lblEdit_' . $rstScrapCode[$intIndex]['SCD_ID'] . '" scrapcodename="' . $rstScrapCode[$intIndex]['SCD_NAME'] . '" onclick="showModal(' . $rstScrapCode[$intIndex]['SCD_ID'] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
                $strGrid .= '</td>';
                $strGrid .= '</tr>';
                if ($intIndex == ($intSqlNumberOfRecords - 1)) {
                    break;
                }
            };
        }else{
            $strGrid .= '<tr><td class="tdGrid" style="text-align: center" colspan="' . $intSqlNumberOfColumns . '">No existen registros</td></tr>';
        }
        unset($rstScrapCode);
        $jsnData['grid'] = $strGrid;
        require_once('../../lib/scrap_grid/class.php');
        $objGrid = new clsGrid();
        $jsnData['pagination'] = $objGrid->gridPagination($intSqlPage,$intPages,$intSqlNumberOfRecords,$intSqlLimit);
        unset($objGrid);
        break;
    case 'insertScrapCode':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $strScrapCode = $_REQUEST['strScrapCode'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM SCD_DIVISION WHERE SCD_NAME = '" . $strScrapCode . "'";
        $rstScrapCodeCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstScrapCodeCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstScrapCodeCount[0]['COUNT'];
                $jsnData['strError'] = "La división " . $strScrapCode . " ya existe";
            }else{
                $strPlant = $_REQUEST['strSelectedPlant'];
                $arrPlant = explode("|",$strPlant);
                array_splice($arrPlant,count($arrPlant)-1);
                $strSql = "INSERT INTO SCD_DIVISION (SCD_NAME, SCD_STATUS) VALUES ('" . $strScrapCode . "',1) RETURNING SCD_ID INTO :intInsertedID";
                $objScrap->dbInsert($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $intScrapCodeId = $objScrap->getProperty('intLastInsertId');
                    for($intIndex=0;$intIndex<count($arrPlant);$intIndex++){
                        $strSql = "INSERT INTO SCD_DIVISION_PLANT (SCD_DIVISION,SCD_PLANT) VALUES (" . $intScrapCodeId . "," . $arrPlant[$intIndex] . ") RETURNING SCD_ID INTO :intInsertedID";
                        $objScrap->dbInsert($strSql);
                    }
                }else{
                    $jsnData['blnGo'] = 'false';
                    $jsnData['strError'] = $objScrap->getProperty('strDBError');
                }
            }
        }else{
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        unset($rstScrapCodeCount);
        break;
    case 'updateScrapCode':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $intScrapCodeId = $_REQUEST['intScrapCodeId'];
        $strScrapCode = $_REQUEST['strScrapCode'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM SCD_DIVISION WHERE SCD_NAME = '" . $strScrapCode . "' AND SCD_ID <> " . $intScrapCodeId;
        $rstScrapCodeCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstScrapCodeCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstScrapCodeCount[0]['COUNT'];
                $jsnData['strError'] = "La división " . $strScrapCode . " ya existe";
            }else{
                $strPlant = $_REQUEST['strSelectedPlant'];
                $arrPlant = explode("|",$strPlant);
                array_splice($arrPlant,count($arrPlant)-1);
                $strSql = "UPDATE SCD_DIVISION SET SCD_NAME = '" . $strScrapCode . "' WHERE SCD_ID = " . $intScrapCodeId;
                $objScrap->dbUpdate($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $strSql = "DELETE FROM SCD_DIVISION_PLANT WHERE SCD_DIVISION = " . $intScrapCodeId;
                    $objScrap->dbUpdate($strSql);
                    if($objScrap->getProperty('strDBError')=='') {
                        for ($intIndex = 0; $intIndex < count($arrPlant); $intIndex++) {
                            $strSql = "INSERT INTO SCD_DIVISION_PLANT (SCD_DIVISION,SCD_PLANT) VALUES (" . $intScrapCodeId . "," . $arrPlant[$intIndex] . ") RETURNING SCD_ID INTO :intInsertedID";
                            $objScrap->dbInsert($strSql);
                        }
                    }else{
                        $jsnData['blnGo'] = 'false';
                        $jsnData['strError'] = $objScrap->getProperty('strDBError');
                    }
                }else{
                    $jsnData['blnGo'] = 'false';
                    $jsnData['strError'] = $objScrap->getProperty('strDBError');
                }
            }
        }else{
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        unset($rstScrapCodeCount);
        break;

    case 'deactivateScrapCode':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $intScrapCodeId = $_REQUEST['intScrapCodeId'];
        $intStatus = $_REQUEST['intStatus'];
        $strSql = "UPDATE SCD_DIVISION SET SCD_STATUS = " . $intStatus . " WHERE SCD_ID = " . $intScrapCodeId;
        $objScrap->dbUpdate($strSql);
        if($objScrap->getProperty('strDBError')!=''){
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        break;
    case 'getCause':
        $jsnData = array();
        $strSql = "SELECT CAS_ID, CAS_NAME FROM CAS_CAUSE WHERE CAS_STATUS = 1 ORDER BY 2";
        $rstCause = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstCause as $objCause){
                $strHTML = '<tr><td id="tdCause_' . $objCause['CAS_ID'] . '" class="tdNonActive" onclick="switchSelected(' . $objCause['CAS_ID']. ')">&#10006</td><td>' . $objCause['CAS_NAME'] . '</td></tr>';
                array_push($jsnData,array('intCause'=>$objCause['CAS_ID'],'strHtml'=>$strHTML));
            };
        }
        unset($rstCause);
        break;
    case 'getPlantScrapCode':
        $jsnData = array();
        $intScrapCodeId = $_REQUEST['intScrapCodeId'];
        $strSql = "SELECT PLN_ID, CNT_CODE||'-'||PLN_NAME \"PLANT\" FROM PLN_PLANT ";
        $strSql .= "LEFT JOIN CNT_COUNTRY ON CNT_COUNTRY.CNT_ID = PLN_PLANT.PLN_COUNTRY ";
        $strSql .= "WHERE PLN_STATUS = 1 ";
        $strSql .= "ORDER BY 2";
        $rstPlant = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstPlant as $objPlant){
                $strSql = 'SELECT COUNT(*) AS "COUNT" FROM SCD_DIVISION_PLANT WHERE SCD_DIVISION = ' . $intScrapCodeId . ' AND SCD_PLANT = ' . $objPlant['PLN_ID'];
                $rstScrapCodePlant = $objScrap->dbQuery($strSql);
                if($rstScrapCodePlant[0]['COUNT']!=0){
                    $strHTML = '<tr><td id="tdPlant_' . $objPlant['PLN_ID'] . '" class="tdActive" onclick="switchSelected(' . $objPlant['PLN_ID']. ')">&#10004</td><td>' . $objPlant['PLANT'] . '</td></tr>';
                }else{
                    $strHTML = '<tr><td id="tdPlant_' . $objPlant['PLN_ID'] . '" class="tdNonActive" onclick="switchSelected(' . $objPlant['PLN_ID']. ')">&#10006</td><td>' . $objPlant['PLANT'] . '</td></tr>';
                }
                unset($rstScrapCodePlant);
                array_push($jsnData,array('intPlant'=>$objPlant['PLN_ID'],'strHtml'=>$strHTML));
            };
        }
        unset($rstPlant);
        break;
};
unset($objScrap);
echo json_encode($jsnData);
?>