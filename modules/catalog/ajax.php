<?php
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('../../lib/scrap.php');
$objScrap = new clsScrap();
$objScrap->intTableId = $_REQUEST['intTableId'];
$objScrap->getTableData();
$strProcess = $_REQUEST['strProcess'];
switch ($strProcess){
    case 'updateGrid':
        $jsnData = array('grid'=>'','pagination'=>'','intSqlNumberOfRecords'=>0);
        $objScrap->strGridSqlOrder = $_REQUEST['strSqlOrder'];
        $objScrap->intGridSqlPage = $_REQUEST['intSqlPage'];
        $objScrap->intGridSqlLimit = $_REQUEST['intSqlLimit'];
        $objScrap->updateGrid();
        $jsnData['grid'] = $objScrap->strGrid;
        $jsnData['pagination'] = $objScrap->strGridPagination;
        $jsnData['intSqlNumberOfRecords'] = $objScrap->intGridNumberOfRecords;
        break;
    case 'processRecord':
        $jsnData = array('blnGo'=>true,'strError'=>'','strField'=>'');
        $jsnForm = json_decode($objScrap->arrFormField,true);
        for($intIndex=0;$intIndex<count($jsnForm);$intIndex++){
            if($jsnForm[$intIndex]['TBL_DUPLICATE']==0){
                $strSql = "SELECT COUNT(*) AS \"COUNT\" FROM " . $objScrap->strTableName;
                $strSql .= " WHERE " . $jsnForm[$intIndex]['TBL_FIELD'] . " = '" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "'";
                if($_REQUEST['intRecordId']!=0){
                    $strSql .= " AND " . $objScrap->strTableIdField . " <> '" . $_REQUEST['intRecordId'] . "'";
                }
                $rstRecordCount = $objScrap->dbQuery($strSql);
                if($objScrap->getProperty('strDBError')==''){
                    if($rstRecordCount[0]['COUNT']!=0){
                        $jsnData['blnGo'] = false;
                        $jsnData['strError'] = $jsnForm[$intIndex]['TBL_NAME'] . " <b>" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "</b> ya existe";
                        $jsnData['strField'] = $jsnForm[$intIndex]['TBL_FIELD'];
                        $intIndex=count($jsnForm);
                    }
                }else{
                    $jsnData['blnGo'] = false;
                    $jsnData['strError'] = $objScrap->getProperty('strDBError');
                    $intIndex=count($jsnForm);
                }
                unset($rstRecordCount);
            }
        }
        if($jsnData['blnGo']){
            if($_REQUEST['intRecordId']==0){
                $strSql = "INSERT INTO " . $objScrap->strTableName . "(";
                $strInsertFields = '';
                $strInsertValues = '';
                for($intIndex=0;$intIndex<count($jsnForm);$intIndex++){
                    $strInsertFields .= $jsnForm[$intIndex]['TBL_FIELD'] . ",";
                    $strInsertValues .= "'" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "',";
                }
                $strSql .= substr($strInsertFields,0,strlen($strInsertFields)-1) . ") VALUES(" . substr($strInsertValues,0,strlen($strInsertValues)-1) . ") RETURNING " . $objScrap->strTableIdField . " INTO :intInsertedID";
                $objScrap->dbInsert($strSql);

//                echo $strSql . "<br /><br />";

                $intRecordId = $objScrap->getProperty('intLastInsertId');
            }else {
                $intRecordId = $_REQUEST['intRecordId'];
                $strSql = "UPDATE " . $objScrap->strTableName . " SET ";
                for ($intIndex = 0; $intIndex < count($jsnForm); $intIndex++) {
                    $strSql .= $jsnForm[$intIndex]['TBL_FIELD'] . " = '" . $_REQUEST[$jsnForm[$intIndex]['TBL_FIELD']] . "', ";
                }
                $strSql = substr($strSql, 0, strlen($strSql) - 2) . " WHERE " . $objScrap->strTableIdField . " = " . $intRecordId;
                $objScrap->dbUpdate($strSql);
            };
            $jsnRelation = json_decode($objScrap->arrTableRelation,true);
            if(count($jsnRelation)!=0) {
                $strSql = "DELETE FROM " . $jsnRelation[0]['TBL_TABLE'] . " WHERE " . $jsnRelation[0]['TBL_TARGET'] . " = " . $intRecordId;
                $objScrap->dbDelete($strSql);
                $intArrLength = $_REQUEST['intRelationCount'];
                $strFields = "";
                $intArrayIndex = 0;
                $arrRelation = array_fill(0, $intArrLength - 1, array_fill(0, count($jsnRelation), 0));
                foreach ($jsnRelation as $objRelation) {
                    $arrIds = explode("|", $_REQUEST[$objRelation['TBL_NAME']]);
                    array_splice($arrIds, count($arrIds) - 1);
                    $intIndex = 0;
                    while ($intIndex < $intArrLength) {
                        foreach ($arrIds as $objIds) {
                            $arrRelation[$intIndex][$intArrayIndex] = $objIds;
                            $intIndex++;
                        }
                    }
                    $intArrayIndex++;
                    unset($arrIds);
                    $strFields .= $objRelation['TBL_SOURCE'] . ",";
                }
                unset($objRelation);
                $strFields = $jsnRelation[0]['TBL_TARGET'] . "," . substr($strFields, 0, strlen($strFields) - 1);
                $strSql = "INSERT INTO " . $jsnRelation[0]['TBL_TABLE'] . "(" . $strFields . ") VALUES(" . $intRecordId . ",";
                foreach ($arrRelation as $objRelations) {
                    $strValues = '';
                    foreach ($objRelations as $objIds) {
                        $strValues .= $objIds . ",";
                    }
                    unset($objIds);
                    $strValues = substr($strValues, 0, strlen($strValues) - 1) . ")";
                    $objScrap->dbInsert($strSql . $strValues);
                }
                unset($objRelations);
            }
        }
        break;
    case 'deactivateRecord':
        $jsnData = array('blnGo'=>'true','strError'=>'');
        $strSql = "UPDATE " . $objScrap->strTableName . " SET " . $objScrap->strTableStatusField . " = " . $_REQUEST['intStatus'] . " WHERE " . $objScrap->strTableIdField . " = " . $_REQUEST['intRecordId'];
        $objScrap->dbUpdate($strSql);
        if($objScrap->getProperty('strDBError')!=''){
            $jsnData['blnGo'] = false;
            $jsnData['strError'] = $objScrap->getProperty('strDBError');
        }
        break;
    case 'getRelation':
        $jsnData = array();
        $strSql = "SELECT * FROM TBL_TABLE_RELATION WHERE TBL_TARGET_TABLE = " . $_REQUEST['intTableId'] . " AND TBL_PARENT = 0 ORDER BY TBL_PARENT";

//        echo $strSql . "<br /><br />";

        $rstRelation = $objScrap->dbQuery($strSql);
        foreach($rstRelation as $objRelation){
            $strSql = "SELECT " . $objRelation['TBL_SOURCE_ID_FIELD'] . ", " . $objRelation['TBL_SOURCE_DISPLAY_FIELD'] . " ";
            $strSql .= "FROM " . $objRelation['TBL_SOURCE_TABLE'] . " ";
            $strSql .= "WHERE " . $objRelation['TBL_SOURCE_STATUS_FIELD'] . " = 1 ";
            $strSql .= "ORDER BY " . $objRelation['TBL_SOURCE_DISPLAY_FIELD'] . ", " . $objRelation['TBL_SOURCE_ID_FIELD'];

//            echo $strSql . "<br /><br />";

            $rstRelationData = $objScrap->dbQuery($strSql);
            $numRows = $objScrap->intAffectedRows;
            $strRelationIds = "";
            $strRelationRows = "";
            if($objScrap->intAffectedRows!=0){
                foreach($rstRelationData as $objRelationData){
                    $strRelationIds .= $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']] . ",";
                    $blnSelected = false;
                    if($_REQUEST['intRecordId']!=0){
                        $strSql = 'SELECT COUNT(*) AS "COUNT" FROM ' . $objRelation['TBL_TABLE'] . ' WHERE ' . $objRelation['TBL_TARGET'] . ' = ' . $_REQUEST['intRecordId'] . ' AND ' . $objRelation['TBL_SOURCE'] . ' = ' . $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']];
                        $rstCount = $objScrap->dbQuery($strSql);
                        if($rstCount[0]['COUNT']!=0){
                            $blnSelected = true;
                        }
                        unset($rstCount);
                    }else{
                        if($numRows==1){
                            $blnSelected = true;
                        }
                    }
                    $strRelationRows .= '<tr>';
                    $strRelationRows .= '<td id="tdRelation_' . $objRelation['TBL_NAME'] . '_' . $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']] . '"';
//                    if($numRows>1) {
                        $strRelationRows .= ' onclick="switchSelected(' . "'" . $objRelation['TBL_NAME'] . "'," . $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']] . ')"';
//                    }
                    $strRelationRows .= ' class="td';
                    if($blnSelected){
                        $strRelationRows .= 'Active"';
//                        if($numRows==1){
//                            $strRelationRows .= ' style="cursor:auto;"';
//                        }
                        $strRelationRows .= '>&#10004';
                    }else{
                        $strRelationRows .= 'NonActive">&#10006';
                    }
                    $strRelationRows .= '</td><td>' . $objRelationData[$objRelation['TBL_SOURCE_DISPLAY_FIELD']] . '</td>';
                    $strRelationRows .= '</tr>';
                }
                $strRelationIds = substr($strRelationIds,0,strlen($strRelationIds)-1);
            }else{
                $strRelationRows .= '<tr>';
                $strRelationRows .= '<td colspan="2">no existen registros</td>';
                $strRelationRows .= '</tr>';
            }
            array_push($jsnData,array('strTable'=>$objRelation['TBL_NAME'],'strDisplay'=>$objRelation['TBL_DISPLAY'],'strRows'=>$strRelationRows,'strIds'=>str_replace(",","|",$strRelationIds)));
            getRelationbyLevel($_REQUEST['intTableId'], $objRelation['TBL_ID'], $strRelationIds);
        }
        unset($objRelation);
        unset($rstRelation);
        break;
};
unset($objScrap);
echo json_encode($jsnData);

function getRelationbyLevel($intTableId, $intParent, $strRelationIds){
    global $objScrap;
    global $jsnData;
    $strSql = "SELECT * FROM TBL_TABLE_RELATION WHERE TBL_TARGET_TABLE = " . $intTableId . " AND TBL_PARENT = " . $intParent . " ORDER BY TBL_PARENT";

//    echo $strSql . "<br /><br />";

    $rstRelation = $objScrap->dbQuery($strSql);
    foreach($rstRelation as $objRelation){
        $strSql = "SELECT * FROM TBL_TABLE_RELATION WHERE TBL_ID = " . $objRelation['TBL_RELATION'];
        $rstRelated = $objScrap->dbQuery($strSql);
        $strSql = "SELECT " . $objRelation['TBL_SOURCE_ID_FIELD'] . ", " . $objRelation['TBL_SOURCE_DISPLAY_FIELD'] . " ";
        $strSql .= "FROM " . $objRelation['TBL_SOURCE_TABLE'] . " ";
        $strSql .= "WHERE " . $objRelation['TBL_SOURCE_ID_FIELD'] . " IN (";
        $strSql .= "SELECT DISTINCT(" . $rstRelated[0]['TBL_SOURCE'] . ") ";
        $strSql .= "FROM " . $rstRelated[0]['TBL_TABLE'] . " ";
        $strSql .= "WHERE " . $rstRelated[0]['TBL_TARGET'] . " IN (" . $strRelationIds . ")) ";
        $strSql .= "AND " . $objRelation['TBL_SOURCE_STATUS_FIELD'] . " = 1 ";
        $strSql .= "ORDER BY " . $objRelation['TBL_SOURCE_DISPLAY_FIELD'] . ", " . $objRelation['TBL_SOURCE_ID_FIELD'];

//        echo $strSql . "<br /><br />";

        $rstRelationData = $objScrap->dbQuery($strSql);
        $numRows = $objScrap->intAffectedRows;
        $strRelationIds = "";
        $strRelationRows = "";
        if($objScrap->intAffectedRows!=0){
            foreach($rstRelationData as $objRelationData){
                $strRelationIds .= $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']] . ",";
                $blnSelected = false;
                if($_REQUEST['intRecordId']!=0){
                    $strSql = 'SELECT COUNT(*) AS "COUNT" FROM ' . $objRelation['TBL_TABLE'] . ' WHERE ' . $objRelation['TBL_TARGET'] . ' = ' . $_REQUEST['intRecordId'] . ' AND ' . $objRelation['TBL_SOURCE'] . ' = ' . $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']];
                    $rstCount = $objScrap->dbQuery($strSql);
                    if($rstCount[0]['COUNT']!=0){
                        $blnSelected = true;
                    }
                    unset($rstCount);
                }else{
                    if($numRows==1){
                        $blnSelected = true;
                    }
                }
                $strRelationRows .= '<tr>';
                $strRelationRows .= '<td id="tdRelation_' . $objRelation['TBL_NAME'] . '_' . $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']] . '"';
//                if($numRows>1) {
                    $strRelationRows .= ' onclick="switchSelected(' . "'" . $objRelation['TBL_NAME'] . "'," . $objRelationData[$objRelation['TBL_SOURCE_ID_FIELD']] . ')"';
//                }
                $strRelationRows .= ' class="td';
                if($blnSelected){
                    $strRelationRows .= 'Active"';
//                    if($numRows==1){
//                        $strRelationRows .= ' style="cursor:auto;"';
//                    }
                    $strRelationRows .= '>&#10004';
                }else{
                    $strRelationRows .= 'NonActive">&#10006';
                }
                $strRelationRows .= '</td><td>' . $objRelationData[$objRelation['TBL_SOURCE_DISPLAY_FIELD']] . '</td>';
                $strRelationRows .= '</tr>';
            }
            $strRelationIds = substr($strRelationIds,0,strlen($strRelationIds)-1);
        }else{
            $strRelationRows .= '<tr>';
            $strRelationRows .= '<td colspan="2">no existen registros</td>';
            $strRelationRows .= '</tr>';
        }
        unset($rstRelated);
        array_push($jsnData,array('strTable'=>$objRelation['TBL_NAME'],'strDisplay'=>$objRelation['TBL_DISPLAY'],'strRows'=>$strRelationRows,'strIds'=>str_replace(",","|",$strRelationIds)));
        getRelationbyLevel($intTableId, $objRelation['TBL_ID'], $strRelationIds);
    }
    unset($objRelation);
    unset($rstRelation);
}
?>