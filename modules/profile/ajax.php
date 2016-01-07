<?php
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('../../lib/scrap.php');
$objScrap = new clsScrap();
$strProcess = $_REQUEST['strProcess'];
switch ($strProcess){
    case 'insertProfile':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $strProfile = $_REQUEST['strProfile'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM PRF_PROFILE WHERE PRF_NAME = '" . $strProfile . "'";
        $rstProfileCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstProfileCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                $jsnData['strError'] = "El perfil " . $strProfile . " ya existe";
            }else{
                $strMenu = $_REQUEST['strSelectedMenu'];
                $arrMenu = explode("|",$strMenu);
                array_splice($arrMenu,count($arrMenu)-1);
                $strSql = "INSERT INTO PRF_PROFILE (PRF_NAME, PRF_STATUS) VALUES ('" . $strProfile . "',1) RETURNING PRF_ID INTO :intInsertedID";
                $objScrap->dbInsert($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $intProfileId = $objScrap->getProperty('intLastInsertId');
                    for($intIndex=0;$intIndex<count($arrMenu);$intIndex++){
                        $strSql = "INSERT INTO PRF_PROFILE_MENU (PRF_PROFILE,PRF_MENU) VALUES (" . $intProfileId . "," . $arrMenu[$intIndex] . ") RETURNING PRF_ID INTO :intInsertedID";
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
        unset($rstProfileCount);
        break;
    case 'updateProfile':
        $jsnData = array('blnGo'=>'true','intCount'=>0,'strError'=>'');
        $intProfileId = $_REQUEST['intProfileId'];
        $strProfile = $_REQUEST['strProfile'];
        $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM PRF_PROFILE WHERE PRF_NAME = '" . $strProfile . "' AND PRF_ID <> " . $intProfileId;
        $rstProfileCount = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')==''){
            if($rstProfileCount[0]['COUNT']!=0){
                $jsnData['blnGo'] = 'false';
                $jsnData['intCount'] = $rstProfileCount[0]['COUNT'];
                $jsnData['strError'] = "El perfil " . $strProfile . " ya existe";
            }else{
                $strMenu = $_REQUEST['strSelectedMenu'];
                $arrMenu = explode("|",$strMenu);
                array_splice($arrMenu,count($arrMenu)-1);
                $strSql = "UPDATE PRF_PROFILE SET PRF_NAME = '" . $strProfile . "' WHERE PRF_ID = " . $intProfileId;
                $objScrap->dbUpdate($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    $strSql = "DELETE FROM PRF_PROFILE_MENU WHERE PRF_PROFILE = " . $intProfileId;
                    $objScrap->dbUpdate($strSql);
                    if($objScrap->getProperty('strDBError')=='') {
                        for ($intIndex = 0; $intIndex < count($arrMenu); $intIndex++) {
                            $strSql = "INSERT INTO PRF_PROFILE_MENU (PRF_PROFILE,PRF_MENU) VALUES (" . $intProfileId . "," . $arrMenu[$intIndex] . ") RETURNING PRF_ID INTO :intInsertedID";
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
        unset($rstProfileCount);
        break;
    case 'deactivateProfile':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $intProfileId = $_REQUEST['intProfileId'];
        $intStatus = $_REQUEST['intStatus'];
        $strSql = "UPDATE PRF_PROFILE SET PRF_STATUS = " . $intStatus . " WHERE PRF_ID = " . $intProfileId;
        $objScrap->dbUpdate($strSql);
        if($objScrap->getProperty('strDBError')!=''){
            $jsnData['blnGo'] = 'false';
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        break;
    case 'updateGrid':
        $jsnData = array('grid'=>'','pagination'=>'','intSqlNumberOfRecords'=>0);
        $strSql = $_REQUEST['strSql'];
        $strSqlOrder = $_REQUEST['strSqlOrder'];
        $intSqlPage = $_REQUEST['intSqlPage'];
        $intSqlLimit = $_REQUEST['intSqlLimit'];
        $intSqlNumberOfColumns = $_REQUEST['intSqlNumberOfColumns'];
        $rstProfile = $objScrap->dbQuery($strSql . $strSqlOrder);
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
                $strGrid .= '<tr id="trGrid_' . $rstProfile[$intIndex]['PRF_ID'] . '">';
                $strGrid .= '<td class="tdGrid" style="text-align: right;">' . $rstProfile[$intIndex]['PRF_ID'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: left">' . $rstProfile[$intIndex]['PRF_NAME'] . '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                if ($rstProfile[$intIndex]['PRF_STATUS'] == 1) {
                    $strGrid .= '<label id="lblDeactivateProfile_' . $rstProfile[$intIndex]['PRF_ID'] . '" currentValue="' . $rstProfile[$intIndex]['PRF_STATUS'] . '" onclick="deactivateProfile(' . $rstProfile[$intIndex]['PRF_ID'] . ');" class="labelActions labelActionsGreen">&#10004;</label>';
                } else {
                    $strGrid .= '<label id="lblDeactivateProfile_' . $rstProfile[$intIndex]['PRF_ID'] . '" currentValue="' . $rstProfile[$intIndex]['PRF_STATUS'] . '" onclick="deactivateProfile(' . $rstProfile[$intIndex]['PRF_ID'] . ');" class="labelActions labelActionsRed">&#10006;</label>';
                }
                $strGrid .= '</td>';
                $strGrid .= '<td class="tdGrid" style="text-align: center;">';
                $strGrid .= '<label id="lblEditProfile_' . $rstProfile[$intIndex]['PRF_ID'] . '" profilename="' . $rstProfile[$intIndex]['PRF_NAME'] . '" onclick="showModal(' . $rstProfile[$intIndex]['PRF_ID'] . ');" class="labelActions labelActionsOrange">&#9998;</label>';
                $strGrid .= '</td>';
                $strGrid .= '</tr>';
                if ($intIndex == ($intSqlNumberOfRecords - 1)) {
                    break;
                }
            };
        }else{
            $strGrid .= '<tr><td class="tdGrid" style="text-align: center" colspan="' . $intSqlNumberOfColumns . '">No existen registros</td></tr>';
        }
        unset($rstProfile);
        $jsnData['grid'] = $strGrid;
        require_once('../../lib/scrap_grid/class.php');
        $objGrid = new clsGrid();
        $jsnData['pagination'] = $objGrid->gridPagination($intSqlPage,$intPages,$intSqlNumberOfRecords,$intSqlLimit);
        unset($objGrid);
        break;
    case 'getMenu':
        $jsnData = array();
        $strSql = "SELECT * FROM MNU_MENU WHERE MNU_PARENT = 0 AND MNU_STATUS = 1 ORDER BY MNU_ORDER, MNU_NAME";
        $rstCategory = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstCategory as $objCategory){
                $strSql = "SELECT * FROM MNU_MENU WHERE MNU_PARENT = " . $objCategory['MNU_ID'] . " AND MNU_STATUS = 1 ORDER BY MNU_ORDER, MNU_NAME";
                $rstMenu = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
                    $arrMenu = array();
                    foreach($rstMenu as $objMenu){
                        array_push($arrMenu,array('id'=>$objMenu['MNU_ID'],'name'=>$objMenu['MNU_NAME']));
                    }
                    array_push($jsnData,array('id'=>$objCategory['MNU_ID'],'name'=>$objCategory['MNU_NAME'],'menu'=>$arrMenu));
                    unset($objMenu);
                }
                unset($rstMenu);
            };
            unset($objCategory);
        }
        unset($rstCategory);
        break;
    case 'getMenuProfile':
        $jsnData = array();
        $intProfileId = $_REQUEST['intProfileId'];
        $strSql = "SELECT * FROM MNU_MENU WHERE MNU_PARENT = 0 AND MNU_STATUS = 1 ORDER BY MNU_ORDER, MNU_NAME";
        $rstCategory = $objScrap->dbQuery($strSql);
        if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
            foreach($rstCategory as $objCategory){
                $strSql = "SELECT * FROM MNU_MENU WHERE MNU_PARENT = " . $objCategory['MNU_ID'] . " AND MNU_STATUS = 1 ORDER BY MNU_ORDER, MNU_NAME";
                $rstMenu = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('strDBError')=='' && $objScrap->getProperty('intAffectedRows')>0){
                    $arrMenu = array();
                    foreach($rstMenu as $objMenu){
                        $strSql = 'SELECT COUNT(*) AS "COUNT" FROM SCRAP.PRF_PROFILE_MENU WHERE PRF_PROFILE = ' . $intProfileId . ' AND PRF_MENU = ' . $objMenu['MNU_ID'];
                        $rstMenuProfile = $objScrap->dbQuery($strSql);
                        array_push($arrMenu,array('id'=>$objMenu['MNU_ID'],'name'=>$objMenu['MNU_NAME'],'selected'=>$rstMenuProfile[0]['COUNT']));
                        unset($rstMenuProfile);
                    }
                    array_push($jsnData,array('id'=>$objCategory['MNU_ID'],'name'=>$objCategory['MNU_NAME'],'menu'=>$arrMenu));
                    unset($objMenu);
                }
                unset($rstMenu);
            };
            unset($objCategory);
        }
        unset($rstCategory);
        break;
};
unset($objScrap);
echo json_encode($jsnData);
?>