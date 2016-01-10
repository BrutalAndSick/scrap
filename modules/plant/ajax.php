<?php
ini_set("display_errors",1);

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
                $strGrid .= '<tr id="trGrid_' . $rstData[$intIndex]['PLN_ID'] . '">';
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstData[$intIndex]['PLN_ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstData[$intIndex]['PLN_NAME'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center">' . $rstData[$intIndex]['CNT_CODE'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: right">' . $rstData[$intIndex]['PLN_NUMBER'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                if ($rstData[$intIndex]['PLN_STATUS'] == 1) {
                    $strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex]['PLN_ID'] . '" currentValue="' . $rstData[$intIndex]['PLN_STATUS'] . '" onclick="deactivatePlant(' . $rstData[$intIndex]['PLN_ID'] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                } else {
                    $strGrid .= '<label id="lblDeactivate_' . $rstData[$intIndex]['PLN_ID'] . '" currentValue="' . $rstData[$intIndex]['PLN_STATUS'] . '" onclick="deactivatePlant(' . $rstData[$intIndex]['PLN_ID'] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                }
                $strGrid .= '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $strGrid .= '<label id="lblEdit_' . $rstData[$intIndex]['PLN_ID'] . '" plantname="' . $rstData[$intIndex]['PLN_NAME'] . '" onclick="showModal(' . $rstData[$intIndex]['PLN_ID'] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
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
    case 'getCountry':
        $jsnData = array('blnGo'=>'true','strHTML'=>0,'strError'=>'');
        $strSql = "SELECT CNT_ID, CNT_CODE||' - '||CNT_NAME \"COUNTRY\" FROM CNT_COUNTRY ORDER BY 2";
        $rstData = $objScrap->dbQuery($strSql);
        $strHTML = "";
        if($objScrap->getProperty('strDBError')==''){
            if($objScrap->getProperty('intAffectedRows')>1){
                $strHTML .= '<option value="-1">- Pais - </option>';
            }
            foreach ($rstData as $objData){
                $strHTML .= '<option value="' . $objData['CNT_ID'] . '">' . $objData['COUNTRY'] . '</option>';
            }
            $jsnData['strHTML'] = $strHTML;
        }else{
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        unset($rstData);
        break;
    case 'getSelectedCountry':
        $jsnData = array('blnGo'=>'true','strHTML'=>0,'strError'=>'');
        $intPlant = $_REQUEST['intPlant'];
        $strSql = "SELECT PLN_COUNTRY FROM PLN_PLANT WHERE PLN_ID = " . $intPlant;
        $rstData = $objScrap->dbQuery($strSql);
        $intCountry = $rstData[0]['PLN_COUNTRY'];
        unset($rstData);
        $strSql = "SELECT CNT_ID, CNT_CODE||' - '||CNT_NAME \"COUNTRY\" FROM CNT_COUNTRY ORDER BY 2";
        $rstData = $objScrap->dbQuery($strSql);
        $strHTML = "";
        if($objScrap->getProperty('strDBError')==''){
            foreach ($rstData as $objData){
                $strHTML .= '<option value="' . $objData['CNT_ID'] . '"';
                if($objData['CNT_ID']==$intCountry){
                    $strHTML .= ' selected="selected"';
                }
                $strHTML .= '>' . $objData['COUNTRY'] . '</option>';
            }
            $jsnData['strHTML'] = $strHTML;
        }else{
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        unset($rstData);
        break;
    case 'insertPlant':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $strPlant = $_REQUEST['strPlant'];
        $intCountry = $_REQUEST['intCountry'];
        $intNumber = $_REQUEST['intNumber'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM PLN_PLANT WHERE PLN_NAME = '" . $strPlant . "'";
        $rstCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                $jsnData['strError'] = "La planta " . $strPlant . " ya existe";
            }else{
                $strSql = "INSERT INTO PLN_PLANT(PLN_NAME,PLN_COUNTRY,PLN_NUMBER,PLN_STATUS) VALUES ('" . $strPlant . "'," . $intCountry . "," . $intNumber . ",1) RETURNING PLN_ID INTO :intInsertedID";
                $objScrap->dbInsert($strSql);
                if($objScrap->getProperty('strDBError')!='') {
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
    case 'updatePlant':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $intPlantId = $_REQUEST['intPlant'];
        $strPlant = $_REQUEST['strPlant'];
        $intCountry = $_REQUEST['intCountry'];
        $intNumber = $_REQUEST['intNumber'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM PLN_PLANT WHERE PLN_NAME = '" . $strPlant . "' AND PLN_ID <> " . $intPlantId;
        $rstCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstCount[0]['COUNT'];
                $jsnData['strError'] = "La planta " . $strPlant . " ya existe";
            }else{
                $strSql = "UPDATE PLN_PLANT SET PLN_NAME = '" . $strPlant . "', PLN_COUNTRY = '" . $intCountry . "', PLN_NUMBER = " . $intNumber . " WHERE PLN_ID = " . $intPlantId;
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
    case 'deactivatePlant':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $intPlantId = $_REQUEST['intPlantId'];
        $intStatus = $_REQUEST['intStatus'];
        $strSql = "UPDATE PLN_PLANT SET PLN_STATUS = " . $intStatus . " WHERE PLN_ID = " . $intPlantId;
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