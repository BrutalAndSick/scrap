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
        $rstProfitCenter = $objScrap->dbQuery($strSql . $strSqlOrder);
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
                $strGrid .= '<tr id="trGrid_' . $rstProfitCenter[$intIndex]['PRT_ID'] . '">';
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstProfitCenter[$intIndex]['PRT_ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstProfitCenter[$intIndex]['PRT_NAME'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                if ($rstProfitCenter[$intIndex]['PRT_STATUS'] == 1) {
                    $strGrid .= '<label id="lblDeactivate_' . $rstProfitCenter[$intIndex]['PRT_ID'] . '" currentValue="' . $rstProfitCenter[$intIndex]['PRT_STATUS'] . '" onclick="deactivateProfitCenter(' . $rstProfitCenter[$intIndex]['PRT_ID'] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                } else {
                    $strGrid .= '<label id="lblDeactivate_' . $rstProfitCenter[$intIndex]['PRT_ID'] . '" currentValue="' . $rstProfitCenter[$intIndex]['PRT_STATUS'] . '" onclick="deactivateProfitCenter(' . $rstProfitCenter[$intIndex]['PRT_ID'] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                }
                $strGrid .= '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $strGrid .= '<label id="lblEdit_' . $rstProfitCenter[$intIndex]['PRT_ID'] . '" profitcentername="' . $rstProfitCenter[$intIndex]['PRT_NAME'] . '" onclick="showModal(' . $rstProfitCenter[$intIndex]['PRT_ID'] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
                $strGrid .= '</td>';
                $strGrid .= '</tr>';
                if ($intIndex == ($intSqlNumberOfRecords - 1)) {
                    break;
                }
            };
        }else{
            $strGrid .= '<tr><td class="tdGrid" style="text-align: center" colspan="' . $intSqlNumberOfColumns . '">No existen registros</td></tr>';
        }
        unset($rstProfitCenter);
        $jsnData['grid'] = $strGrid;
        require_once('../../lib/scrap_grid/class.php');
        $objGrid = new clsGrid();
        $jsnData['pagination'] = $objGrid->gridPagination($intSqlPage,$intPages,$intSqlNumberOfRecords,$intSqlLimit);
        unset($objGrid);
        break;
    case 'insertProfitCenter':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $strProfitCenter = $_REQUEST['strProfitCenter'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM PRT_PROFITCENTER WHERE PRT_NAME = '" . $strProfitCenter . "'";
        $rstProfitCenterCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstProfitCenterCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfitCenterCount[0]['COUNT'];
                $jsnData['strError'] = "El centro de costos " . $strProfitCenter . " ya existe";
            }else{
                $strSegment = $_REQUEST['strSelectedSegment'];
                $arrSegment = explode("|",$strSegment);
                array_splice($arrSegment,count($arrSegment)-1);
                $strSql = "INSERT INTO PRT_PROFITCENTER (PRT_NAME, PRT_STATUS) VALUES ('" . $strProfitCenter . "',1) RETURNING PRT_ID INTO :intInsertedID";
                $objScrap->dbInsert($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $intProfitCenterId = $objScrap->getProperty('intLastInsertId');
                    for($intIndex=0;$intIndex<count($arrSegment);$intIndex++){
                        $strSql = "INSERT INTO PRT_PROFITCENTER_SEGMENT (PRT_PROFITCENTER,PRT_SEGMENT) VALUES (" . $intProfitCenterId . "," . $arrSegment[$intIndex] . ") RETURNING PRT_ID INTO :intInsertedID";
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
        unset($rstProfitCenterCount);
        break;
    case 'updateProfitCenter':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $intProfitCenterId = $_REQUEST['intProfitCenterId'];
        $strProfitCenter = $_REQUEST['strProfitCenter'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM PRT_PROFITCENTER WHERE PRT_NAME = '" . $strProfitCenter . "' AND PRT_ID <> " . $intProfitCenterId;
        $rstProfitCenterCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstProfitCenterCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfitCenterCount[0]['COUNT'];
                $jsnData['strError'] = "El centro de costos " . $strProfitCenter . " ya existe";
            }else{
                $strSegment = $_REQUEST['strSelectedSegment'];
                $arrSegment = explode("|",$strSegment);
                array_splice($arrSegment,count($arrSegment)-1);
                $strSql = "UPDATE PRT_PROFITCENTER SET PRT_NAME = '" . $strProfitCenter . "' WHERE PRT_ID = " . $intProfitCenterId;
                $objScrap->dbUpdate($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $strSql = "DELETE FROM PRT_PROFITCENTER_SEGMENT WHERE PRT_PROFITCENTER = " . $intProfitCenterId;
                    $objScrap->dbUpdate($strSql);
                    if($objScrap->getProperty('strDBError')=='') {
                        for ($intIndex = 0; $intIndex < count($arrSegment); $intIndex++) {
                            $strSql = "INSERT INTO PRT_PROFITCENTER_SEGMENT (PRT_PROFITCENTER,PRT_SEGMENT) VALUES (" . $intProfitCenterId . "," . $arrSegment[$intIndex] . ") RETURNING PRT_ID INTO :intInsertedID";
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
        unset($rstProfitCenterCount);
        break;
    case 'deactivateProfitCenter':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $intProfitCenterId = $_REQUEST['intProfitCenterId'];
        $intStatus = $_REQUEST['intStatus'];
        $strSql = "UPDATE PRT_PROFITCENTER SET PRT_STATUS = " . $intStatus . " WHERE PRT_ID = " . $intProfitCenterId;
        $objScrap->dbUpdate($strSql);
        if($objScrap->getProperty('strDBError')!=''){
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        break;
    case 'getSegment':
        $jsnData = array();
        $strSql = "SELECT SGM_ID, SGM_NAME  FROM SGM_SEGMENT ";
        $strSql .= "WHERE SGM_STATUS = 1 ";
        $strSql .= "ORDER BY 2";
        $rstSegment = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstSegment as $objSegment){
                $strHTML = '<tr><td id="tdSegment_' . $objSegment['SGM_ID'] . '" class="tdNonActive" onclick="switchSelected(' . $objSegment['SGM_ID']. ')">&#10006</td><td>' . $objSegment['SGM_NAME'] . '</td></tr>';
                array_push($jsnData,array('intSegment'=>$objSegment['SGM_ID'],'strHtml'=>$strHTML));
            };
        }
        unset($rstSegment);
        break;
    case 'getSegmentProfitCenter':
        $jsnData = array();
        $intProfitCenterId = $_REQUEST['intProfitCenterId'];
        $strSql = "SELECT SGM_ID, SGM_NAME  FROM SGM_SEGMENT ";
        $strSql .= "WHERE SGM_STATUS = 1 ";
        $strSql .= "ORDER BY 2";
        $rstSegment = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstSegment as $objSegment){
                $strSql = 'SELECT COUNT(*) AS "COUNT" FROM PRT_PROFITCENTER_SEGMENT WHERE PRT_PROFITCENTER = ' . $intProfitCenterId . ' AND PRT_SEGMENT = ' . $objSegment['SGM_ID'];
                $rstSegmentSegment = $objScrap->dbQuery($strSql);
                if($rstSegmentSegment[0]['COUNT']!=0){
                    $strHTML = '<tr><td id="tdSegment_' . $objSegment['SGM_ID'] . '" class="tdActive" onclick="switchSelected(' . $objSegment['SGM_ID']. ')">&#10004</td><td>' . $objSegment['SGM_NAME'] . '</td></tr>';
                }else{
                    $strHTML = '<tr><td id="tdSegment_' . $objSegment['SGM_ID'] . '" class="tdNonActive" onclick="switchSelected(' . $objSegment['SGM_ID']. ')">&#10006</td><td>' . $objSegment['SGM_NAME'] . '</td></tr>';
                }
                unset($rstSegmentPlant);
                array_push($jsnData,array('intSegment'=>$objSegment['SGM_ID'],'strHtml'=>$strHTML));
            };
        }
        unset($rstSegment);
        break;
};
unset($objScrap);
echo json_encode($jsnData);
?>