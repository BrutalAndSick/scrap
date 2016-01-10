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
        $rstSegment = $objScrap->dbQuery($strSql . $strSqlOrder);
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
                $strGrid .= '<tr id="trGrid_' . $rstSegment[$intIndex]['SGM_ID'] . '">';
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstSegment[$intIndex]['SGM_ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstSegment[$intIndex]['SGM_NAME'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                if ($rstSegment[$intIndex]['SGM_STATUS'] == 1) {
                    $strGrid .= '<label id="lblDeactivate_' . $rstSegment[$intIndex]['SGM_ID'] . '" currentValue="' . $rstSegment[$intIndex]['SGM_STATUS'] . '" onclick="deactivateSegment(' . $rstSegment[$intIndex]['SGM_ID'] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                } else {
                    $strGrid .= '<label id="lblDeactivate_' . $rstSegment[$intIndex]['SGM_ID'] . '" currentValue="' . $rstSegment[$intIndex]['SGM_STATUS'] . '" onclick="deactivateSegment(' . $rstSegment[$intIndex]['SGM_ID'] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                }
                $strGrid .= '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $strGrid .= '<label id="lblEdit_' . $rstSegment[$intIndex]['SGM_ID'] . '" segmentname="' . $rstSegment[$intIndex]['SGM_NAME'] . '" onclick="showModal(' . $rstSegment[$intIndex]['SGM_ID'] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
                $strGrid .= '</td>';
                $strGrid .= '</tr>';
                if ($intIndex == ($intSqlNumberOfRecords - 1)) {
                    break;
                }
            };
        }else{
            $strGrid .= '<tr><td class="tdGrid" style="text-align: center" colspan="' . $intSqlNumberOfColumns . '">No existen registros</td></tr>';
        }
        unset($rstSegment);
        $jsnData['grid'] = $strGrid;
        require_once('../../lib/scrap_grid/class.php');
        $objGrid = new clsGrid();
        $jsnData['pagination'] = $objGrid->gridPagination($intSqlPage,$intPages,$intSqlNumberOfRecords,$intSqlLimit);
        unset($objGrid);
        break;
    case 'insertSegment':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $strSegment = $_REQUEST['strSegment'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM SGM_SEGMENT WHERE SGM_NAME = '" . $strSegment . "'";
        $rstSegmentCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstSegmentCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstSegmentCount[0]['COUNT'];
                $jsnData['strError'] = "El segmento " . $strSegment . " ya existe";
            }else{
                $strDivision = $_REQUEST['strSelectedDivision'];
                $arrDivision = explode("|",$strDivision);
                array_splice($arrDivision,count($arrDivision)-1);
                $strSql = "INSERT INTO SGM_SEGMENT (SGM_NAME, SGM_STATUS) VALUES ('" . $strSegment . "',1) RETURNING SGM_ID INTO :intInsertedID";
                $objScrap->dbInsert($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $intSegmentId = $objScrap->getProperty('intLastInsertId');
                    for($intIndex=0;$intIndex<count($arrDivision);$intIndex++){
                        $strSql = "INSERT INTO SGM_SEGMENT_DIVISION (SGM_SEGMENT,SGM_DIVISION) VALUES (" . $intSegmentId . "," . $arrDivision[$intIndex] . ") RETURNING SGM_ID INTO :intInsertedID";
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
        unset($rstSegmentCount);
        break;
    case 'updateSegment':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $intSegmentId = $_REQUEST['intSegmentId'];
        $strSegment = $_REQUEST['strSegment'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM SGM_SEGMENT WHERE SGM_NAME = '" . $strSegment . "' AND SGM_ID <> " . $intSegmentId;
        $rstSegmentCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstSegmentCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstSegmentCount[0]['COUNT'];
                $jsnData['strError'] = "El segmento " . $strSegment . " ya existe";
            }else{
                $strDivision = $_REQUEST['strSelectedDivision'];
                $arrDivision = explode("|",$strDivision);
                array_splice($arrDivision,count($arrDivision)-1);
                $strSql = "UPDATE SGM_SEGMENT SET SGM_NAME = '" . $strSegment . "' WHERE SGM_ID = " . $intSegmentId;
                $objScrap->dbUpdate($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $strSql = "DELETE FROM SGM_SEGMENT_DIVISION WHERE SGM_SEGMENT = " . $intSegmentId;
                    $objScrap->dbUpdate($strSql);
                    if($objScrap->getProperty('strDBError')=='') {
                        for ($intIndex = 0; $intIndex < count($arrDivision); $intIndex++) {
                            $strSql = "INSERT INTO SGM_SEGMENT_DIVISION (SGM_SEGMENT,SGM_DIVISION) VALUES (" . $intSegmentId . "," . $arrDivision[$intIndex] . ") RETURNING SGM_ID INTO :intInsertedID";
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
        unset($rstSegmentCount);
        break;
    case 'deactivateSegment':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $intSegmentId = $_REQUEST['intSegmentId'];
        $intStatus = $_REQUEST['intStatus'];
        $strSql = "UPDATE SGM_SEGMENT SET SGM_STATUS = " . $intStatus . " WHERE SGM_ID = " . $intSegmentId;
        $objScrap->dbUpdate($strSql);
        if($objScrap->getProperty('strDBError')!=''){
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        break;
    case 'getDivision':
        $jsnData = array();
        $strSql = "SELECT DVS_ID, DVS_NAME  FROM DVS_DIVISION ";
        $strSql .= "WHERE DVS_STATUS = 1 ";
        $strSql .= "ORDER BY 2";
        $rstDivision = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstDivision as $objDivision){
                $strHTML = '<tr><td id="tdDivision_' . $objDivision['DVS_ID'] . '" class="tdNonActive" onclick="switchSelected(' . $objDivision['DVS_ID']. ')">&#10006</td><td>' . $objDivision['DVS_NAME'] . '</td></tr>';
                array_push($jsnData,array('intDivision'=>$objDivision['DVS_ID'],'strHtml'=>$strHTML));
            };
        }
        unset($rstDivision);
        break;
    case 'getDivisionSegment':
        $jsnData = array();
        $intSegmentId = $_REQUEST['intSegmentId'];
        $strSql = "SELECT DVS_ID, DVS_NAME  FROM DVS_DIVISION ";
        $strSql .= "WHERE DVS_STATUS = 1 ";
        $strSql .= "ORDER BY 2";
        $rstDivision = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstDivision as $objDivision){
                $strSql = 'SELECT COUNT(*) AS "COUNT" FROM SGM_SEGMENT_DIVISION WHERE SGM_SEGMENT = ' . $intSegmentId . ' AND SGM_DIVISION = ' . $objDivision['DVS_ID'];
                $rstDivisionSegment = $objScrap->dbQuery($strSql);
                if($rstDivisionSegment[0]['COUNT']!=0){
                    $strHTML = '<tr><td id="tdDivision_' . $objDivision['DVS_ID'] . '" class="tdActive" onclick="switchSelected(' . $objDivision['DVS_ID']. ')">&#10004</td><td>' . $objDivision['DVS_NAME'] . '</td></tr>';
                }else{
                    $strHTML = '<tr><td id="tdDivision_' . $objDivision['DVS_ID'] . '" class="tdNonActive" onclick="switchSelected(' . $objDivision['DVS_ID']. ')">&#10006</td><td>' . $objDivision['DVS_NAME'] . '</td></tr>';
                }
                unset($rstDivisionPlant);
                array_push($jsnData,array('intDivision'=>$objDivision['DVS_ID'],'strHtml'=>$strHTML));
            };
        }
        unset($rstPlant);
        break;
};
unset($objScrap);
echo json_encode($jsnData);
?>