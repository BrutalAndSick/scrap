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
        $rstDivision = $objScrap->dbQuery($strSql . $strSqlOrder);
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
                $strGrid .= '<tr id="trGrid_' . $rstDivision[$intIndex]['DVS_ID'] . '">';
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstDivision[$intIndex]['DVS_ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstDivision[$intIndex]['DVS_NAME'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                if ($rstDivision[$intIndex]['DVS_STATUS'] == 1) {
                    $strGrid .= '<label id="lblDeactivate_' . $rstDivision[$intIndex]['DVS_ID'] . '" currentValue="' . $rstDivision[$intIndex]['DVS_STATUS'] . '" onclick="deactivateDivision(' . $rstDivision[$intIndex]['DVS_ID'] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                } else {
                    $strGrid .= '<label id="lblDeactivate_' . $rstDivision[$intIndex]['DVS_ID'] . '" currentValue="' . $rstDivision[$intIndex]['DVS_STATUS'] . '" onclick="deactivateDivision(' . $rstDivision[$intIndex]['DVS_ID'] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                }
                $strGrid .= '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $strGrid .= '<label id="lblEdit_' . $rstDivision[$intIndex]['DVS_ID'] . '" divisionname="' . $rstDivision[$intIndex]['DVS_NAME'] . '" onclick="showModal(' . $rstDivision[$intIndex]['DVS_ID'] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
                $strGrid .= '</td>';
                $strGrid .= '</tr>';
                if ($intIndex == ($intSqlNumberOfRecords - 1)) {
                    break;
                }
            };
        }else{
            $strGrid .= '<tr><td class="tdGrid" style="text-align: center" colspan="' . $intSqlNumberOfColumns . '">No existen registros</td></tr>';
        }
        unset($rstDivision);
        $jsnData['grid'] = $strGrid;
        require_once('../../lib/scrap_grid/class.php');
        $objGrid = new clsGrid();
        $jsnData['pagination'] = $objGrid->gridPagination($intSqlPage,$intPages,$intSqlNumberOfRecords,$intSqlLimit);
        unset($objGrid);
        break;
    case 'insertDivision':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $strDivision = $_REQUEST['strDivision'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM DVS_DIVISION WHERE DVS_NAME = '" . $strDivision . "'";
        $rstDivisionCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstDivisionCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstDivisionCount[0]['COUNT'];
                $jsnData['strError'] = "La división " . $strDivision . " ya existe";
            }else{
                $strPlant = $_REQUEST['strSelectedPlant'];
                $arrPlant = explode("|",$strPlant);
                array_splice($arrPlant,count($arrPlant)-1);
                $strSql = "INSERT INTO DVS_DIVISION (DVS_NAME, DVS_STATUS) VALUES ('" . $strDivision . "',1) RETURNING DVS_ID INTO :intInsertedID";
                $objScrap->dbInsert($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $intDivisionId = $objScrap->getProperty('intLastInsertId');
                    for($intIndex=0;$intIndex<count($arrPlant);$intIndex++){
                        $strSql = "INSERT INTO DVS_DIVISION_PLANT (DVS_DIVISION,DVS_PLANT) VALUES (" . $intDivisionId . "," . $arrPlant[$intIndex] . ") RETURNING DVS_ID INTO :intInsertedID";
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
        unset($rstDivisionCount);
        break;
    case 'updateDivision':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $intDivisionId = $_REQUEST['intDivisionId'];
        $strDivision = $_REQUEST['strDivision'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM DVS_DIVISION WHERE DVS_NAME = '" . $strDivision . "' AND DVS_ID <> " . $intDivisionId;
        $rstDivisionCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstDivisionCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstDivisionCount[0]['COUNT'];
                $jsnData['strError'] = "La división " . $strDivision . " ya existe";
            }else{
                $strPlant = $_REQUEST['strSelectedPlant'];
                $arrPlant = explode("|",$strPlant);
                array_splice($arrPlant,count($arrPlant)-1);
                $strSql = "UPDATE DVS_DIVISION SET DVS_NAME = '" . $strDivision . "' WHERE DVS_ID = " . $intDivisionId;
                $objScrap->dbUpdate($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $strSql = "DELETE FROM DVS_DIVISION_PLANT WHERE DVS_DIVISION = " . $intDivisionId;
                    $objScrap->dbUpdate($strSql);
                    if($objScrap->getProperty('strDBError')=='') {
                        for ($intIndex = 0; $intIndex < count($arrPlant); $intIndex++) {
                            $strSql = "INSERT INTO DVS_DIVISION_PLANT (DVS_DIVISION,DVS_PLANT) VALUES (" . $intDivisionId . "," . $arrPlant[$intIndex] . ") RETURNING DVS_ID INTO :intInsertedID";
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
        unset($rstDivisionCount);
        break;

    case 'deactivateDivision':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $intDivisionId = $_REQUEST['intDivisionId'];
        $intStatus = $_REQUEST['intStatus'];
        $strSql = "UPDATE DVS_DIVISION SET DVS_STATUS = " . $intStatus . " WHERE DVS_ID = " . $intDivisionId;
        $objScrap->dbUpdate($strSql);
        if($objScrap->getProperty('strDBError')!=''){
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        break;
    case 'getPlant':
        $jsnData = array();
        $strSql = "SELECT PLN_ID, CNT_CODE||'-'||PLN_NAME \"PLANT\" FROM PLN_PLANT ";
        $strSql .= "LEFT JOIN CNT_COUNTRY ON CNT_COUNTRY.CNT_ID = PLN_PLANT.PLN_COUNTRY ";
        $strSql .= "WHERE PLN_STATUS = 1 ";
        $strSql .= "ORDER BY 2";
        $rstPlant = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstPlant as $objPlant){
                $strHTML = '<tr><td id="tdPlant_' . $objPlant['PLN_ID'] . '" class="tdNonActive" onclick="switchSelected(' . $objPlant['PLN_ID']. ')">&#10006</td><td>' . $objPlant['PLANT'] . '</td></tr>';
                array_push($jsnData,array('intPlant'=>$objPlant['PLN_ID'],'strHtml'=>$strHTML));
            };
        }
        unset($rstPlant);
        break;
    case 'getPlantDivision':
        $jsnData = array();
        $intDivisionId = $_REQUEST['intDivisionId'];
        $strSql = "SELECT PLN_ID, CNT_CODE||'-'||PLN_NAME \"PLANT\" FROM PLN_PLANT ";
        $strSql .= "LEFT JOIN CNT_COUNTRY ON CNT_COUNTRY.CNT_ID = PLN_PLANT.PLN_COUNTRY ";
        $strSql .= "WHERE PLN_STATUS = 1 ";
        $strSql .= "ORDER BY 2";
        $rstPlant = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstPlant as $objPlant){
                $strSql = 'SELECT COUNT(*) AS "COUNT" FROM DVS_DIVISION_PLANT WHERE DVS_DIVISION = ' . $intDivisionId . ' AND DVS_PLANT = ' . $objPlant['PLN_ID'];
                $rstDivisionPlant = $objScrap->dbQuery($strSql);
                if($rstDivisionPlant[0]['COUNT']!=0){
                    $strHTML = '<tr><td id="tdPlant_' . $objPlant['PLN_ID'] . '" class="tdActive" onclick="switchSelected(' . $objPlant['PLN_ID']. ')">&#10004</td><td>' . $objPlant['PLANT'] . '</td></tr>';
                }else{
                    $strHTML = '<tr><td id="tdPlant_' . $objPlant['PLN_ID'] . '" class="tdNonActive" onclick="switchSelected(' . $objPlant['PLN_ID']. ')">&#10006</td><td>' . $objPlant['PLANT'] . '</td></tr>';
                }
                unset($rstDivisionPlant);
                array_push($jsnData,array('intPlant'=>$objPlant['PLN_ID'],'strHtml'=>$strHTML));
            };
        }
        unset($rstPlant);
        break;
};
unset($objScrap);
echo json_encode($jsnData);
?>